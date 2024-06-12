<?php

namespace App\Listeners;

use App\Events\RegisteredAdmin;
use App\Listeners\Notifications\VerifyEmailAdmin;

class SendEmailVerificationNotificationAdmin
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(RegisteredAdmin $event)
    {
        $event->user->notify(new VerifyEmailAdmin);
    }
}
