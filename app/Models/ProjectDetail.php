<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order',
        'head_title',
        'title',
        'description',
        'is_long',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
