<?php

namespace App\Listeners;

use App\Events\ProjectDocumentAddedEvent;
use App\Services\EmailService;

class ProjectDocumentAddedListener
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(ProjectDocumentAddedEvent $event)
    {
        (new EmailService())->investorProjectDocumentAdded($event);
    }
}
