<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Queue\SerializesModels;

class ProjectAuctionBidNewEvent
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var Project
     */
    public $project;
    public $projectAuctionOffer;

    /**
     * Create a new event instance.
     *
     * @param  Project  $project
     * @return void
     */
    public function __construct($project, $projectAuctionOffer)
    {
        $this->project = $project;
        $this->projectAuctionOffer = $projectAuctionOffer;
    }
}
