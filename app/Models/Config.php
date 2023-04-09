<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{


    protected $fillable = [
        'api_brasil_host',
        'api_brasil_bearer_token',
        'api_brasil_secret_key',
        'api_brasil_device_token',
        'api_brasil_public_token',
        'smtp_host',
        'smtp_port',
        'smtp_user',
        'smtp_password',
        'smtp_security',
        'sendpsulse_token',
        'sendpsulse_secret',
    ];


}
