<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        /*
         |--------------------------------------------------------------
         | Rebuild fast search index from records table
         |--------------------------------------------------------------
         | This is a heavy command. Do not run it every minute.
         | Run it once per day at low-traffic time.
         |
         | withoutOverlapping() prevents a new run from starting
         | if the previous one is still running.
         */
        $schedule->command('records:build-search-index --chunk=1000')
            ->dailyAt('02:00')
            ->withoutOverlapping();

        /*
         |--------------------------------------------------------------
         | Optional: write scheduler output to a log file
         |--------------------------------------------------------------
         | Uncomment this version instead of the above if you want logs.
         */
        /*
        $schedule->command('records:build-search-index --chunk=1000')
            ->dailyAt('02:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/search-index-scheduler.log'));
        */
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}