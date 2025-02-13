<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use App\Services\BackupService;
use App\Services\CountryServices;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPassword;

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
        'email_2',
        'password',
        'advisor',
        'investor',
        'investor_status',
        'show_investor_status',
        'investor_status_email_notification',
        'advertiser',
        'advertiser_status',
        'show_advertiser_status',
        'advertiser_status_email_notification',
        'real_estate_broker',
        'real_estate_broker_status',
        'show_real_estate_broker_status',
        'real_estate_broker_status_email_notification',
        'check_status',
        'show_check_status',

        'title_before',
        'name',
        'surname',
        'title_after',
        'street',
        'street_number',
        'city',
        'psc',
        'country',
        'birthdate',
        'more_info_investor',
        'more_info_advertiser',
        'more_info_real_estate_broker',
        'phone_number',
        'phone_number_2',
        'notice',
        'investor_info',
        'ban_info',

        'user_verify_service_id',
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
        'deletable',
        'crypt',
        'birthdate_f',
        'country_f',
        'is_verify_finished_b'
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

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            (new BackupService)->backup2Table($model, true);
        });
    }

    public function notificationevents()
    {
        return $this->belongsTo(NotificationEvent::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function isSuperadmin(): bool
    {
        return (bool)$this->superadmin;
    }

    public function isOwner(): bool
    {
        return $this->owner;
    }

    public function isTranslator(): bool
    {
        return $this->translator || $this->superadmin || $this->owner;
    }

    public function isVerified()
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        return auth()->user()->check_status === 'verified' && auth()->user()->hasVerifiedEmail();
    }

    public function isVerifiedInvestor()
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        return auth()->user()->investor && auth()->user()->investor_status === 'verified' && auth()->user()->hasVerifiedEmail();
    }

    public function isVerifiedAdvertiser()
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        return auth()->user()->advertiser && auth()->user()->advertiser_status === 'verified' && auth()->user()->hasVerifiedEmail();
    }

    public function isVerifiedRealEstateBrokerStatus()
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        return auth()->user()->real_estate_broker && auth()->user()->real_estate_broker_status === 'verified' && auth()->user()->hasVerifiedEmail();
    }

    public function isDeniedInvestor()
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        return auth()->user()->investor_status === 'denied' && auth()->user()->hasVerifiedEmail();
    }

    public function isDeniedAdvertiser()
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        return auth()->user()->advertiser_status === 'denied' && auth()->user()->hasVerifiedEmail();
    }

    public function isDeniedRealEstateBrokerStatus()
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        return auth()->user()->real_estate_broker_status === 'denied' && auth()->user()->hasVerifiedEmail();
    }

    public function isInvestorVerified()
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        return auth()->user()->investor_status === 'verified';
    }

    public function isAdvertiserVerified()
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        return auth()->user()->advertiser_status === 'verified';
    }

    public function isRealEstateBrokerVerified()
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        return auth()->user()->real_estate_broker_status === 'verified';
    }

    public function userverifyservice()
    {
        return $this->belongsTo(UserVerifyService::class, 'user_verify_service_id', 'id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function deletable(): Attribute
    {
        $deletable = false;

        if (auth()->user()) {
            $deletable = !$this->projects()
                ->whereIn('status', Project::STATUS_NOT_DELETE_USER)
                ->count();

            if ($deletable) {
                $deletable = !ProjectShow::where('user_id', auth()->user()->id)
                    ->whereHas('project', function ($query) {
                        $query->whereIn('status', Project::STATUS_NOT_DELETE_USER)
                            ->where('offer', 1);
                    })
                    ->count();
            }
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $deletable
        );
    }

    public function crypt(): Attribute
    {
        $cryptName = sha1('user-crypt-' . $this->id);
        $cryptName = preg_replace('/\D/', '', $cryptName);;
        $cryptName = Str::take($cryptName, 6);
        $cryptName = Str::padLeft($cryptName, 6, '0');

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $cryptName
        );
    }

    public function birthdateF(): Attribute
    {
        if (empty($this->birthdate)) {
            return Attribute::make(
                get: fn(mixed $value, array $attributes) => ''
            );
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => Carbon::create($this->birthdate)->format('d.m.Y')
        );
    }

    public function countryF(): Attribute
    {
        if (empty($this->country)) {
            return Attribute::make(
                get: fn(mixed $value, array $attributes) => ''
            );
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => CountryServices::COUNTRIES[$this->country] ?? $this->country
        );
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    public function dataForVerify($newData = [])
    {
        $data = $this->toArray();

        if (empty($this->user_verify_service_id)) {
            $data['title_before'] = null;
            $data['name'] = null;
            $data['surname'] = null;
            $data['title_after'] = null;
            $data['birthdate'] = null;
            $data['birthdate_f'] = null;
            $data['street'] = null;
            $data['street_number'] = null;
            $data['city'] = null;
            $data['psc'] = null;
            $data['country'] = null;
            $data['country_f'] = null;
            $model = new UserVerifyService();
            $columns = Schema::getColumnListing($model->getTable());
            $emptyArray = array_fill_keys($columns, '');
            $data['userverifyservice'] = $emptyArray;
        } else {
            $data['userverifyservice'] = $this->userverifyservice->toArray();
        }

        if (!empty($newData)) {
            $data['title_before'] = $newData['title_before'] ?? null;
            $data['name'] = $newData['name'] ?? null;
            $data['surname'] = $newData['surname'] ?? null;
            $data['title_after'] = $newData['title_after'] ?? null;
            $data['birthdate'] = $newData['birthdate'] ?? null;
            $data['birthdate_f'] = $newData['birthdate_f'] ?? null;
            $data['street'] = $newData['street'] ?? null;
            $data['street_number'] = $newData['street_number'] ?? null;
            $data['city'] = $newData['city'] ?? null;
            $data['psc'] = $newData['psc'] ?? null;
            $data['country'] = $newData['country'] ?? null;
            $data['country_f'] = $newData['country_f'] ?? null;
            $data['user_verify_service_id'] = $newData['user_verify_service_id'] ?? null;
        }

        return $data;
    }

    public function isVerifyFinishedB(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $this->isVerifyFinished()
        );
    }

    public function isVerifyFinished()
    {
        if ($this->check_status === 'not_verified') {
            return false;
        }

        if ($this->investor && !mb_strlen(trim($this->more_info_investor))) {
            return false;
        }

        if ($this->advertiser && !mb_strlen(trim($this->more_info_advertiser))) {
            return false;
        }

        if ($this->real_estate_broker && !mb_strlen(trim($this->more_info_real_estate_broker))) {
            return false;
        }

        return true;
    }
}
