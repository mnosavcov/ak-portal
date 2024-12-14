<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Queue\SerializesModels;

class ProjectCreatedEvent
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var Project
     */
    public $project;

    /**
     * Create a new event instance.
     *
     * @param  Project  $project
     * @return void
     */
    public function __construct($project)
    {
        $this->project = $project;
    }
}
