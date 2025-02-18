<?php

namespace App\Services\Auth\Ext;

class FoundationExtVerifyService
{
    protected $isPossibleActualization = false;

    public function isPossibleActualization(): bool
    {
        return $this->isPossibleActualization;
    }

    public function getActualizationErrors(): array
    {
        return [];
    }
}
