<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailNotification extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'notify',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_id', function ($builder) {
            $builder->where('user_id', auth()->id());
        });

        static::creating(function ($model) {
            if(empty($model->user_id)) {
                $model->user_id = auth()->id();
            }
        });
    }
}
