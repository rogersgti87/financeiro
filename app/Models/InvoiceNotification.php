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
        'message_status',
        'message',
        'subject_whatsapp',
    ];



    public static function sendNotification($data){

        $config = DB::table('configs')->where('id',1)->first();

        $api_token  = $config->sendpulse_token;
        $api_key    = $config->sendpulse_secret;

        $response = Http::post('https://api.sendpulse.com/oauth/access_token',[
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
            $emails = array(
                [
                "customer"  => $data['customer'],
                "email"     => $data['customer_email']
            ]
        );
        }

        $response = Http::withToken($access_token)->post('https://api.sendpulse.com/smtp/emails',[
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


        DB::table('invoice_notifications')->insert([
            'invoice_id'        => $data['invoice_id'],
            'type_send'         => 'email',
            'date'              => Carbon::now(),
            'subject_whatsapp'  => '',
            'senpulse_email_id' => $senpulse_email_id,
            'status'            => null,
            'message_status'    => null,
            'message'           => null,
            'subject_whatsapp'  => null,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

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

        if(isset($data['text_remember']) && $data['text_remember'] != null){
            $whats_text_remember        = $data['text_remember'];
        }


        $data['text_whatsapp'] = "*MENSAGEM AUTOMÁTICA*\n\n";
        $data['text_whatsapp'] .= "Olá $whats_customer_name, tudo bem?\n\n";

        if(isset($data['text_remember']) && $data['text_remember'] != null){
            $data['text_whatsapp'] .= "$whats_text_remember *Fatura #$whats_invoice_id* \n\n";
        }else{
            $data['text_whatsapp'] .= "Esta é uma mensagem para notificá-lo(a) que foi gerado a *Fatura #$whats_invoice_id* \n\n";
        }
        $data['text_whatsapp'] .= "*Serviço(s) Contratado(s):* \n\n";
        $data['text_whatsapp'] .= "$whats_description \n\n";
        $data['text_whatsapp'] .= "*Data da Fatura:* $whats_data_fatura \n";
        $data['text_whatsapp'] .= "*Vencimento:* $whats_data_vencimento \n";
        $data['text_whatsapp'] .= "*Forma de pagamento:* $whats_payment_method \n";
        $data['text_whatsapp'] .= "*Total:* R$ $whats_price \n\n";



        if($whats_payment_method == 'Boleto'){
            $data['text_whatsapp'] .= "Para abrir o Boleto é só clicar no link abaixo\n";
            $data['text_whatsapp'] .= "$whats_billet_url_slip\n\n";
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

        $whats_status           = json_decode($result)->error;
        if($whats_status == false){
            $whats_message_status   = json_decode($result)->message;
            $whats_message          = json_encode(json_decode($result)->response);
        }else{
            $whats_message_status   = json_encode(json_decode($result)->message);
            $whats_message          = '';
        }


        DB::table('invoice_notifications')->insert([
            'invoice_id'        => $data['invoice_id'],
            'type_send'         => 'whatsapp',
            'date'              => Carbon::now(),
            'subject_whatsapp'  => $data['title'],
            'senpulse_email_id' => '',
            'status'            => $whats_status == true ? 'Error' : 'Success',
            'message_status'    => $whats_message_status,
            'message'           => $whats_message,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        $data['text_whatsapp_payment'] = '';

        if($whats_payment_method == 'Pix'){
            $data['text_whatsapp_payment'] .= "$whats_pix_emv\n\n";
        }else{
            $whats_billet_digitable_line = removeEspeciais($whats_billet_digitable_line);
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

            $whats_status           = json_decode($result)->error;
            if($whats_status == false){
                $whats_message_status   = json_decode($result)->message;
                $whats_message          = json_encode(json_decode($result)->response);
            }else{
                $whats_message_status   = json_encode(json_decode($result)->message);
                $whats_message          = '';
            }


            DB::table('invoice_notifications')->insert([
                'invoice_id'        => $data['invoice_id'],
                'type_send'         => 'whatsapp',
                'date'              => Carbon::now(),
                'subject_whatsapp'  => $data['title'],
                'senpulse_email_id' => '',
                'status'            => $whats_status == true ? 'Error' : 'Success',
                'message_status'    => $whats_message_status,
                'message'           => $whats_message,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);

        }




        // $response = Http::withToken($access_token)->get(env('API_HOST_SEND_PULSE').'/smtp/emails/rst2dv-0hsnll-91');
        // $result = $response->getBody();

        // $result = json_decode($result);



    }


    public static function sendNotificationConfirm($data){

        $config = DB::table('configs')->where('id',1)->first();

        $api_token  = $config->sendpulse_token;
        $api_key    = $config->sendpulse_secret;

        $response = Http::post('https://api.sendpulse.com/oauth/access_token',[
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
            $emails = array(
                [
                "customer"  => $data['customer'],
                "email"     => $data['customer_email']
            ]
        );
        }

        $response = Http::withToken($access_token)->post('https://api.sendpulse.com/smtp/emails',[
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

        DB::table('invoice_notifications')->insert([
            'invoice_id'        => $data['invoice_id'],
            'type_send'         => 'email',
            'date'              => Carbon::now(),
            'subject_whatsapp'  => '',
            'senpulse_email_id' => $senpulse_email_id,
            'status'            => null,
            'message_status'    => null,
            'message'           => null,
            'subject_whatsapp'  => null,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);


        $whats_customer_name            = $data['customer'];
        $whats_invoice_id               = $data['invoice_id'];
        $whats_description              = $data['description_whatsapp'];
        $whats_data_fatura              = $data['data_fatura'];
        $whats_data_vencimento          = $data['data_vencimento'];
        $whats_data_pagamento           = $data['data_pagamento'];
        $whats_price                    = $data['price'];
        $whats_payment_method           = $data['payment_method'];


        $data['text_whatsapp'] = "*MENSAGEM AUTOMÁTICA*\n\n";
        $data['text_whatsapp'] .= "Olá $whats_customer_name, tudo bem?\n\n";
        $data['text_whatsapp'] .= "Seu pagamento referente a *Fatura #$whats_invoice_id* foi confimado!\n\n";
        $data['text_whatsapp'] .= "*Serviço(s) Contratado(s):* \n\n";
        $data['text_whatsapp'] .= "$whats_description \n\n";
        $data['text_whatsapp'] .= "*Data da Fatura:* $whats_data_fatura \n";
        $data['text_whatsapp'] .= "*Vencimento:* $whats_data_vencimento \n";
        $data['text_whatsapp'] .= "*Data do Pagamento:* $whats_data_pagamento \n";
        $data['text_whatsapp'] .= "*Forma de pagamento:* $whats_payment_method \n";
        $data['text_whatsapp'] .= "*Total:* R$ $whats_price \n\n";


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

            $whats_status           = json_decode($result)->error;
            if($whats_status == false){
                $whats_message_status   = json_decode($result)->message;
                $whats_message          = json_encode(json_decode($result)->response);
            }else{
                $whats_message_status   = json_encode(json_decode($result)->message);
                $whats_message          = '';
            }

            DB::table('invoice_notifications')->insert([
                'invoice_id'        => $data['invoice_id'],
                'type_send'         => 'whatsapp',
                'date'              => Carbon::now(),
                'subject_whatsapp'  => $data['title'],
                'senpulse_email_id' => '',
                'status'            => $whats_status == true ? 'Error' : 'Success',
                'message_status'    => $whats_message_status,
                'message'           => $whats_message,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);


            }

    }



}
