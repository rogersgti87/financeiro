<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Config;
use App\Models\Invoice;
use App\Models\InvoiceNotification;
use Illuminate\Support\Facades\Http;
use DB;

class WebHookController extends Controller
{

  public function __construct()
  {

  }


  public function index(Request $request)
  {
    $data = $request->all();
    if($data != null){
        foreach($data as $result){
            DB::table('email_events')->insert([
                'event'         =>  $result['event'],
                'timestamp'     =>  Carbon::createFromTimestamp($result['timestamp'])->format('Y-m-d H:i:s'),
                'message_id'    =>  $result['message_id'],
                'recipient'     =>  $result['recipient'],
                'sender'        =>  $result['sender'],
                'subject'       =>  $result['subject'],
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }
    }

  }

  public function paghiper(Request $request)
  {
    $data = $request->all();

    $api_token  = env('API_TOKEN_PAG_HIPER');
    $api_key    = env('API_KEY_PAG_HIPER');

    $invoice = Invoice::where('transaction_id',$result->transaction_id)->first();

    if($invoice->payment_method == 'Pix'){
        $url = 'https://pix.paghiper.com/invoice/notification/';
    }else{
        $url = 'https://api.paghiper.com/transaction/notification/';
    }

    $response = Http::withHeaders([
        'accept' => 'application/json',
        'content-type' => 'application/json',
    ])->post($url,[
        'token'             => $api_token,
        'apiKey'            => $data['apiKey'],
        'transaction_id'    => $data['transaction_id'],
        'notification_id'   => $data['notification_id']
    ]);

    $result = $response->getBody();

    $result = json_decode($result)->status_request;


    if($result->status == 'completed' || $result->status == 'paid'){
        Invoice::where('id',$result->order_id)->where('transaction_id',$result->transaction_id)->update([
            'status'       =>   'pago',
            'date_payment' =>   Carbon::now(),
            'updated_at'   =>   Carbon::now()
        ]);


        $invoice = DB::table('invoices as i')
        ->select('i.id','i.date_invoice','i.date_end','i.description','c.email','c.email2','c.name','c.company','c.document','c.phone','c.address','c.number','c.complement',
        'c.district','c.city','c.state','c.cep','c.payment_method','s.id as service_id','s.name as service_name','i.price as service_price','cs.dominio')
        ->join('customer_services as cs','i.customer_service_id','cs.id')
        ->join('customers as c','cs.customer_id','c.id')
        ->join('services as s','cs.service_id','s.id')
        ->where('i.id',$result->order_id)
        ->where('transaction_id',$result->transaction_id)
        ->first();

        $details = [
            'title'                     => 'Confirmação de Pagamento',
            'customer'                  => $invoice->name,
            'customer_email'            => $invoice->email,
            'customer_email2'           => $invoice->email2,
            'customer_phone'            => $invoice->phone,
            'company'                   => $invoice->company,
            'data_fatura'               => date('d/m/Y', strtotime($invoice->date_invoice)),
            'data_vencimento'           => date('d/m/Y', strtotime($invoice->date_end)),
            'data_pagamento'            => date('d/m/Y', strtotime($invoice->date_end)),
            'price'                     => number_format($invoice->service_price, 2),
            'payment_method'            => $invoice->payment_method,
            'description'               => $invoice->description,
            'description_whatsapp'      => $invoice->service_name . ' - ' . $invoice->dominio,
            'invoice_id'                => $invoice->id,
            'status_payment'            => 'Pago',
            'url_base'                  => url('/')
        ];


        $details['body']  = view('mails.payinvoice',$details)->render();

        InvoiceNotification::sendNotificationConfirm($details);


    }else if($result->status == 'canceled' || $result->status == 'refunded'){
        Invoice::where('id',$result->order_id)->where('transaction_id',$result->transaction_id)->update([
            'status'       =>   'cancelado',
            'date_payment' =>   Carbon::now(),
            'updated_at'   =>   Carbon::now()
        ]);
    }


  }


}
