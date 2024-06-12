<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'investor',
        'advertiser',
        'advisor',
        'real_estate_broker',
        'check_status',

        'title_before',
        'name',
        'surname',
        'title_after',
        'street',
        'street_number',
        'city',
        'psc',
        'country',
        'more_info',
        'phone_number',
        'notice',
        'investor_info',
        'ban_info',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'deletable'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isSuperadmin(): bool
    {
        return (bool)$this->superadmin;
    }

    public function isOwner(): bool
    {
        return $this->owner;
    }

    public function isVerified()
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        return auth()->user()->check_status === 'verified' && auth()->user()->hasVerifiedEmail();
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function deletable(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => !$this->projects()->whereIn('status', Project::STATUS_NOT_DELETE_USER)->count()
        );
    }
}
