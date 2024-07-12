<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class QueryLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        DB::listen(function ($query) {
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

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
