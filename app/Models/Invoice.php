<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use DB;
use InvoiceNotification;

class Invoice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_service_id',
        'description',
        'price',
        'status',
        'payment_method',
        'date_invoice',
        'date_end',
        'date_payment',
        'transaction_id',
    ];


    public static function generateBilletPayment($invoice_id){


        $invoice = DB::table('invoices as i')
                    ->select('i.id','c.email','c.email2','c.name','c.document','c.phone','c.address','c.number','c.complement',
                    'c.district','c.city','c.state','c.cep','s.id as service_id','s.name as service_name','s.price as service_price',
                    DB::raw("DATEDIFF (i.date_end,i.date_invoice) as days_due_date"))
                    ->join('customer_services as cs','i.customer_service_id','cs.id')
                    ->join('customers as c','cs.customer_id','c.id')
                    ->join('services as s','cs.service_id','s.id')
                    ->where('i.id',$invoice_id)
                    ->first();

        $api_token  = env('API_TOKEN_PAG_HIPER');
        $api_key    = env('API_KEY_PAG_HIPER');

        //Gerar PIX
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
          ])->post('https://api.paghiper.com/transaction/create/',[
            'apiKey'            =>  $api_key,
            'order_id'          =>  $invoice->id,
            'payer_email'       =>  $invoice->email,
            'payer_name'        =>  $invoice->name,
            'payer_cpf_cnpj'    =>  $invoice->document,
            'payer_phone'       =>  $invoice->phone,
            'payer_street'      =>  $invoice->address,
            'payer_number'      =>  $invoice->number,
            'payer_complement'  =>  $invoice->complement,
            'payer_district'    =>  $invoice->district,
            'payer_city'        =>  $invoice->city,
            'payer_state'       =>  $invoice->state,
            'payer_zip_code'    =>  $invoice->cep,
            'type_bank_slip'    => 'boletoA4',
            'days_due_date'     =>  $invoice->days_due_date,
            'late_payment_fine' => '1',// Percentual de multa após vencimento.
            'per_day_interest'  => true, // Juros após vencimento.
            'items' => array([
                'item_id'       => $invoice->service_id,
                'description'   => $invoice->service_name,
                'quantity'      => 1,
                'price_cents'   => bcmul($invoice->service_price,100)
          ])
          ]);



        $result = $response->getBody();

        $result = json_decode($result)->create_request;

        if($result->result == 'reject'){
            return ['staus' => 'reject', 'message' => $result->response_message];
        }else{

            return ['status' => 'ok', 'transaction_id' => $result->transaction_id];
        }

      }

    public static function generatePixPayment($invoice_id){

      $invoice = DB::table('invoices as i')
      ->select('i.id','c.email','c.email2','c.name','c.document','c.phone','c.address','c.number','c.complement','c.district','c.city','c.state','c.cep','s.id as service_id','s.name as service_name','s.price as service_price')
      ->join('customer_services as cs','i.customer_service_id','cs.id')
      ->join('customers as c','cs.customer_id','c.id')
      ->join('services as s','cs.service_id','s.id')
      ->where('i.id',$invoice_id)
      ->first();


        $api_token  = env('API_TOKEN_PAG_HIPER');
        $api_key    = env('API_KEY_PAG_HIPER');

        //Gerar PIX
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
          ])->post('https://pix.paghiper.com/invoice/create/',[
            'apiKey'            =>  $api_key,
            'order_id'          =>  $invoice->id,
            'payer_email'       =>  $invoice->email,
            'payer_name'        =>  $invoice->name,
            'payer_cpf_cnpj'    =>  $invoice->document,
            'days_due_date'     =>  90,
            'items' => array([
                'item_id'       => $invoice->service_id,
                'description'   => $invoice->service_name,
                'quantity'      => 1,
                'price_cents'   => bcmul($invoice->service_price,100)
          ])
          ]);



        $result = $response->getBody();

        $result = json_decode($result)->pix_create_request;

        if($result->result == 'reject'){
            return ['staus' => 'reject', 'message' => $result->response_message];
        }else{

            return ['status' => 'ok', 'transaction_id' => $result->transaction_id];
        }


    }


    public static function verifyStatusBilletPayment($transaction_id){

        $api_token  = env('API_TOKEN_PAG_HIPER');
        $api_key    = env('API_KEY_PAG_HIPER');


        //Consultar status da transação
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.paghiper.com/transaction/status/',[
            'token'             => $api_token,
            'apiKey'            => $api_key,
            'transaction_id'    => $transaction_id
        ]);

        $result = $response->getBody();
        return json_decode($result);

    }

    public static function verifyStatusPixPayment($transaction_id){

        $api_token  = env('API_TOKEN_PAG_HIPER');
        $api_key    = env('API_KEY_PAG_HIPER');

        //Consultar status da transação
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://pix.paghiper.com/invoice/status/',[
            'token'             => $api_token,
            'apiKey'            => $api_key,
            'transaction_id'    => $transaction_id
        ]);

        $result = $response->getBody();
        return json_decode($result);

    }

}
