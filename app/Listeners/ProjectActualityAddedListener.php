<?php

namespace App\Listeners;

use App\Events\ProjectActualityAddedEvent;
use App\Services\EmailService;

class ProjectActualityAddedListener
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(ProjectActualityAddedEvent $event)
    {
        (new EmailService())->investorProjectActualityAdded($event);
    }
}
