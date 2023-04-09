<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Config;
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

}
