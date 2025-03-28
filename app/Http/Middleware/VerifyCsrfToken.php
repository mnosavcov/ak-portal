<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [];

    public function __construct(Application $app, Encrypter $encrypter)
    {
        $this->except[] = route('auth.ext.bankid.notify');
        $this->except[] = route('auth.ext.bankid.localhost.notify.set');
        $this->except[] = route('auth.ext.rivaas.callback');

        parent::__construct($app, $encrypter);
    }
}
