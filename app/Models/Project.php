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
        'status_text',
        'states_prepared',
        'details_prepared',
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

    public const STATUSES = [
        'draft' => [
            'title' => 'Rozpracováno',
            'description' => 'Stav ve kterém může zadavatel upravovat zadání (nemůže upravovat data, která jsou zadaná administrátorem))',
        ],
        'send' => [
            'title' => 'Odesláno ke zpracování',
            'description' => 'Zadavatel odeslal své zadání ke zpracování projektu',
        ],
        'prepared' => [
            'title' => 'Připraveno ke kontrole zadavatelem',
            'description' => 'Zpracované administrátorem a zaslané zadavateli ke schválení',
        ],
        'confirm' => [
            'title' => 'Potvrzeno zadavatelem',
            'description' => 'Zadavatel potvrdil správnost projektu',
        ],
        'reminder' => [
            'title' => 'Zadavatel má připomínky',
            'description' => 'Zadavatel má připomínky ke správnosti projektu',
        ],
        'publicated' => [
            'title' => 'Publikované (aktivní)',
            'description' => 'Projekt bude vypublikován a bude veřejně přístupný',
        ],
        'finished' => [
            'title' => 'Publikované (dokončené)',
            'description' => 'Projekt bude nastaven na ukončený, ale bude veřejně viditelný se stavem "Ukončení"',
        ],
    ];

    public const PAID_TYPES = [
        'fixed-price' => [
            'value' => 'fixed-price',
            'text' => 'Cenu stanovíte vy (prodávající)',
            'description' => 'V projektu nastavíte fixní cenu, kterou chcete za projekt obdržet. Jakmile ji některý z investorů nabídne, dochází k ukončení projektu.',
        ],
        'offer-the-price' => [
            'value' => 'offer-the-price',
            'text' => 'Cenu stanoví zájemce o projekt (investor)',
            'description' => 'Zájemci o projekt předkládají po vámi určenou dobu své nabídky, jejichž výše není veřejná. Po skončení sběru nabídek vyberete vítěze. Můžete nastavit minimální částku, za kterou jste ochotni projekt prodat.',
        ],
//        'auction' => [
//            'value' => 'auction',
//            'text' => 'Prodej formou aukce',
//            'description' => 'Nastavíte délku trvání aukce, vyvolávací částku a minimální příhoz. Zájemci spolu soutěží. Vítězem bude ten, kdo nabídne nejvíce.',
//        ],
    ];

    public const REPRESENTATION_OPTIONS = [
        'exclusive' => [
            'value' => 'exclusive',
            'text' => 'Výhradní zastoupení',
            'description' => 'Klienta zastupujete jen vy. Za zveřejnění projektu nic neplatíte. Platíte jen za úspěšné zprostředkování prodeje ve výši, na které se dohodneme před zveřejněním projektu.',
        ],
        'non-exclusive' => [
            'value' => 'non-exclusive',
            'text' => 'Nevýhradní zastoupení',
            'description' => 'Nemáte exkluzivní právo na zprostředkování prodeje projektu. Za zveřejnění projektu platíte dle našeho ceníku. Zaplatíte za úspěšné zprostředkování prodeje naším portále. Od této částky bude odečten poplatek za zveřejnění projektu.',
        ],
    ];

    public const STATUS_DRAFT = [
        'draft',
    ];

    public const STATUS_PREPARE = [
        'send',
        'prepared',
        'confirm',
        'reminder',
    ];

    public const STATUS_PUBLIC = [
        'publicated',
        'finished',
    ];

    public const STATUS_FINISHED = [
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

        if ($this->status === 'finished') {
            $dateText = 'dokončeno';
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $dateText
        );
    }

    public function priceText(): Attribute
    {
        $priceText = '';
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
        } elseif ($type === 'offer-the-price') {
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
        if (in_array($this->status, self::STATUS_PREPARE)) {
            $state = htmlspecialchars(trim($this->actual_state ?? '-- neuvedeno --'));
            if (empty(trim($state))) {
                $state = '-- neuvedeno --';
            }
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $state
        );
    }

    public function statusText(): Attribute
    {
        $state = '---';

        if ($this->status === 'publicated') {
            $state = '<span class="text-app-green">Aktivní</span>';
        } elseif ($this->status === 'publicated') {
            $state = '<span class="text-app-green">Ukončeno</span>';
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $state
        );
    }

    public function statesPrepared(): Attribute
    {
        $data = $this->states;
        $ret = $data;

        if (auth()->guest()) {
            $ret = [];
            foreach ($data as $item) {
                $description = html_entity_decode($item->description ?? '');
                $description = preg_split("/\R/", strip_tags($description));
                foreach ($description as $index => $itemX) {
                    $strLen = ceil((mb_strlen($itemX) / 2) * 1.5);
                    $description[$index] = '<p><span style="background-color: #EBE9E9; overflow: hidden">' . str_repeat(' &nbsp;', $strLen) . '</span></p>';
                }
                $item->description = implode("\n", $description);

                $ret[] = (object)$item->toArray();
            }
            $ret = collect($ret);
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $ret
        );
    }

    public function detailsPrepared(): Attribute
    {
        $data = $this->details;
        $ret = $data;

        if (auth()->guest()) {
            $ret = [];
            foreach ($data as $item) {
                $description = html_entity_decode($item->description ?? '');
                $description = preg_split("/\R/", strip_tags($description));
                foreach ($description as $index => $itemX) {
                    $strLen = ceil((mb_strlen($itemX) / 2) * 1.5);
                    $description[$index] = '<p><span style="background-color: #EBE9E9; overflow: hidden">' . str_repeat(' &nbsp;', $strLen) . '</span></p>';
                }
                $item->description = implode("\n", $description);

                $ret[] = (object)$item->toArray();
            }
            $ret = collect($ret);
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $ret
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
        return $query->WhereRaw('
            (end_date is null
            or end_date >= CURRENT_DATE)
            and status = ?
            ', 'publicated');
    }

    public function scopeIsNotActive(Builder $query): Builder
    {
        return $query->WhereRaw('
            (end_date is not null
            and end_date < CURRENT_DATE)
            or status = ?
            ', 'finished');
    }

    public function scopeForDetail(Builder $query): Builder
    {
        return $query->with(['tags', 'shows']);
    }
}
