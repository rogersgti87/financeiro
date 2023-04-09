<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerServices extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'service_id',
        'dominio',
        'date_start',
        'date_end',
        'price',
        'period',
        'status',
    ];
}
