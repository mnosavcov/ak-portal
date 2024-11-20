<?php

namespace App\Console;

use App\Services\Admin\LocalizationService;
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
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        Artisan::command('localize-x {lang?} {--remove-missing} {--force}', function (LocalizationService $localizationService) {
            $localizationService->clearBkps();

            Artisan::call(
                'localize',
                [
                    'lang' => $this->arguments()['lang'],
                    '--remove-missing' => $this->options()['remove-missing'],
                    '--force' => $this->options()['force'],
                ]
            );

            $localizationService->setLocalizationLangs();
        });

        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
