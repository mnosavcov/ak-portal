<?php

namespace App\Listeners;

use App\Events\ProjectFinishedEvent;
use App\Services\EmailService;

class ProjectFinishedListener
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(ProjectFinishedEvent $event)
    {
        (new EmailService())->investorProjectFinished($event);
    }
}
