<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Mail;

class LogErrorListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageLogged $event)
    {
        if (!empty(env('MAIL_ERROR_TO_ADDRESS')) && $event->level === 'error') {
            Mail::raw(
                $event->message,
                function ($mail) {
                    $mail->to(env('MAIL_ERROR_TO_ADDRESS'))
                        ->subject('..::ERROR: ' . env('APP_NAME'));
                });
        }
    }
}
