<?php

namespace App\Listeners;

use App\Events\ProjectCreatedEvent;
use App\Services\EmailService;

class ProjectCreatedListener
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(ProjectCreatedEvent $event)
    {
        (new EmailService())->investorProjectCreated($event);
    }
}
