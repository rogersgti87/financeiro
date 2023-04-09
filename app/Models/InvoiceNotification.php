<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use DB;

class InvoiceNotification extends Model
{


    protected $fillable = [
        'invoice_id',
        'type_send',
        'date',
        'status',
        'open',
        'click',
    ];



    public static function sendNotification($data){

        $config = DB::table('configs')->where('id',1)->first();

        $api_token  = $config->sendpulse_token;
        $api_key    = $config->sendpulse_secret;

        $response = Http::post(env('API_HOST_SEND_PULSE').'/oauth/access_token',[
            'grant_type'        => 'client_credentials',
            'client_id'         =>  $api_token,
            'client_secret'     =>  $api_key
          ]);

        $result = $response->getBody();

        $access_token = json_decode($result)->access_token;

        if($data['customer_email2'] != null){
            $emails = array(
                [
                    "customer"  => $data['customer'],
                    "email"     => $data['customer_email']
                ],
                [
                    "customer"  => $data['customer'],
                    "email"     => $data['customer_email2']
                ]
                );
        } else {
            $emails = [
                "customer"  => $data['customer'],
                "email"     => $data['customer_email']
            ];
        }

        $response = Http::withToken($access_token)->post(env('API_HOST_SEND_PULSE').'/smtp/emails',[
            "email" =>  [
                "subject"  => $data['title'],
                "html" => base64_encode($data['body']),
                "from" => [
                    "name"  => "Financeiro",
                    "email" => $config->smtp_user
                ],
                "to" => $emails
            ],
          ]);

        $result = $response->getBody();

        $senpulse_email_id = json_decode($result)->id;

        $whats_customer_name            = $data['customer'];
        $whats_invoice_id               = $data['invoice_id'];
        $whats_description              = $data['description_whatsapp'];
        $whats_data_fatura              = $data['data_fatura'];
        $whats_data_vencimento          = $data['data_vencimento'];
        $whats_price                    = $data['price'];
        $whats_payment_method           = $data['payment_method'];
        $whats_pix_emv                  = $data['pix_emv'];
        $whats_billet_digitable_line    = $data['billet_digitable_line'];
        $whats_billet_url_slip          = $data['billet_url_slip'];

        $data['text_whatsapp'] = "Olá $whats_customer_name, tudo bem?\n\n";
        $data['text_whatsapp'] .= "Esta é uma mensagem para notificá-lo(a) que foi gerado a *Fatura #$whats_invoice_id* \n\n";
        $data['text_whatsapp'] .= "*Serviço(s) Contratado(s):* \n\n";
        $data['text_whatsapp'] .= "$whats_description \n\n";
        $data['text_whatsapp'] .= "*Data da Fatura:* $whats_data_fatura \n";
        $data['text_whatsapp'] .= "*Vencimento:* $whats_data_vencimento \n";
        $data['text_whatsapp'] .= "*Total:* R$ $whats_price \n\n";
        $data['text_whatsapp'] .= "*Forma de pagamento:* $whats_payment_method \n\n";



        if($whats_payment_method == 'Pix'){
            $data['text_whatsapp'] .= "Código digitavel pix \n\n";
        }else{
            $data['text_whatsapp'] .= "Para abrir o Boleto é só clicar no link abaixo\n";
            $data['text_whatsapp'] .= "$whats_billet_url_slip\n\n";
            $data['text_whatsapp'] .= "Código digitavel do boleto \n\n";
        }

        if($data['customer_phone'] != null){

        $response = Http::withHeaders([
            "Content-Type"  => "application/json",
            "SecretKey"     =>  $config->api_brasil_secret_key,
            "PublicToken"   =>  $config->api_brasil_public_token,
            "DeviceToken"   =>  $config->api_brasil_device_token
        ])->withToken($config->api_brasil_bearer_token)
        ->post($config->api_brasil_host.'/whatsapp/sendText',[
            "number" => '55'.$data['customer_phone'],
            "text"   => $data['text_whatsapp']
        ]);

        $result = $response->getBody();

        $data['text_whatsapp_payment'] = '';

        if($whats_payment_method == 'Pix'){
            $data['text_whatsapp_payment'] .= "$whats_pix_emv\n\n";
        }else{
            $data['text_whatsapp_payment'] .= "$whats_billet_digitable_line\n\n";
        }


            $response = Http::withHeaders([
                "Content-Type"  => "application/json",
                "SecretKey"     =>  $config->api_brasil_secret_key,
                "PublicToken"   =>  $config->api_brasil_public_token,
                "DeviceToken"   =>  $config->api_brasil_device_token
            ])->withToken($config->api_brasil_bearer_token)
            ->post($config->api_brasil_host.'/whatsapp/sendText',[
                "number" => '55'.$data['customer_phone'],
                "text"   => $data['text_whatsapp_payment']
            ]);

            $result = $response->getBody();
        }


        DB::table('invoice_notifications')->insert([
            'invoice_id'        => $data['invoice_id'],
            'type_send'         => 'email',
            'date'              => Carbon::now(),
            'senpulse_email_id' => $senpulse_email_id,
            'status'            => null,
            'open'              => null,
            'click'             => null,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        // $response = Http::withToken($access_token)->get(env('API_HOST_SEND_PULSE').'/smtp/emails/rst2dv-0hsnll-91');
        // $result = $response->getBody();

        // $result = json_decode($result);



    }




}
