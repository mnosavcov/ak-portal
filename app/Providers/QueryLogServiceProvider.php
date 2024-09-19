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

            $sql = $query->sql;

            if (
                !str_contains(strtolower($sql), 'insert ')
                && !str_contains(strtolower($sql), 'update ')
                && !str_contains(strtolower($sql), 'delete ')
            ) {
                return;
            }

            $bindings = $query->bindings;

            foreach ($bindings as $binding) {
                $value = is_numeric($binding) ? $binding : "'" . addslashes($binding) . "'";
                $sql = preg_replace('/\?/', $value, $sql, 1);
            }

            $filename = 'logs/sql_' . Carbon::now()->format('Y-m-d') . '.log';
            Storage::append($filename, $sql);
        });
    }
}
