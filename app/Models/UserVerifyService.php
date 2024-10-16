<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerifyService extends Model
{
    use HasFactory;

    protected $fillable = [
        'verify_service',
        'verify_service_user_id',
        'data',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }
}
