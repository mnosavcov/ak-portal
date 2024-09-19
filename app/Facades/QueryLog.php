<?php

namespace App\Facades;

use App\Services\System\QueryLogService;
use Illuminate\Support\Facades\Facade;

class QueryLog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return QueryLogService::class;
    }
}
