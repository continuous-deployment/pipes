<?php

namespace Pipes\Console;

use Pipes\Console\Commands\Setup;
use Pipes\Console\Commands\Register;
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
        Setup::class,
        Register::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule schedule object
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // TODO: add a default schedule to adding actions to a queue
        return $schedule;
    }
}
