<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::get();
        return response()->json($customers, 200);
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
