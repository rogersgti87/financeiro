<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
use App\Models\InvoiceNotification;


class GenerateInvoiceCron extends Command
{

  protected $signature = 'generateinvoice:cron';

  protected $description = 'Gerar Faturas a vencer';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {


    $sql = "SELECT a.id, c.name customer,c.email,c.phone, c.company, CONCAT(s.name, ' - ',a.dominio,' (de: ',DATE_FORMAT(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-',DAY(a.date_end)), '%d/%m/%Y'),' até ',
    DATE_FORMAT(DATE_ADD(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-',DAY(a.date_end)), INTERVAL 1 MONTH),'%d/%m/%Y'), ') - R$ ',a.price) description,
    CONCAT(s.name, ' - ',a.dominio) description_whatsapp,
    a.dominio,a.price,c.payment_method,a.period, CURRENT_DATE date_invoice,
    CASE
         WHEN a.period = 'mensal'     THEN DATE_ADD(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-',DAY(a.date_end)), INTERVAL 1 MONTH)
         WHEN a.period = 'trimestral' THEN DATE_ADD(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-',DAY(a.date_end)), INTERVAL 3 MONTH)
         WHEN a.period = 'anual'      THEN DATE_ADD(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-',DAY(a.date_end)), INTERVAL 12 MONTH)
    END AS date_due,
    a.status, 'nao_pago',CURRENT_TIMESTAMP created_at,CURRENT_TIMESTAMP updated_at FROM customer_services a
    INNER JOIN customers c ON a.customer_id = c.id
    INNER JOIN services s ON a.service_id = s.id
    WHERE NOT EXISTS (SELECT * FROM invoices b WHERE a.id = b.customer_service_id AND
    CASE
         WHEN a.period = 'mensal'     THEN DATE_ADD(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-',DAY(a.date_end)), INTERVAL 1 MONTH)
         WHEN a.period = 'trimestral' THEN DATE_ADD(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-',DAY(a.date_end)), INTERVAL 3 MONTH)
         WHEN a.period = 'anual'      THEN DATE_ADD(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-',DAY(a.date_end)), INTERVAL 12 MONTH)
    END  = b.date_end
    )
    AND a.date_end - CURRENT_DATE = 10 AND a.status = 'ATIVO'";

   $verifyInvoices = DB::select($sql);

//   return $verifyInvoices;

    foreach($verifyInvoices as $invoice){

        $newInvoice = DB::table('invoices')->insertGetId([
            'customer_service_id' => $invoice->id,
            'description' => $invoice->description,
            'price' => $invoice->price,
            'payment_method' => $invoice->payment_method,
            'date_invoice' => $invoice->date_invoice,
            'date_end' => $invoice->date_due,
            'date_payment' => null,
            'status' => 'nao_pago',
            'created_at' => $invoice->created_at,
            'updated_at' => $invoice->updated_at
        ]);


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

        $verifyTransaction = DB::table('invoices')->select('transaction_id')->where('id',$newInvoice)->first();

        if($invoice->payment_method == 'Pix'){
          $getInfoPixPayment      = Invoice::verifyStatusPixPayment($verifyTransaction->transaction_id);
        }else{
          $getInfoBilletPayment   = Invoice::verifyStatusBilletPayment($verifyTransaction->transaction_id);
        }


        $details = [
            'title'                     => 'Nova fatura gerada',
            'customer'                  => $invoice->customer,
            'customer_email'            => $invoice->email,
            'customer_phone'            => $invoice->phone,
            'company'                   => $invoice->company,
            'data_fatura'               => date('d/m/Y', strtotime($invoice->date_invoice)),
            'data_vencimento'           => date('d/m/Y', strtotime($invoice->date_due)),
            'proxima_data_vencimento'   => date('d/m/Y', strtotime($invoice->date_due)),
            'price'                     => number_format($invoice->price, 2),
            'payment_method'            => $invoice->payment_method,
            'description'               => $invoice->description,
            'description_whatsapp'      => $invoice->description_whatsapp,
            'invoice_id'                => $newInvoice,
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

            //Storage::disk('pdf_temp')->put($newInvoice.'.pdf', file_get_contents($details['billet_url_slip_pdf']));
            //$details['file_bill_pdf'] = Storage::path('pdf_temp/'.$newInvoice.'.pdf');
        }else{
            $details['pix_qrcode_image_url']  = $getInfoPixPayment->status_request->pix_code->qrcode_image_url;
            $details['pix_emv']             = $getInfoPixPayment->status_request->pix_code->emv;
        }


        $details['body']  = view('mails.newinvoice',$details)->render();

        InvoiceNotification::sendNotification($details);

        // dispara um unico e-mail com todo o conteúdo
        //\Mail::send('mails.newinvoice', $details, function($message)use($details,$invoice) {
           // $message->to($invoice->email)
             //       ->subject('Nova Fatura');

                    // if($invoice->payment_method != 'pix'){
                    //     $message->attach($details['file_bill_pdf']);
                    // }

        //});


    }



  }
}
