<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectGallery extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'filepath',
        'filename',
        'order',
        'public',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
