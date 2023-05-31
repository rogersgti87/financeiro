<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerController extends Controller
{

    public function index(Request $request)
    {


        if($request->input('field') && $request->input('operator') && $request->input('value')){

        $field     = $request->input('field');
        $operator  = $request->input('operator');
        $value     = $request->input('value');

            if($field == 'created_at'){
                $field = 'CAST(created_at as DATE)';
                $value = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            }

            if($operator == 'like'){
                $newValue = "'%$value%'";
            }else{
                $newValue = "'$value'";
            }

            $customers = Customer::whereraw("$field $operator $newValue")->paginate(15);

        }else{
            $customers = Customer::paginate(15);
        }

        return response()->json($customers, 200);
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        $customer = Customer::find($id);
        return response()->json($customer, 200);
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
