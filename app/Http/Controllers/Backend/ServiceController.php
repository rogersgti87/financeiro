<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Service;
use DB;

class ServiceController extends Controller
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
      'titulo'               =>  'Produtos/Serviços',
      'diretorio'            =>  'backend.services',
      'url_action'               =>  'services'
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

      $results = DB::table('services')
        ->select('id', 'name', 'description', 'price', 'price_trimestral', 'price_anual', 'period', 'status', 'created_at')
        ->orderByRaw("$column_name")
        ->whereraw("$field $operador $newValue")
        ->paginate(30);
    } catch (\Exception $err) {
      return response()->json($err->getMessage(), 500);
    }

    return view($this->datarequest['diretorio'] . '.index', compact('results', 'order'))->with($this->datarequest);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view($this->datarequest['diretorio'] . '.form')->with($this->datarequest);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store()
  {
    $model = new $this->model;
    $result = $this->request->all();

    $rules = [
      'name'     => "required",
      'price' => 'required'
    ];

    $messages = [
      'name.required' => 'nome é obrigatório',
      'price.required' => 'preço é obrigatório'
    ];

    $validator = Validator::make($result, $rules, $messages);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    $model->name = $result['name'];
    $model->description = $result['description'];
    $model->price = $result['price'];
    $model->price_trimestral = $result['price_trimestral'];
    $model->price_anual = $result['price_anual'];
    $model->period = $result['period'];
    $model->status = $result['status'];
    $model->created_at = Carbon::now();
    $model->updated_at = Carbon::now();

    try {
      $model->save();
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json($e->getMessage(), 500);
    }

    return response()->json('Produto/Serviço salvo com sucesso', 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Service  $service
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $result = $this->model::find($id);
    $allServices = $this->model::where('id', '!=', $id)->get();
    return view($this->datarequest['diretorio'] . '.details', compact('result', 'allServices'))->with($this->datarequest);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Service  $service
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $result = $this->model::where('id', $id)->first();
    return view($this->datarequest['diretorio'] . '.form', compact('result'))->with($this->datarequest);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Service  $service
   * @return \Illuminate\Http\Response
   */
  public function update($id)
  {
    $model = $this->model::find($id);
    $result = $this->request->all();

    $rules = [
      'name'     => "required",
      'price' => 'required'
    ];

    $messages = [
      'name.required' => 'nome é obrigatório',
      'price.required' => 'preço é obrigatório'
    ];

    $validator = Validator::make($result, $rules, $messages);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    if (isset($result['status'])) {
      $model->status = $result['status'];
    } else {
      $model->status = 'Pendente';
    }
    $model->name = $result['name'];
    $model->description = $result['description'];
    $model->price = $result['price'];
    $model->price_trimestral = $result['price_trimestral'];
    $model->price_anual = $result['price_anual'];
    $model->period = $result['period'];
    $model->updated_at = Carbon::now();

    try {
      $model->save();
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json($e->getMessage(), 500);
    }

    return response()->json('Produto/Serviço salvo com sucesso', 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Service  $service
   * @return \Illuminate\Http\Response
   */
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
