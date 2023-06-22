<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CustomerServices;
use App\Models\Invoice;
use DB;
use Dotenv\Result\Result;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Support\Facades\Storage;
use App\Models\InvoiceNotification;

class CustomerServicesController extends Controller
{
  protected $model;
  protected $request;
  protected $fields;
  protected $datarequest;

  public function __construct(CustomerServices $customerservice, Request $request)
  {
    $this->model                =  $customerservice;
    $this->request              =  $request;

    $this->datarequest = [
      'titulo'               =>  'Serviços de Clientes',
      'diretorio'            =>  'backend.customerservices',
      'url_action'           =>  'customerservices'
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

      $field = $this->request->input('field') ? $this->request->input('field') : 'name';
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

      $results = DB::table('customerservices')
        ->select('id', 'name', 'company', 'email','email2', 'status', 'phone', 'created_at')
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
    $getServices = DB::table('services as a')
      ->where('a.status', 'Ativo')
      ->get();
    return view('backend.customers.servicesForm', compact('getServices', 'customer_id'))->with($this->datarequest);
  }


  public function store()
  {
    $model = new $this->model;
    $result = $this->request->all();


    $rules = [
      'service_id' => 'required',
      'date_start' => 'required',
      'date_end' => 'required',
      'price' => 'required'
    ];

    $messages = [
      'service_id.required' => 'selecione um serviço',
      'date_start.required' => 'data de início é obrigatório',
      'date_end.required' => 'data de vencimento é obrigatório',
      'price.min' => 'preço é obrigatório'
    ];

    $validator = Validator::make($result, $rules, $messages);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    $model->customer_id     = $result['customer_id'];
    $model->service_id      = $result['service_id'];
    $model->dominio         = $result['dominio'];
    $model->status          = $result['status'];
    $model->date_start      = Carbon::createFromFormat('d/m/Y',$result['date_start'])->format('Y-m-d');
    $model->date_end        = Carbon::createFromFormat('d/m/Y',$result['date_end'])->format('Y-m-d');
    $model->price           =  $result['price'];
    $model->period          = $result['period'];
    $model->created_at      = Carbon::now();
    $model->updated_at      = Carbon::now();

    $msg_success = '';

      $model->save();
      $getService = DB::table('services')->where('id', $result['service_id'])->first();

      // Nesse momento preciso disparar o Evento de Gerar Fatura
      if (isset($result['generate_invoice'])) {
        $msg_success = ' e fatura gerada.';
        $getCustomer = DB::table('customers')->where('id', $result['customer_id'])->first();
        $period = null;
        $description = '';

        if ($result['period'] == 'unico') {
          $description = $getService->name . ' - ' . $result['dominio'] . ' - pagamento unico';
        } else {
          if ($result['period'] == 'mensal') {
            $period = 1;
          } else if ($result['period'] == 'trimestral') {
            $period = 3;
          } else if ($result['period'] == 'anual') {
            $period = 12;
          }
          $description = $getService->name . ' - ' . $result['dominio'] . ' (de: ' . Carbon::createFromFormat('d/m/Y',$result['date_end'])->format('d/m/Y') . ' até ' . Carbon::createFromFormat('d/m/Y',$result['date_end'])->addMonth($period)->format('d/m/Y') . ') - R$ ' . number_format($result['price'], 2);
        }


        $newInvoice = DB::table('invoices')->insertGetId([
            'customer_service_id' => $model->id,
            'description' => $description,
            'price' => $result['price'],
            'payment_method' => $getCustomer->payment_method,
            'date_invoice' => Carbon::now(),
            'date_end' => Carbon::createFromFormat('d/m/Y',$result['date_end'])->format('Y-m-d'),
            'date_payment' => null,
            'status' => 'nao_pago',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $invoice = DB::table('invoices')->where('id',$newInvoice)->first();

        if($invoice->payment_method == 'Pix'){
            $generatePixInvoice = Invoice::generatePixPayment($newInvoice);

            if($generatePixInvoice['status'] == 'reject'){
                return response()->json($generatePixInvoice['message'], 422);
            }

            try {
                Invoice::where('id',$newInvoice)->update([
                    'transaction_id' => $generatePixInvoice['transaction_id']
                ]);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return response()->json($e->getMessage(), 422);
            }

        }else{

            //Gerar Boleto
            $generateBilletInvoice = Invoice::generateBilletPayment($newInvoice);

            if($generateBilletInvoice['status'] == 'reject'){
                return response()->json($generateBilletInvoice['message'], 422);
            }

            try {
                Invoice::where('id',$newInvoice)->update([
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
        }else{
          $getInfoBilletPayment   = Invoice::verifyStatusBilletPayment($verifyTransaction->transaction_id);
        }


        $details = [
            'title'                     => 'Nova fatura gerada',
            'customer'                  => $getCustomer->name,
            'customer_email'            => $getCustomer->email,
            'customer_email2'           => $getCustomer->email2,
            'customer_phone'            => $getCustomer->phone,
            'notification_whatsapp'     => $getCustomer->notification_whatsapp,
            'company'                   => $getCustomer->company,
            'data_fatura'               => date('d/m/Y', strtotime($invoice->date_invoice)),
            'data_vencimento'           => date('d/m/Y', strtotime($invoice->date_end)),
            'proxima_data_vencimento'   => date('d/m/Y', strtotime($invoice->date_end)),
            'price'                     => number_format($invoice->price, 2,',','.'),
            'payment_method'            => $invoice->payment_method,
            'description'               => $invoice->description,
            'description_whatsapp'      => $getService->name . ' - ' . $result['dominio'],
            'invoice_id'                => $invoice->id,
            'url_base'                  => url('/'),
            'pix_qrcode_image_url'      =>  '',
            'pix_emv'                   =>  '',
            'billet_digitable_line'     =>  '',
            'billet_url_slip_pdf'       =>  '',
            'billet_url_slip'           =>  '',
        ];

        if($invoice->payment_method == 'Boleto'){
            $details['billet_digitable_line']   = $getInfoBilletPayment->status_request->bank_slip->digitable_line;
            $details['billet_url_slip_pdf']     = $getInfoBilletPayment->status_request->bank_slip->url_slip_pdf;
            $details['billet_url_slip']         = $getInfoBilletPayment->status_request->bank_slip->url_slip;
        }else{
            $details['pix_qrcode_image_url']  = $getInfoPixPayment->qr_code_base64;
            $details['pix_emv']               = $getInfoPixPayment->qr_code;
        }


        $details['body']  = view('mails.newinvoice',$details)->render();

        if (isset($result['send_invoice']) == 1) {
            InvoiceNotification::sendNotification($details);
        // dispara um unico e-mail com todo o conteúdo
        //\Mail::send('mails.newinvoice', $details, function($message)use($details,$getCustomer) {
          //  $message->to($getCustomer->email)
            //        ->subject('Nova Fatura');

                    // if($invoice->payment_method != 'pix'){
                    //     $message->attach($details['file_bill_pdf']);
                    // }

        //});
    }



      }

    return response()->json('Serviço cadastrado com sucesso' . $msg_success, 200);

  }

  public function show($id)
  {

    $result = $this->model::find($id);
    $allCustomerServicess = $this->model::where('id', '!=', $id)->get();

    $myServices = DB::table('customer_services as a')
      ->select('a.id as id', 'a.customerservice_id', 'a.service_id', 'a.dominio', 'a.date_start', 'a.date_end', 'a.price', 'a.period', 'a.status', 'b.name as nameService')
      ->join('services as b', 'a.service_id', 'b.id')
      ->where('a.customerservice_id', $id)
      ->paginate(30);

    return view($this->datarequest['diretorio'] . '.details', compact('result', 'allCustomerServicess', 'myServices'))->with($this->datarequest);

  }


  public function edit($customer_id, $id)
  {
    $result = $this->model::where('id', $id)->first();
    $getServices = DB::table('services as a')
      ->where('a.status', 'Ativo')
      ->get();

    return view('backend.customers.servicesForm', compact('result', 'getServices', 'customer_id'))->with($this->datarequest);
  }


  public function update($id)
  {
    $model = $this->model::find($id);
    $result = $this->request->all();

    $validator = Validator::make($result, [
      'dominio'     => "required",
      'date_start' => 'required',
      'date_end' => 'required',
      'price' => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    $model->customer_id = $result['customer_id'];
    $model->service_id = $result['service_id'];
    $model->dominio = $result['dominio'];
    if (isset($result['status'])) {
      $model->status = $result['status'];
    } else {
      $model->status = 'Pendente';
    }
    $model->date_start      = Carbon::createFromFormat('d/m/Y',$result['date_start'])->format('Y-m-d');
    $model->date_end        = Carbon::createFromFormat('d/m/Y',$result['date_end'])->format('Y-m-d');
    $model->price = $result['price'];
    $model->period = $result['period'];
    $model->updated_at = Carbon::now();

    try {
      $model->save();
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json($e->getMessage(), 500);
    }

    return response()->json('Serviço salvo com sucesso', 200);
  }


  public function destroy()
  {
    $model = new $this->model;
    $data = $this->request->all();

    if (!isset($data['selectedServices'])) {
      return response()->json('Selecione ao menos um serviço', 422);
    }

    try {
      foreach ($data['selectedServices'] as $result) {
        $find = $model->find($result);
        $find->delete();
      }
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json($e->getMessage(), 500);
    }

    return response()->json(true, 200);
  }





}
