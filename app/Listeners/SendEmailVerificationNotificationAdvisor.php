<?php

namespace App\Listeners;

use App\Events\RegisteredAdvisor;
use App\Listeners\Notifications\VerifyEmailAdvisor;


class SendEmailVerificationNotificationAdvisor
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(RegisteredAdvisor $event)
    {
        $event->user->notify(new VerifyEmailAdvisor);
    }
}
