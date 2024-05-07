<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $appends = [
        'common_img',
        'end_date_text',
    ];

    protected $fillable = [
        'user_account_type',
        'type',
        'status',
        'end_date',
        'title',
        'description',
        'price',
        'subject_offer',
        'location_offer',
        'country',
        'representation_type',
        'representation_end_date',
        'representation_indefinitely_date',
        'representation_may_be_cancelled',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function galleries()
    {
        return $this->hasMany(ProjectGallery::class);
    }

    public function shows()
    {
        return $this->hasMany(ProjectShow::class);
    }

    public function commonImg(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => null
        );
    }

    public function endDateText(): Attribute
    {
        $date = $this->end_date;
        if($date === null) {
            $dateText = 'bez termínu';
        } else {
            $currentDate = Carbon::now();
            $diff = $currentDate->diff($date);
            $dateText = $diff->format('%d dní %h hodiny');;
        }
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $dateText
        );
    }

    public function scopeIsPrepared(Builder $query): Builder
    {
        return $query->whereIn('status', ['prepared']);
    }

    public function scopeIsDrafted(Builder $query): Builder
    {
        return $query->whereIn('status', ['draft']);
    }

    public function scopeIsPublicated(Builder $query): Builder
    {
        return $query->whereIn('status', ['publicated']);
    }

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->whereNull('end_date')
            ->orWhereRaw('end_date >= CURRENT_DATE');
    }

    public function scopeIsNotActive(Builder $query): Builder
    {
        return $query->whereNotNull('end_date')
            ->whereRaw('end_date < CURRENT_DATE');
    }
}
