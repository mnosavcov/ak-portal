<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Queue\SerializesModels;

class ProjectCommentAddedEvent
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var Project
     */
    public $project;
    public $projectQuestion;

    /**
     * Create a new event instance.
     *
     * @param  Project  $project
     * @return void
     */
    public function __construct($project, $projectQuestion)
    {
        $this->project = $project;
        $this->projectQuestion = $projectQuestion;
    }
}
