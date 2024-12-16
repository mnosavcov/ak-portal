<?php

namespace App\Listeners;

use App\Events\ProjectAuctionBidNewEvent;
use App\Services\EmailService;

class ProjectAuctionBidNewListener
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(ProjectAuctionBidNewEvent $event)
    {
        (new EmailService())->investorProjectAuctionBidNew($event);
    }
}
