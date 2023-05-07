<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class RemoveBackupSQLCron extends Command
{

  protected $signature = 'removebackupsql:cron';

  protected $description = 'Remove Backup SQL';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {

    foreach(DB::table('customer_backups')->where('status','Ativo')->get() as $result){

        foreach(Storage::disk('google')->listContents($result->google_drive_folder_sql) as $file){
            if(substr($file['name'],0,6) <= date('ymd', strtotime('-3 days'))){
                Storage::disk('google')->delete($file['dirname'].'/'.$file['basename']);
            }

        }


    }

  }

}
