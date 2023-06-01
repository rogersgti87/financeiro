<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{

    public function index(Request $request)
    {

        $customers          = DB::table('customers')->where('status', 'ativo')->count();
        $getTotalMoneyMonth = DB::table('customer_services')->where('status', 'ativo')->where('period', 'mensal')->sum('price');
        $getInvoicesDues    = DB::table('invoices')->where('status', 'nao_pago')->count();


        return response()->json(['customers' => $customers, 'total_money_month' => number_format($getTotalMoneyMonth,2,',','.'), 'invoices_dues' => $getInvoicesDues], 200);
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
       //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
