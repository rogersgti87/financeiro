<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceNotification;
use App\Models\CustomerServices;
use Illuminate\Support\Facades\Http;
use DB;

class InvoicesController extends Controller
{
  protected $model;
  protected $request;
  protected $fields;
  protected $datarequest;

  public function __construct(Invoice $invoice, Request $request)
  {
    $this->model                =  $invoice;
    $this->request              =  $request;

    $this->datarequest = [
      'titulo'               =>  'Todas as Faturas',
      'diretorio'            =>  'backend.invoices',
      'url_action'               =>  'invoices'
    ];
  }


  public function index()
  {
    try {
      $order = $this->request->input('order') == 'asc' ? 'desc' : 'asc';
      $column_name = null;

      if ($this->request->input('column')) {
        $column = $this->request->input('column');
        $column_name = "$column $order";
      } else {
        $column_name = "id desc";
      }

      $field = $this->request->input('field') ? $this->request->input('field') : 'date_invoice';
      $operador = $this->request->input('operador') ? $this->request->input('operador') : 'like';
      $value = $this->request->input('value') ? $this->request->input('value') : '';

      if ($field == 'data' || $field == 'dataini' || $field == 'datafim') {
        $value = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
      }

      if ($operador == 'like') {
        $newValue = "'%$value%'";
      } else {
        $newValue = "'$value'";
      }

      $results = DB::table('invoices as i')
        ->select('i.id', 'i.customer_service_id', 'i.description', 'i.price', 'i.payment_method', 'i.date_invoice', 'i.date_end',
        'i.date_payment', 'i.status', 'c.name as nameCustomer','c.id as customer_id')
        ->join('customer_services as cs', 'cs.id', 'i.customer_service_id')
        ->join('customers as c', 'cs.customer_id', 'c.id')
        ->orderByRaw("$column_name")
        ->whereraw("$field $operador $newValue")
        ->paginate(30);
    } catch (\Exception $err) {
      return response()->json($err->getMessage(), 500);
    }

    return view($this->datarequest['diretorio'] . '.index', compact('results', 'order'))->with($this->datarequest);
  }


  public function create($customer_id)
  {
    $customer_services  = DB::table('customer_services as cs')
                                ->select('cs.id',DB::raw("CONCAT(s.name,' - ',cs.dominio) as service_name"))
                                ->join('services as s','cs.service_id','s.id')
                                ->where('cs.customer_id',$customer_id)
                                ->get();

    return view('backend.customers.invoicesForm', compact('customer_id','customer_services'))->with($this->datarequest);
  }

  public function notification($invoice_id)
  {

    $notifications = DB::table('invoice_notifications as a')
                        ->select('a.id','a.invoice_id', 'a.date' ,'ev.timestamp', 'a.senpulse_email_id', 'ev.event','a.subject_whatsapp',
                         'ev.recipient', 'ev.subject', 'a.type_send','a.status','a.message_status','a.message')
                        ->leftJoin('email_events as ev','ev.message_id','a.senpulse_email_id')
                        ->where('a.invoice_id',$invoice_id)
                        ->orderby('ev.timestamp','asc')
                        ->get();


    return view('backend.customers.invoicesNotification', compact('notifications'))->with($this->datarequest);
  }


