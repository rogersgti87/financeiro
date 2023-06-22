<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Payable;
use App\Models\Category;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;
use App\Models\Invoice;


class PayableController extends Controller
{
  protected $model;
  protected $request;
  protected $fields;
  protected $datarequest;

  public function __construct(Payable $payable, Request $request)
  {
    $this->model                =  $payable;
    $this->request              =  $request;

    $this->datarequest = [
      'titulo'               =>  'Contas a Pagar',
      'diretorio'            =>  'backend.payables',
      'url_action'           =>  'payables',
      'filter'               =>  'payables?filter',
    ];
  }

  public function index()
  {

        $column    = $this->request->input('column');
        $order     = $this->request->input('order') == 'desc' ? 'asc' : 'desc';

        if($column){
            $column = $this->request->input('column');
            $column_name = "$column $order";
        } else {
            $column_name = "id desc";
        }

        $data_ini  = $this->request->input('filter_data_ini') ? Carbon::createFromFormat('d/m/Y', $this->request->input('filter_data_ini'))->format('Y-m-d') : Carbon::now()->startOfMonth()->format('Y-m-d');
        $data_fim  = $this->request->input('filter_data_fim') ? Carbon::createFromFormat('d/m/Y', $this->request->input('filter_data_fim'))->format('Y-m-d') : Carbon::now()->lastOfMonth()->format('Y-m-d');

        if($this->request->input('filter_status')){
            $status = $this->request->input('filter_status') == 'all' ? '' : " and status = '".$this->request->input('filter_status')."'";
        }else{
            $status = "";
        }


        $data = $this->model->orderByRaw("$column_name")
                    ->select('id','category_id','description','price','date_payable','date_end','date_payment','status',
                    DB::raw("
                    (select sum(price) from payables where date_end between '$data_ini' and '$data_fim' $status) as total,
                    (select sum(price) from payables where date_end between '$data_ini' and '$data_fim' and status = 'Nao pago') as total_pagar,
                    (select sum(price) from payables where date_end between '$data_ini' and '$data_fim' and status = 'Pago') as total_pago,
                    (select sum(price) from payables where date_end between '$data_ini' and '$data_fim' and status = 'Cancelado') as total_cancelado
                    "))
                    ->whereraw("date_end between '$data_ini' and '$data_fim' $status")
                    ->paginate(15);


        return view($this->datarequest['diretorio'].'.index',compact('column','order','data'))->with($this->datarequest);

  }


  public function create()
  {
    $categories = Category::get();
    return view($this->datarequest['diretorio'] . '.form',compact('categories'))->with($this->datarequest);
  }


  public function store()
  {
    $model = new $this->model;
    $result = $this->request->all();

    $rules = [
      'description'     => "required",
      'price'           => 'required'
    ];

    $messages = [
      'descritpion.required' => 'Descrição é obrigatório',
      'price.required' => 'preço é obrigatório'
    ];

    $validator = Validator::make($result, $rules, $messages);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    $model->category_id     = $result['category_id'];
    $model->description     = $result['description'];
    $model->price           = $result['price'];
    $model->payment_method  = $result['payment_method'];
    $model->date_payable    = Carbon::createFromFormat('d/m/Y',$result['date_payable'])->format('Y-m-d');
    $model->date_end        = Carbon::createFromFormat('d/m/Y',$result['date_end'])->format('Y-m-d');
    $model->date_payment    = $result['date_payment'] != null ? Carbon::createFromFormat('d/m/Y',$result['date_payment'])->format('Y-m-d') : null;
    $model->period          = $result['period'];
    $model->status          = $result['status'];
    $model->created_at      = Carbon::now();
    $model->updated_at      = Carbon::now();

    try {
      $model->save();
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json($e->getMessage(), 500);
    }

    return response()->json('Registro salvo com sucesso', 200);
  }


  public function show($id)
  {
    $result     = $this->model::find($id);
    $categories = Category::get();
    return view($this->datarequest['diretorio'] . '.details', compact('result', 'categories'))->with($this->datarequest);
  }


  public function edit($id)
  {
    $result = $this->model::where('id', $id)->first();
    $categories = Category::get();
    return view($this->datarequest['diretorio'] . '.form', compact('result','categories'))->with($this->datarequest);
  }


  public function update($id)
  {
    $model = $this->model::find($id);
    $result = $this->request->all();

    $rules = [
        'description'     => "required",
        'price'           => 'required'
      ];

      $messages = [
        'descritpion.required' => 'Descrição é obrigatório',
        'price.required' => 'preço é obrigatório'
      ];

    $validator = Validator::make($result, $rules, $messages);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    $model->category_id     = $result['category_id'];
    $model->description     = $result['description'];
    $model->price           = $result['price'];
    $model->payment_method  = $result['payment_method'];
    $model->date_payable    = Carbon::createFromFormat('d/m/Y',$result['date_payable'])->format('Y-m-d');
    $model->date_end        = Carbon::createFromFormat('d/m/Y',$result['date_end'])->format('Y-m-d');
    $model->date_payment    = $result['date_payment'] != null ? Carbon::createFromFormat('d/m/Y',$result['date_payment'])->format('Y-m-d') : null;
    $model->period          = $result['period'];
    $model->status          = $result['status'];
    $model->updated_at      = Carbon::now();

    try {
      $model->save();
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json($e->getMessage(), 500);
    }

    return response()->json('Registro salvo com sucesso', 200);
  }


  public function destroy()
  {
    $model = new $this->model;
    $data = $this->request->all();

    if (!isset($data['selected'])) {
      return response()->json('Selecione ao menos um registro', 422);
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
