<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Customer;
use DB;

class CustomerController extends Controller
{
    protected $model;
    protected $request;
    protected $fields;
    protected $datarequest;

    public function __construct(Customer $customer, Request $request)
    {
        $this->model                =  $customer;
        $this->request              =  $request;

        $this->datarequest = [
            'titulo'               =>  'Clientes',
            'diretorio'            =>  'backend.customers',
            'url_action'           =>  'customers'
        ];
    }


    public function index()
    {
        try {
            $order = $this->request->input('order') == 'asc' ? 'desc' : 'asc';
            $column_name = null;

            if ($this->request->input('column')) {
                $column = $this->request->input('column');
                $column_name = "$column $order";
            } else {
                $column_name = "id desc";
            }

            $field = $this->request->input('field') ? $this->request->input('field') : 'name';
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


            $results = \DB::table("customers")
                ->select(
                    'customers.id as id',
                    'customers.name',
                    'customers.company',
                    'customers.email',
                    'customers.email2',
                    'customers.phone',
                    'customers.status as status',
                    'customers.created_at',
                    'customers.updated_at',
                    DB::raw("(select COUNT(customer_services.customer_id) from customer_services where customer_services.customer_id = customers.id) as qtaServices")
                )
                ->whereraw("$field $operador $newValue")
                ->paginate();
        } catch (\Exception $err) {
            return response()->json($err->getMessage(), 500);
        }

        return view($this->datarequest['diretorio'] . '.index', compact('results', 'order'))->with($this->datarequest);
    }


    public function create()
    {
        return view($this->datarequest['diretorio'] . '.form')->with($this->datarequest);
    }


    public function store()
    {
        $model = new $this->model;
        $result = $this->request->all();

        $rules = [
            'name'          => "required",
            'document'      => "required",
            'email'         => "required",
            'company'       => "required",
            // 'cep'           => "required",
            // 'address'       => "required",
            // 'number'        => "required",
            // 'city'          => "required",
            // 'state'         => "required",
            'phone'         => "required"
        ];

        $messages = [
            'name.required' => 'nome é obrigatório',
            'document.required' => 'cpf/cnpj é obrigatório',
            'email.required' => 'e-mail é obrigatório',
            'password.required' => 'senha é obrigatório',
            'password.min' => 'senha precisa ter 6 caracteres',
            'company.required' => 'empresa é obrigatório',
            'cep.required' => 'CEP é obrigatório',
            'address.required' => 'endereço é obrigatório',
            'number.required' => 'número é obrigatório',
            'city.required' => 'cidade é obrigatório',
            'state.required' => 'estado é obrigatório',
            'phone.required' => 'telefone é obrigatório'
        ];

        $validator = Validator::make($result, $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 422);
        }

        $model->name            = $result['name'];
        $model->company         = $result['company'];
        $model->document        = $result['document'];
        $model->email           = $result['email'];
        $model->email2          = $result['email2'];
        $model->status          = $result['status'];
        $model->cep             = $result['cep'];
        $model->address         = $result['address'];
        $model->number          = $result['number'];
        $model->complement      = $result['complement'];
        $model->city            = $result['city'];
        $model->state           = $result['state'];
        $model->phone           = removeEspeciais($result['phone']);
        $model->payment_method  = $result['payment_method'];
        $model->created_at      = Carbon::now();
        $model->updated_at      = Carbon::now();

        try {
            $model->save();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json($e->getMessage(), 500);
        }

