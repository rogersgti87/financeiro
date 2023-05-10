<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class BackupFileCron extends Command
{

  protected $signature = 'backupfile:cron';

  protected $description = 'Backup SQL';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {

    foreach(DB::table('customer_backups')->where('status','Ativo')->get() as $result){

        if(!Storage::disk('backup')->exists(trim($result->database))) {

            Storage::disk('backup')->makeDirectory(trim($result->database), 0775, true);

        }

        \Artisan::call('config:cache');
        Config::set('filesystems.disks.google.folderId',$result->google_drive_folder_file);

        //\Log::info($result->database);
        if(!empty(Storage::disk('backup')->files(trim($result->database)))){

            foreach(Storage::disk('backup')->files(trim($result->database)) as $file){

                if(substr($file,-7) == '.tar.gz'){

                $file_store = Storage::disk('backup')->get($file);
                Storage::disk('google')->put(basename($file),$file_store);

                $size_google_drive = Storage::disk('google')->size(basename($file));

                $size_local = Storage::disk('backup')->size($file);

                while(true){
                    if($size_google_drive == $size_local){
                        Storage::disk('backup')->delete($file);
                        break;
                    }else{
                        //\Log::info('Tamanho do arquivo diferente.');
                    }
                }

                }

            }

        }


        }

    //\Log::info('terminou o envio para o drive');

  }

}
