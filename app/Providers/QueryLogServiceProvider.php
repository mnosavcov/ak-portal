<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class QueryLogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('QueryLogServiceProvider.enabled', function () {
            return true;
        });
    }

    /**
     * Register services.
     */
    public function boot(): void
    {
        DB::listen(function ($query) {
            if (!$this->app->make('QueryLogServiceProvider.enabled')) {
                $this->app->singleton('QueryLogServiceProvider.enabled', function () {
                    return true;
                });
                return;
            }

            if (
                !str_contains(strtolower($query->sql), 'insert ')
                && !str_contains(strtolower($query->sql), 'update ')
                && !str_contains(strtolower($query->sql), 'delete ')
            ) {
                return;
            }

            $filename = 'logs/sql_' . Carbon::now()->format('Y-m-d') . '.log';
            Storage::append($filename, $query->sql . '|' . serialize($query->bindings));
        });
    }
}
