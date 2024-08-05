<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('authentication-log:purge')
            ->daily()
            ->onSuccess(function () {
                Artisan::call('authentication-log:purge');
                $output = Artisan::output();
                
                \Log::info("[schedule command] => authentication-log:purge command output: \n$output");
            })
            ->onFailure(function () {
                \Log::error('schedule command => authentication-log:purge command failed');
            });
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
