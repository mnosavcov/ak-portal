<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectShow extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'favourite',
        'winner',
        'principal_paid',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
