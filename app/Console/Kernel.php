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
        $schedule->command('sync:servers')->everyFifteenMinutes(); 
        $schedule->command('sync:sites')->everyFifteenMinutes();
        $schedule->command('sync:db')->everyFifteenMinutes(); 
        $schedule->command('sync:dbusers')->everyFifteenMinutes();
        $schedule->command('cashier:run')
    ->hourly() // run as often as you like (daily, monthly, every minute, ...)
    ->withoutOverlapping(); // make sure to include this

        
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
