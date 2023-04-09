<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use App\Models\Report;

class ReportsController extends Controller
{
    protected $model;
    protected $request;
    protected $fields;
    protected $datarequest;

    public function __construct(Report $report, Request $request)
    {
        $this->model                =  $report;
        $this->request              =  $request;

        $this->datarequest = [
            'titulo'               =>  'Relatórios',
            'diretorio'            =>  'backend.reports',
            'url_action'               =>  'reports'
        ];
    }

    // Dashboard
    public function index()
    {
        $getTotalCustomers = $this->getTotalCustomers();
        $getTotalServices = $this->getTotalServices();
        $getTotalMoneyMonth = $this->getTotalMoneyMonth();
        $getTotalMoneyYear = $this->getTotalMoneyYear();
        $getInvoicesDues = $this->getInvoicesDues();


        return view($this->datarequest['diretorio'] . '.index', compact('getTotalCustomers', 'getTotalServices', 'getTotalMoneyMonth', 'getTotalMoneyYear', 'getInvoicesDues'))->with($this->datarequest);
    }

    public function getTotalCustomers(){
        $result = DB::table('customers')->where('status', 'ativo')->count();

        return $result;
    }
    public function getTotalServices(){
        $result = DB::table('customer_services')->where('status', 'ativo')->count();

        return $result;
    }
    public function getTotalMoneyMonth(){
        $result = DB::table('customer_services')->where('status', 'ativo')->where('period', 'mensal')->sum('price');

        return $result;
    }
    public function getTotalMoneyYear(){
        $result = DB::table('customer_services')->where('status', 'ativo')->where('period', 'anual')->sum('price');

        return $result;
    }
    public function getInvoicesDues(){
        $result = DB::table('invoices')->where('status', 'nao_pago')->count();

        return $result;
    }
}
