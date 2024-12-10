<?php

namespace App\Listeners;

use App\Events\RegisteredTranslator;
use App\Listeners\Notifications\VerifyEmailTranslator;


class SendEmailVerificationNotificationTranslator
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(RegisteredTranslator $event)
    {
        $event->user->notify(new VerifyEmailTranslator);
    }
}
