<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use DB;
use InvoiceNotification;

class Payabble extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supply_id',
        'description',
        'price',
        'status',
        'payment_method',
        'date_invoice',
        'date_end',
        'date_payment',
    ];

}
