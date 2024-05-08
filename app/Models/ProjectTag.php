<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTag extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
