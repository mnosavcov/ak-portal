<?php

namespace App\Providers;

use App\Services\System\QueryLogService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(QueryLogService::class, function ($app) {
            return new QueryLogService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if(env('LANG_DEBUG')) {
            $filename = resource_path('lang/.setting');
            if (File::isFile($filename)) {
                App::setLocale(File::get($filename));
            }
        }
    }
}
