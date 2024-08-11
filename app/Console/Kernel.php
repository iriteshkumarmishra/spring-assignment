<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\DeclareWinnerJob;



class Kernel extends ConsoleKernel
{

    protected $commands = [
        \App\Console\Commands\ResetUserPoints::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            DeclareWinnerJob::dispatch();
        })->everyFiveMinutes();
    }
}