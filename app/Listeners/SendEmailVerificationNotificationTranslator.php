<?php

namespace App\Listeners;

use App\Events\RegisteredTranslatorEvent;
use App\Listeners\Notifications\VerifyEmailTranslator;


class SendEmailVerificationNotificationTranslator
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(RegisteredTranslatorEvent $event)
    {
        $event->user->notify(new VerifyEmailTranslator);
    }
}
