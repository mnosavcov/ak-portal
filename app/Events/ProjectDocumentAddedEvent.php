<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Queue\SerializesModels;

class ProjectDocumentAddedEvent
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var Project
     */
    public $project;
    public $document;

    /**
     * Create a new event instance.
     *
     * @param  Project  $project
     * @return void
     */
    public function __construct($project, $document)
    {
        $this->project = $project;
        $this->document = $document;
    }
}
