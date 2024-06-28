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
        'offer',
        'winner',
        'principal_paid',
        'details_on_request',
        'details_on_request_time',
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
