<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{

    protected $fillable = [
        'category_id',
        'description',
        'price',
        'payment_method',
        'date_payable',
        'date_end',
        'period',
        'date_payment',
        'status'
    ];
}
