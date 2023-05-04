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
        commands\GenerateInvoiceCron::class,
        //commands\VerifyInvoiceStatusCron::class,
        commands\RememberInvoiceCron::class,
        commands\BackupSQLCron::class,
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

        $schedule->command('generateinvoice:cron')->everyMinute();
        //$schedule->command('generateinvoicestatus:cron')->everyMinute();
        $schedule->command('rememberinvoice:cron')->everyMinute();
        $schedule->command('backupsql:cron')->hourly();

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
