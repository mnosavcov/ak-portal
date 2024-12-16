<?php

namespace App\Listeners;

use App\Events\ProjectPreliminaryInterestBidsEndEvent;
use App\Services\EmailService;

class ProjectPreliminaryInterestBidsEndListener
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(ProjectPreliminaryInterestBidsEndEvent $event)
    {
        (new EmailService())->investorProjectPreliminaryInterestBidsEnd($event);
    }
}
