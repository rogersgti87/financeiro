<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Service;
use DB;

class ServicesActivesController extends Controller
{
    protected $model;
    protected $request;
    protected $fields;
    protected $datarequest;

    public function __construct(Service $service, Request $request)
    {
        $this->model                =  $service;
        $this->request              =  $request;

        $this->datarequest = [
            'titulo'               =>  'Todos Serviços',
            'diretorio'            =>  'backend.customers',
            'url_action'               =>  'customers'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $order = $this->request->input('order') == 'asc' ? 'desc' : 'asc';
            $column_name = null;

            if ($this->request->input('column')) {
                $column = $this->request->input('column');
                $column_name = "$column $order";
            } else {
                $column_name = "id asc";
            }

            $field = $this->request->input('field') ? $this->request->input('field') : 'c.name';
            $operador = $this->request->input('operador') ? $this->request->input('operador') : 'like';
            $value = $this->request->input('value') ? $this->request->input('value') : '';

            if ($field == 'data' || $field == 'dataini' || $field == 'datafim') {
                $value = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            }

            if ($operador == 'like') {
                $newValue = "'%$value%'";
            } else {
                $newValue = "'$value'";
            }



            $results = DB::table('customer_services as a')
                ->select(
                    'a.id as id',
                    'a.customer_id',
                    'a.service_id',
                    'a.dominio',
                    'a.date_start',
                    'a.date_end',
                    'a.price',
                    'a.period',
                    'a.status',
                    'a.created_at',
                    'a.updated_at',
                    'b.name as nameService',
                    'c.id as idCustomer',
                    'c.name as nameCustomer',
                    'c.company',
                    'c.email'
                )
                ->join('services as b', 'a.service_id', 'b.id')
                ->join('customers as c', 'a.customer_id', 'c.id')
                // ->where('a.status', 'ativo')
                ->where('a.status', 'ativo')
                // ->orderBy('a.date_start')
                ->orderByRaw("$column_name")
                ->whereraw("$field $operador $newValue")
                ->paginate(30);

            // dd($results);
        } catch (\Exception $err) {
            return response()->json($err->getMessage(), 500);
        }

        return view($this->datarequest['diretorio'] . '.allservices', compact('results', 'order'))->with($this->datarequest);
    }
}
