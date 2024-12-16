<?php

namespace App\Listeners;

use App\Events\ProjectFixedPriceBidsEndEvent;
use App\Services\EmailService;

class ProjectFixedPriceBidsEndListener
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(ProjectFixedPriceBidsEndEvent $event)
    {
        (new EmailService())->investorProjectFixedPriceBidsEndEvent($event);
    }
}
