<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Config;
use DB;

class ConfigController extends Controller
{
  protected $model;
  protected $request;
  protected $fields;
  protected $datarequest;

  public function __construct(Config $config, Request $request)
  {
    $this->model                =  $config;
    $this->request              =  $request;

    $this->datarequest = [
      'titulo'               =>  'Configurações',
      'diretorio'            =>  'backend.configs',
      'url_action'           =>  'configs'
    ];
  }


  public function index()
  {
    try {

      $config = $this->model::find(1);
    } catch (\Exception $err) {
      return response()->json($err->getMessage(), 500);
    }

    return view($this->datarequest['diretorio'] . '.index', compact('config'))->with($this->datarequest);
  }


  public function create()
  {
    return view($this->datarequest['diretorio'] . '.form')->with($this->datarequest);
  }




  public function update()
  {
    $model = $this->model::where('id',1)->first();
    $result = $this->request->all();

    $rules = [
      //'name'     => "required",
      //'price' => 'required'
    ];

    $messages = [
      'name.required' => 'nome é obrigatório',
      'price.required' => 'preço é obrigatório'
    ];

    $validator = Validator::make($result, $rules, $messages);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    try {
      $model->update($result);
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json($e->getMessage(), 500);
    }

    return response()->json('Configurações salvas com sucesso', 200);
  }


}
