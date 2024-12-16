<?php

namespace App\Listeners;

use App\Events\ProjectOfferThePriceBidsEndEvent;
use App\Services\EmailService;

class ProjectOfferThePriceBidsEndListener
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(ProjectOfferThePriceBidsEndEvent $event)
    {
        (new EmailService())->investorProjectOfferThePriceBidsEnd($event);
    }
}
