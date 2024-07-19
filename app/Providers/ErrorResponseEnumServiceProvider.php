<?php

namespace App\Providers;

use App\Enums\ConfigurationEnum;
use App\Enums\ErrorResponseEnum;
use Illuminate\Support\ServiceProvider;

class ErrorResponseEnumServiceProvider extends ServiceProvider
{   /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        ErrorResponseEnum::initialize();
        ConfigurationEnum::initialize();
        //
    }
}
