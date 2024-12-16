<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Queue\SerializesModels;

class ProjectActualityAddedEvent
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var Project
     */
    public $project;
    public $projectActuality;

    /**
     * Create a new event instance.
     *
     * @param  Project  $project
     * @return void
     */
    public function __construct($project, $projectActuality)
    {
        $this->project = $project;
        $this->projectActuality = $projectActuality;
    }
}
