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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('weather:today')
        //     ->cron('00 06 * * 1-5'); // 6h00 from Monday to Friday

        $schedule->command('notify:prepare-lunch')
            ->cron('35 11 * * 1-5'); // 11h25 from Monday to Friday

        $schedule->command('notify:lunch')
            ->cron('40 11 * * 1-5'); // 11h35 from Monday to Friday

        $schedule->command('notify:cv-report')
            ->cron('00 17 * * 1-5'); // 17h00 from Monday to Friday
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
