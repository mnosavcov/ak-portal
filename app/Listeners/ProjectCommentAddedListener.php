<?php

namespace App\Listeners;

use App\Events\ProjectCommentAddedEvent;
use App\Services\EmailService;

class ProjectCommentAddedListener
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(ProjectCommentAddedEvent $event)
    {
        (new EmailService())->investorProjectCommentAdded($event);
    }
}
