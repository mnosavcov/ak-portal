<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectState extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order',
        'title',
        'description',
        'state',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
