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


    $response = Http::withHeaders([
        'accept' => 'application/json',
        'content-type' => 'application/json',
    ])->post('https://pix.paghiper.com/invoice/notification/',[
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
    }else if($result->status == 'canceled' || $result->status == 'refunded'){
        Invoice::where('id',$result->order_id)->where('transaction_id',$result->transaction_id)->update([
            'status'       =>   'cancelado',
            'date_payment' =>   Carbon::now(),
            'updated_at'   =>   Carbon::now()
        ]);
    }


  }


}
