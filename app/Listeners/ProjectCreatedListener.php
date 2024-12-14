<?php

namespace App\Listeners;

use App\Events\RegisteredAdminEvent;

class ProjectCreatedListener
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(RegisteredAdminEvent $event)
    {
        //
    }
}
