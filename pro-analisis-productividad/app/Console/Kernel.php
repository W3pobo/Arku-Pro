<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
/**
 * The Artisan commands provided by your application.
 *
 * @var array<int, class-string|string>
 */
protected $commands = [
    //
];
protected function schedule(Schedule $schedule)
{
    $schedule->command('notifications:productivity')->weekly()->fridays()->at('17:00');
    $schedule->command('analytics:daily')->dailyAt('23:59');
    $schedule->command('integrations:sync')->hourly();
}
}