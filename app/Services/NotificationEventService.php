<?php

namespace App\Services;

use App\Events\ProjectCreatedEvent;
use App\Events\ProjectFinishedEvent;
use App\Events\ProjectFixedPriceBidsEndEvent;
use App\Events\ProjectOfferThePriceBidsEndEvent;
use App\Events\ProjectPreliminaryInterestBidsEndEvent;
use App\Models\Project;

class NotificationEventService
{
    public function ProjectChange($original, $changes)
    {
        if (!empty($changes['status']) && $original['status'] !== $changes['status']) {
            $project = Project::find($original['id']);

            if ($project->status !== 'draft') {
                (new EmailService())->projectChangeStatusToAdmin($project);
            }

            if ($project->status === 'publicated') {
                event(new ProjectCreatedEvent($project));
            }

            if ($project->status === 'evaluation') {
                if ($project->type === 'offer-the-price') {
                    event(new ProjectOfferThePriceBidsEndEvent($project));
                }
                if ($project->type === 'fixed-price') {
                    event(new ProjectFixedPriceBidsEndEvent($project));
                }
            }

            if ($project->status === 'finished') {
                if ($project->type === 'preliminary-interest') {
                    event(new ProjectPreliminaryInterestBidsEndEvent($project));
                } else {
                    event(new ProjectFinishedEvent($project));
                }
            }
        }
    }
}
