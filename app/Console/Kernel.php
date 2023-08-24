<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //commands\GenerateInvoiceCron::class,
        //commands\VerifyInvoiceStatusCron::class,
        commands\RememberInvoiceCron::class,
        //commands\BackupSQLCron::class,
        //commands\BackupFileCron::class,
        //commands\RemoveBackupSQLCron::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        //$schedule->command('generateinvoice:cron')->twiceDaily(9, 13);
        //$schedule->command('generateinvoicestatus:cron')->everyMinute();
        $schedule->command('rememberinvoice:cron')->dailyAt('8:40');
        //$schedule->command('backupsql:cron')->everyFiveMinutes();
        //$schedule->command('backupfile:cron')->twiceMonthly(1, 16, '3:00');
        //$schedule->command('removebackupsql:cron')->dailyAt('1:00');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
