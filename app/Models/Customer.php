<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{


    protected $fillable = [
        'name',
        'company',
        'email',
        'status',
        'cep',
        'address',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'phone',
        'payment_method',
    ];


}
