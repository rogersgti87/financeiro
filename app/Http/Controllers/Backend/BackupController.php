<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Backup;
use DB;

class BackupController extends Controller
{
  protected $model;
  protected $request;
  protected $fields;
  protected $datarequest;

  public function __construct(Backup $backup, Request $request)
  {
    $this->model                =  $backup;
    $this->request              =  $request;

    $this->datarequest = [
      'titulo'               =>  'Backup',
      'diretorio'            =>  'backend.backups',
      'url_action'           =>  'backups'
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

      $results = Backup::orderByRaw("$column_name")
        ->whereraw("$field $operador $newValue")
        ->paginate(30);
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
      'name'     => "required",
    ];

    $messages = [
      'name.required' => 'nome é obrigatório',
      'price.required' => 'preço é obrigatório'
    ];

    $validator = Validator::make($result, $rules, $messages);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    $model->name                        = $result['name'];
    $model->google_drive_folder_sql     = $result['google_drive_folder_sql'];
    $model->google_drive_folder_file    = $result['google_drive_folder_file'];
    $model->folder_path                 = $result['folder_path'];
    $model->database                    = $result['database'];
    $model->host                        = $result['host'];
    $model->user                        = $result['user'];
    $model->password                    = $result['password'];
    $model->port                        = $result['port'];
    $model->status                      = $result['status'];
    $model->created_at = Carbon::now();
    $model->updated_at = Carbon::now();

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
    $result = $this->model::find($id);
    $allServices = $this->model::where('id', '!=', $id)->get();
    return view($this->datarequest['diretorio'] . '.details', compact('result', 'allServices'))->with($this->datarequest);
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
      'name'     => "required",
    ];

    $messages = [
      'name.required' => 'nome é obrigatório',
      'price.required' => 'preço é obrigatório'
    ];

    $validator = Validator::make($result, $rules, $messages);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    $model->name                        = $result['name'];
    $model->google_drive_folder_sql     = $result['google_drive_folder_sql'];
    $model->google_drive_folder_file    = $result['google_drive_folder_file'];
    $model->folder_path                 = $result['folder_path'];
    $model->database                    = $result['database'];
    $model->host                        = $result['host'];
    $model->user                        = $result['user'];
    $model->password                    = $result['password'];
    $model->port                        = $result['port'];
    $model->status                      = $result['status'];
    $model->updated_at = Carbon::now();

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
