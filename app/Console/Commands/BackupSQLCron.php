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

    $filename = $result->database."-backup-" . Carbon::now()->format('YmdHis') . ".gz";
    $command = "mysqldump --user=" . $result->user ." --password=" . $result->password . " --host=" . $result->host . " " . $result->database . "  | gzip > " . storage_path() . "/app/backup/". $filename;

    $returnVar = NULL;
    $output  = NULL;

    exec($command, $output, $returnVar);
    \Log::info('terminou o backup sql - '.$filename);

    \Log::info('Limpando cache config');
    \Artisan::call('config:cache');
    Config::set('filesystems.disks.google.folderId',$result->google_drive_folder_sql);

    $file_store = Storage::disk('backup')->get($filename);

    Storage::disk('google')->put($filename,$file_store);

    \Log::info('backup sql enviado para o google drive - '.$filename);

    \Log::info('verifica se existe o arquivo no google drive');
    \Log::info(Storage::disk('google')->exists($filename));

    $size_google_drive = Storage::disk('google')->size($filename);
    \Log::info('Size google_drive: '.$size_google_drive);

    $size_local = Storage::disk('backup')->size($filename);
    \Log::info('Size local: '.$size_local);


    while(true){
        if($size_google_drive == $size_local){
            \Log::info('Tamanho do arquivo igual.');
            Storage::disk('backup')->delete($filename);
            \Log::info('backup sql deletado - '.$filename);
            break;
        }else{
            \Log::info('Tamanho do arquivo diferente.');
        }
    }


    }

    \Log::info('terminou o backup');

  }

}
