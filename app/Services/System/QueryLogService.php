<?php

namespace App\Services\System;

class QueryLogService
{
    public function enable()
    {
        app()->singleton('QueryLogServiceProvider.enabled', function () {
            return true;
        });
    }

    public function disable()
    {
        app()->singleton('QueryLogServiceProvider.enabled', function () {
            return false;
        });
    }

    public function isEnabled()
    {
        return app()->make('QueryLogServiceProvider.enabled');
    }
}
