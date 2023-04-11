<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceNotification;

class VerifyInvoiceStatusCron extends Command
{

  protected $signature = 'generateinvoicestatus:cron';

  protected $description = 'Verificar status dos invoices';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {

   $verifyInvoices = Invoice::where('status','nao_pago')->where('transaction_id','<>',null)->get();

    foreach($verifyInvoices as $invoice){

        if($invoice->payment_method == 'Pix'){
           $status  = Invoice::verifyStatusPixPayment($invoice->transaction_id)->status_request->status;
        }else{
           $status = Invoice::verifyStatusBilletPayment($invoice->transaction_id)->status_request->status;
        }

        $date_payment = null;
        switch ($status) {
            case 'canceled':
                $status = "cancelado";
                break;
            case 'completed':
                $status = "pago";
                $date_payment = Carbon::now();
                break;
            case 'paid':
                $status = "pago";
                $date_payment = Carbon::now();
                break;
            case 'refunded':
                $status = "cancelado";
                break;
        }

            Invoice::where('id',$invoice->id)->update([
                'status'       =>   $status,
                'date_payment' =>   $date_payment,
                'updated_at'   =>   Carbon::now()
            ]);



    }


  }

}