  public function store()
  {

    $model = new $this->model;
    $result = $this->request->all();
    $msg_success = '';

    $validator = Validator::make($result, [
      'customer_service_id' => "required",
      'price'               => 'required',
      'date_invoice'        => 'required',
      'date_end'            => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }


    $cs  = DB::table('customer_services as cs')
            ->select('cs.id','cs.dominio','s.name','cs.period','c.name as customer_name','c.notification_whatsapp',
            'c.email','c.email2','c.phone','c.company')
            ->join('services as s','cs.service_id','s.id')
            ->join('customers as c','cs.customer_id','c.id')
            ->where('cs.id',$result['customer_service_id'])
            ->first();

    $description = '';
    if ($cs->period == 'unico') {
        $description = $cs->name . ' - ' . $cs->dominio . ' - pagamento unico';
      } else {
        if ($cs->period == 'mensal') {
          $period = 1;
        } else if ($cs->period == 'trimestral') {
          $period = 3;
        } else if ($cs->period == 'anual') {
          $period = 12;
        }
        $description = $cs->name . ' - ' . $cs->dominio . ' (de: ' . Carbon::createFromFormat('d/m/Y',$result['date_end'])->format('d/m/Y') . ' até ' . Carbon::createFromFormat('d/m/Y',$result['date_end'])->addMonth($period)->format('d/m/Y') . ') - R$ ' . number_format($result['price'], 2);
      }



    $model->customer_service_id = $result['customer_service_id'];
    $model->description         = $description;
    $model->price               = $result['price'];
    $model->status              = $result['status'];
    $model->payment_method      = $result['payment_method'];
    $model->date_invoice        = Carbon::createFromFormat('d/m/Y',$result['date_invoice'])->format('Y-m-d');
    $model->date_end            = Carbon::createFromFormat('d/m/Y',$result['date_end'])->format('Y-m-d');
    $model->date_payment        = $result['date_payment'] != null ? Carbon::createFromFormat('d/m/Y',$result['date_payment'])->format('Y-m-d') : null;
    $model->created_at          = Carbon::now();
    $model->updated_at          = Carbon::now();

    try {
      $model->save();

      $invoice = DB::table('invoices')->where('id',$model->id)->first();

        if($invoice->payment_method == 'Pix'){
            $generatePixInvoice = Invoice::generatePixPayment($invoice->id);

            if($generatePixInvoice['status'] == 'reject'){
                return response()->json($generatePixInvoice['message'], 422);
            }

            try {
                Invoice::where('id',$invoice->id)->update([
                    'transaction_id' => $generatePixInvoice['transaction_id']
                ]);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return response()->json($e->getMessage(), 422);
            }

        }else{

            //Gerar Boleto
            $generateBilletInvoice = Invoice::generateBilletPayment($invoice->id);

            if($generateBilletInvoice['status'] == 'reject'){
                return response()->json($generateBilletInvoice['message'], 422);
            }

            try {
                Invoice::where('id',$invoice->id)->update([
                    'transaction_id' => $generateBilletInvoice['transaction_id']
                ]);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return response()->json($e->getMessage(), 422);
            }

        }


        $verifyTransaction = DB::table('invoices')->select('transaction_id')->where('id',$invoice->id)->first();

        if($invoice->payment_method == 'Pix'){
          $getInfoPixPayment      = Invoice::verifyStatusPixPayment($verifyTransaction->transaction_id);

          if(!file_exists(public_path('pix')))
                \File::makeDirectory(public_path('pix'));

          $image = $getInfoPixPayment->qr_code_base64;
          $imageName = $invoice->id.'.'.'png';
          \File::put(public_path(). '/pix/' . $imageName, base64_decode($image));

        }else{
          $getInfoBilletPayment   = Invoice::verifyStatusBilletPayment($verifyTransaction->transaction_id);
        }


        $details = [
            'title'                     => 'Nova fatura gerada',
            'customer'                  => $cs->customer_name,
            'customer_email'            => $cs->email,
            'customer_email2'           => $cs->email2,
            'customer_phone'            => $cs->phone,
            'notification_whatsapp'     => $cs->notification_whatsapp,
            'company'                   => $cs->company,
            'data_fatura'               => date('d/m/Y', strtotime($invoice->date_invoice)),
            'data_vencimento'           => date('d/m/Y', strtotime($invoice->date_end)),
            'proxima_data_vencimento'   => date('d/m/Y', strtotime($invoice->date_end)),
            'price'                     => number_format($invoice->price, 2,',','.'),
            'payment_method'            => $invoice->payment_method,
            'description'               => $invoice->description,
            'description_whatsapp'      => $cs->name . ' - ' . $cs->dominio,
            'invoice_id'                => $invoice->id,
            'url_base'                  => url('/'),
            'pix_qrcode_image_url'      =>  '',
            'pix_emv'                   =>  '',
            'billet_digitable_line'     =>  '',
            'billet_url_slip_pdf'       =>  '',
            'billet_url_slip'           =>  '',
        ];

        if($invoice->payment_method == 'Boleto'){
            $details['billet_digitable_line'] = $getInfoBilletPayment->status_request->bank_slip->digitable_line;
            $details['billet_url_slip_pdf']   = $getInfoBilletPayment->status_request->bank_slip->url_slip_pdf;
            $details['billet_url_slip']       = $getInfoBilletPayment->status_request->bank_slip->url_slip;
        }else{

            $details['pix_qrcode_image_url']  = $getInfoPixPayment->qr_code_base64;
            $details['pix_emv']               = $getInfoPixPayment->qr_code;
        }


        $details['body']  = view('mails.newinvoice',$details)->render();

        if (isset($result['send_invoice']) == 1) {
            InvoiceNotification::sendNotification($details);
        }


    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json($e->getMessage(), 500);
    }
    return response()->json('Fatura salva com sucesso' . $msg_success, 200);
  }


  public function show($id)
  {
    $result = $this->model::find($id);
    $getCustomer = DB::table('customers')->where('id', $result->customer_id)->first();

    return view($this->datarequest['diretorio'] . '.details', compact('result', 'getCustomer'))->with($this->datarequest);
  }


  public function edit($customer_id, $id)
  {
    $result = $this->model::where('id', $id)->first();

    $customer_services  = DB::table('customer_services as cs')
                                ->select('cs.id',DB::raw("CONCAT(s.name,' - ',cs.dominio) as service_name"))
                                ->join('services as s','cs.service_id','s.id')
                                ->where('cs.customer_id',$customer_id)
                                ->get();

    return view('backend.customers.invoicesForm', compact('result', 'customer_id','customer_services'))->with($this->datarequest);
  }


  public function update($id)
  {
    $model = $this->model::find($id);
    $result = $this->request->all();

    $validator = Validator::make($result, [
        'payment_method'    => 'required',
        'price'             => 'required',
        'date_end'          => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    $model->customer_service_id = $result['customer_service_id'];
    $model->status          = $result['status'];
    $model->payment_method  = $result['payment_method'];
    $model->date_end        = Carbon::createFromFormat('d/m/Y',$result['date_end'])->format('Y-m-d');
    $model->date_payment    = $result['date_payment'] != null ? Carbon::createFromFormat('d/m/Y',$result['date_payment'])->format('Y-m-d') : null;
    $model->updated_at      = Carbon::now();

    try {
      $model->save();

      if(isset($result['sendmailinvoice']) == 1){
        $this->confirm($id);
      }

    } catch (\Exception $e) {
      return response()->json($e->getMessage(), 500);
    }

    return response()->json('Fatura salva com sucesso', 200);
  }

  public function destroy()
  {
    $model = new $this->model;
    $data = $this->request->all();

    if (!isset($data['selectedInvoices'])) {
      return response()->json('Selecione ao menos uma fatura', 422);
    }

    try {
      foreach ($data['selectedInvoices'] as $result) {
        $find = $model->find($result);
        $notifications = InvoiceNotification::where('invoice_id',$find->id)->first();

        if($notifications != null && $notifications->senpulse_email_id != null){
            DB::table('email_events')->where('message_id',$notifications->senpulse_email_id)->delete();
            $notifications->delete();
        }


        $find->delete();
      }
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json($e->getMessage(), 500);
    }

    return response()->json(true, 200);
  }

  public function confirm($id)
  {

    $msg_success = '';

    try {

        $invoice = DB::table('invoices as i')
                ->select('i.id','i.date_invoice','i.date_end','i.description','c.email','c.email2','c.name','c.notification_whatsapp','c.company','c.document','c.phone','c.address','c.number','c.complement',
                'c.district','c.city','c.state','c.cep','i.payment_method','s.id as service_id','s.name as service_name','i.price as service_price','cs.dominio','i.date_payment')
                ->join('customer_services as cs','i.customer_service_id','cs.id')
                ->join('customers as c','cs.customer_id','c.id')
                ->join('services as s','cs.service_id','s.id')
                ->where('i.id',$id)
                ->first();

        $msg_success = ' e E-mail encaminhado para o Cliente';

         $details = [
            'title'                     => 'Confirmação de Pagamento',
            'customer'                  => $invoice->name,
            'customer_email'            => $invoice->email,
            'customer_email2'           => $invoice->email2,
            'customer_phone'            => $invoice->phone,
            'notification_whatsapp'     => $invoice->notification_whatsapp,
            'company'                   => $invoice->company,
            'data_fatura'               => date('d/m/Y', strtotime($invoice->date_invoice)),
            'data_vencimento'           => date('d/m/Y', strtotime($invoice->date_end)),
            'data_pagamento'            => date('d/m/Y', strtotime($invoice->date_payment)),
            'price'                     => number_format($invoice->service_price, 2,',','.'),
            'payment_method'            => $invoice->payment_method,
            'description'               => $invoice->description,
            'description_whatsapp'      => $invoice->service_name . ' - ' . $invoice->dominio,
            'invoice_id'                => $invoice->id,
            'status_payment'            => 'Pago',
            'url_base'                  => url('/')
        ];


        $details['body']  = view('mails.payinvoice',$details)->render();

        InvoiceNotification::sendNotificationConfirm($details);

//        \Mail::to($invoice->email)->send(new \App\Mail\InvoicePay($details));


    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json($e->getMessage(), 500);
    }

    return response()->json('Fatura Confirmada com sucesso' . $msg_success, 200);
  }
}