        return response()->json('Cliente salvo com sucesso', 200);
    }


    public function show($id)
    {
        $result = $this->model::find($id);
        $allCustomers = $this->model::where('id', '!=', $id)->get();

        $myServices = DB::table('customer_services as a')
            ->select('a.id as id', 'a.customer_id', 'a.service_id', 'a.dominio', 'a.date_start', 'a.date_end', 'a.price', 'a.period', 'a.status', 'b.name as nameService')
            ->join('services as b', 'a.service_id', 'b.id')
            ->where('a.customer_id', $id)
            // ->paginate(30);
            ->get();

        $myServicesActives = DB::table('customer_services as a')
            ->select('a.id as id', 'a.customer_id', 'a.service_id', 'a.dominio', 'a.date_start', 'a.date_end', 'a.price', 'a.period', 'a.status', 'b.name as nameService')
            ->join('services as b', 'a.service_id', 'b.id')
            ->where('a.customer_id', $id)
            ->where('a.status', 'ativo')->count();

        $myServicesSuspended = DB::table('customer_services as a')
            ->select('a.id as id', 'a.customer_id', 'a.service_id', 'a.dominio', 'a.date_start', 'a.date_end', 'a.price', 'a.period', 'a.status', 'b.name as nameService')
            ->join('services as b', 'a.service_id', 'b.id')
            ->where('a.customer_id', $id)
            ->where('a.status', 'suspenso')->count();

        $myServicesCanceled = DB::table('customer_services as a')
            ->select('a.id as id', 'a.customer_id', 'a.service_id', 'a.dominio', 'a.date_start', 'a.date_end', 'a.price', 'a.period', 'a.status', 'b.name as nameService')
            ->join('services as b', 'a.service_id', 'b.id')
            ->where('a.customer_id', $id)
            ->where('a.status', 'cancelado')->count();


        $myInvoices = DB::table('invoices as i')
            ->select('i.id as id', 'cs.customer_id', 'i.description', 'i.price', 'i.payment_method', 'i.date_invoice', 'i.date_end', 'i.date_payment', 'i.status')
            ->join('customer_services as cs','i.customer_service_id','cs.id')
            ->where('cs.customer_id', $id)
            ->get();

        $myInvoicesPay = DB::table('invoices as i')
            ->select('i.id as id', 'cs.customer_id', 'i.description', 'i.price', 'i.payment_method', 'i.date_invoice', 'i.date_end', 'i.date_payment', 'i.status')
            ->join('customer_services as cs','i.customer_service_id','cs.id')
            ->where('cs.customer_id', $id)
            ->where('i.status', 'pago');

        $myInvoicesPayCount = $myInvoicesPay->count();
        $myInvoicesPayValue = $myInvoicesPay->get()->sum('price');


        $myInvoicesNotPay = DB::table('invoices as i')
            ->select('i.id as id', 'cs.customer_id', 'i.description', 'i.price', 'i.payment_method', 'i.date_invoice', 'i.date_end', 'i.date_payment', 'i.status')
            ->join('customer_services as cs','i.customer_service_id','cs.id')
            ->where('cs.customer_id', $id)
            ->where('i.status', 'nao_pago');

        $myInvoicesNotPayCount = $myInvoicesNotPay->count();
        $myInvoicesNotPayValue = $myInvoicesNotPay->get()->sum('price');


        $myInvoicesCanceled = DB::table('invoices as i')
            ->select('i.id as id', 'cs.customer_id', 'i.description', 'i.price', 'i.payment_method', 'i.date_invoice', 'i.date_end', 'i.date_payment', 'i.status')
            ->join('customer_services as cs','i.customer_service_id','cs.id')
            ->where('cs.customer_id', $id)
            ->where('i.status', 'cancelado');

        $myInvoicesCanceledCount = $myInvoicesCanceled->count();
        $myInvoicesCanceledValue = $myInvoicesCanceled->get()->sum('price');


        return view($this->datarequest['diretorio'] . '.details', compact('result', 'allCustomers', 'myServices', 'myServicesActives', 'myServicesSuspended', 'myServicesCanceled', 'myInvoices', 'myInvoicesPayCount', 'myInvoicesPayValue', 'myInvoicesNotPayCount', 'myInvoicesNotPayValue', 'myInvoicesCanceledCount', 'myInvoicesCanceledValue'))->with($this->datarequest);
    }

    public function showServices($id)
    {
        $result = $this->model::find($id);
        $allCustomers = $this->model::where('id', '!=', $id)->get();

        $myServices = DB::table('customer_services as a')
            ->select('a.id as id', 'a.customer_id', 'a.service_id', 'a.dominio', 'a.date_start', 'a.date_end', 'a.price', 'a.period', 'a.status', 'b.name as nameService')
            ->join('services as b', 'a.service_id', 'b.id')
            ->where('a.customer_id', $id)
            // ->paginate(30);
            ->get();

        return view($this->datarequest['diretorio'] . '.services', compact('result', 'allCustomers', 'myServices'))->with($this->datarequest);
    }

    public function showInvoices($id)
    {

        $result = $this->model::find($id);
        $allCustomers = $this->model::where('id', '!=', $id)->get();

        $myInvoices = DB::table('invoices as i')
            ->select('i.id as id', 'cs.customer_id', 'i.description', 'i.price', 'i.payment_method', 'i.date_invoice', 'i.date_end', 'i.date_payment', 'i.status')
            ->join('customer_services as cs','i.customer_service_id','cs.id')
            ->orderBy('i.id', 'desc')
            ->where('cs.customer_id',$id)
            ->get();

        return view($this->datarequest['diretorio'] . '.invoices', compact('result', 'allCustomers', 'myInvoices'))->with($this->datarequest);
    }



    public function edit($id)
    {
        $result = $this->model::where('id', $id)->first();
        return view($this->datarequest['diretorio'] . '.form', compact('result'))->with($this->datarequest);
    }


    public function update($id)
    {
        $model = $this->model::find($id);
        $result = $this->request->all();

        $rules = [
            'name'          => "required",
            'document'      => "required",
            'email'         => "required",
            'company'       => "required",
            // 'cep'           => "required",
            // 'address'       => "required",
            // 'number'        => "required",
            // 'city'          => "required",
            // 'state'         => "required",
            'phone'         => "required"
        ];

        $messages = [
            'name.required' => 'nome é obrigatório',
            'document.required' => 'cpf/cnpj é obrigatório',
            'email.required' => 'e-mail é obrigatório',
            'company.required' => 'empresa é obrigatório',
            'cep.required' => 'CEP é obrigatório',
            'address.required' => 'endereço é obrigatório',
            'number.required' => 'número é obrigatório',
            'city.required' => 'cidade é obrigatório',
            'state.required' => 'estado é obrigatório',
            'phone.required' => 'telefone é obrigatório'
        ];

        $validator = Validator::make($result, $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 422);
        }

        $model->name            = $result['name'];
        $model->document        = $result['document'];
        $model->company         = $result['company'];
        $model->email           = $result['email'];
        $model->email2          = $result['email2'];
        if (isset($result['status'])) {
            $model->status      = $result['status'];
        } else {
            $model->status      = 'Pendente';
        }
        $model->cep             = $result['cep'];
        $model->address         = $result['address'];
        $model->number          = $result['number'];
        $model->complement      = $result['complement'];
        $model->city            = $result['city'];
        $model->state           = $result['state'];
        $model->phone           = removeEspeciais($result['phone']);
        $model->payment_method  = $result['payment_method'];

        try {
            $model->save();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json($e->getMessage(), 500);
        }

        return response()->json('Cliente salvo com sucesso', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $model = new $this->model;
        $data = $this->request->all();

        if (!isset($data['selected'])) {
            return response()->json('Selecione ao menos um cliente', 422);
        }

        try {
            foreach ($data['selected'] as $result) {
                $find = $model->find($result);
                $find->delete();
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json($e->getMessage(), 500);
        }

        return response()->json(true, 200);
    }
}
