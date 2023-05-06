<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{

    protected $table = 'customer_backups';

    protected $fillable = [
        'name',
        'google_drive_folder_sql',
        'google_drive_folder_file',
        'folder_path',
        'price_anual',
        'database',
        'host',
        'user',
        'password',
        'port',
        'status'
    ];
}
