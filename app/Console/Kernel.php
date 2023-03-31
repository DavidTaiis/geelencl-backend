<?php

namespace App\Console;

use App\Processes\StravaCustomerIntegrationProcess;
use App\Processes\TrakingProcess;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * @var TrakingProcess
     */
    private $process;

    /**
     * @var StravaCustomerIntegrationProcess
     */
    private $stravaProcess;


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
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Log::info('execute cron sync empty..............');
            $processCron = App::make('App\Processes\EventProcess');
            $processCron->finishEvents();
        })->hourly()->between('8:00', '23:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
