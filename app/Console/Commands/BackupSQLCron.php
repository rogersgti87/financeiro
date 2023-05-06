<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class BackupSQLCron extends Command
{

  protected $signature = 'backupsql:cron';

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

        \Log::info('Limpando cache config');
        \Artisan::call('config:cache');
        Config::set('filesystems.disks.google.folderId',$result->google_drive_folder_sql);

        \Log::info($result->database);
        if(!empty(Storage::disk('backup')->files(trim($result->database)))){

            foreach(Storage::disk('backup')->files(trim($result->database)) as $file){

                $file_store = Storage::disk('backup')->get($file);
                Storage::disk('google')->put(basename($file),$file_store);

                \Log::info('backup sql enviado para o google drive - '.basename($file));

                \Log::info('verifica se existe o arquivo no google drive');
                \Log::info(Storage::disk('google')->exists(basename($file)));

                $size_google_drive = Storage::disk('google')->size(basename($file));
                \Log::info('Size google_drive: '.$size_google_drive);

                $size_local = Storage::disk('backup')->size($file);
                \Log::info('Size local: '.$size_local);


                while(true){
                    if($size_google_drive == $size_local){
                        \Log::info('Tamanho do arquivo igual.');
                        Storage::disk('backup')->delete($file);
                        \Log::info('backup sql deletado - '.basename($file));
                        break;
                    }else{
                        \Log::info('Tamanho do arquivo diferente.');
                    }
                }

            }

        }


        }

    \Log::info('terminou o envio para o drive');

  }

}
