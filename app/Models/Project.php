<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $appends = [
        'common_img',
        'end_date_text',
        'price_text',
        'url_part',
        'url_detail',
        'about_strip',
        'actual_state_text',
    ];

    protected $fillable = [
        'user_account_type',
        'type',
        'status',
        'end_date',
        'title',
        'description',
        'about',
        'actual_state',
        'user_reminder',
        'price',
        'minimum_principal',
        'subject_offer',
        'location_offer',
        'country',
        'representation_type',
        'representation_end_date',
        'representation_indefinitely_date',
        'representation_may_be_cancelled',
    ];

    public const STATUS_DRAFT = [
        'draft',
    ];

    public const STATUS_PREPARE = [
        'send',
        'prepared',
        'confirm',
        'reminders',
    ];

    public const STATUS_PUBLIC = [
        'publicated',
    ];

    public const STATUS_FINISHED = [
        'finished',
    ];

    public const STATUS_FOR_DETAIL = [
        'publicated',
        'finished',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    public function details()
    {
        return $this->hasMany(ProjectDetail::class)->orderBy('head_title')->orderBy('id');
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

    public function states()
    {
        return $this->hasMany(ProjectState::class);
    }

    public function tags()
    {
        return $this->hasMany(ProjectTag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commonImg(): Attribute
    {
        $headImg = $this->galleries()->where('head_img', true)->first()?->url;

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $headImg
        );
    }

    public function endDateText(): Attribute
    {
        $date = $this->end_date;
        if ($date === null) {
            $dateText = 'bez termínu';
        } else {
            $currentDate = Carbon::now();
            $diff = $currentDate->diff($date);
            $dateText = '';
            if (isset($diff->days) && $diff->days > 0) {
                if ($diff->days === 1) {
                    $dateText .= '1 den';
                } elseif ($diff->days > 1 && $diff->days < 5) {
                    $dateText .= $diff->days . ' dny';
                } else {
                    $dateText .= $diff->days . ' dní';
                }
            }
            if ($diff->h === 1) {
                $dateText .= ' 1 hodina';
            } elseif ($diff->h > 1 && $diff->h < 5) {
                $dateText .= ' ' . $diff->h . ' hodiny';
            } elseif ($diff->h > 4) {
                $dateText .= ' ' . $diff->h . ' hodin';
            }
        }
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $dateText
        );
    }

    public function priceText(): Attribute
    {
        $price = $this->price;
        $type = $this->type;
        if ($type === 'fixed-price') {
            if (auth()->guest()) {
                $priceText = 'Jen pro přihlášené ';
            } elseif (empty($price)) {
                $priceText = 'Cena není zadaná';
            } else {
                $priceText = number_format($price, 0, '.', ' ') . ' Kč';
            }
        } elseif ($type = 'offer-the-price') {
            $priceText = 'nabídněte';
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $priceText
        );
    }

    public function urlPart(): Attribute
    {
        $slugTitle = Str::slug($this->title);
        $id = $this->id;
        $url = sprintf('%d-%s', $id, $slugTitle);

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $url
        );
    }

    public function urlDetail(): Attribute
    {
        $slugTitle = Str::slug($this->title);
        $id = $this->id;
        $project = sprintf('%d-%s', $id, $slugTitle);
        $url = route('projects.show', ['project' => $project]);

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $url
        );
    }

    public function aboutStrip(): Attribute
    {
        $about = strip_tags($this->about);

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $about
        );
    }

    public function actualStateText(): Attribute
    {
        $state = null;
        if(in_array($this->status, self::STATUS_PREPARE)) {
            $state = htmlspecialchars(trim($this->actual_state ?? '-- neuvedeno --'));
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $state
        );
    }

    public function scopeIsDrafted(Builder $query): Builder
    {
        return $query->whereIn('status', self::STATUS_DRAFT);
    }

    public function scopeIsPrepared(Builder $query): Builder
    {
        return $query->whereIn('status', self::STATUS_PREPARE);
    }

    public function scopeIsPublicated(Builder $query): Builder
    {
        return $query->whereIn('status', self::STATUS_PUBLIC);
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

    public function scopeForDetail(Builder $query): Builder
    {
        return $query->with(['tags', 'shows']);
    }
}
