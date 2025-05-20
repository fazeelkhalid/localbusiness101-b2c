<?php

namespace App\Providers;

use App\Http\Middleware\GuzzleRequestLoggerMiddleware;
use App\Http\Middleware\HttpLogger\ProcessorRequestResponseLogMiddleware;
use App\Http\Services\AcquirerService;
use App\Http\Services\UserCredService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->singleton(AcquirerService::class, function ($app) {
            return new AcquirerService();
        });

        $this->app->singleton(UserCredService::class, function ($app) {
            return new UserCredService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            $schedule->command('webhook:process-twilio')
                ->everyMinute()
                ->appendOutputTo(storage_path('logs/webhook-scheduler.log'));

            $schedule->command('twilio:fetch-recordings')
                ->everyMinute()
                ->appendOutputTo(storage_path('logs/twilio_recording_scheduler.log'));
        });
    }
}
