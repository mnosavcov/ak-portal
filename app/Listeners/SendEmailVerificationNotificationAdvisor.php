<?php

namespace App\Listeners;

use App\Events\RegisteredAdvisorEvent;
use App\Listeners\Notifications\VerifyEmailAdvisor;


class SendEmailVerificationNotificationAdvisor
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(RegisteredAdvisorEvent $event)
    {
        $event->user->notify(new VerifyEmailAdvisor);
    }
}
