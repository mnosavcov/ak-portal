<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $appends = [
        'common_img',
        'end_date_text',
        'end_date_text_normal',
        'end_date_text_long',
        'price_text',
        'price_text_offer',
        'price_text_auction',
        'actual_min_bid_amount',
        'actual_min_bid_amount_text',
        'minimum_principal_text',
        'url_part',
        'url_detail',
        'short_info_strip',
        'actual_state_text',
        'status_text',
        'states_prepared',
        'details_prepared',
        'about_prepared',
        'use_countdown_date_text_long',
        'zip_url',
        'min_bid_amount_text',
    ];

    protected $fillable = [
        'user_account_type',
        'type',
        'subcategory_id',
        'status',
        'end_date',
        'title',
        'description',
        'about',
        'short_info',
        'actual_state',
        'user_reminder',
        'price',
        'minimum_principal',
        'min_bid_amount',
        'subject_offer',
        'location_offer',
        'country',
        'representation_type',
        'representation_end_date',
        'representation_indefinitely_date',
        'representation_may_be_cancelled',
        'exclusive_contract',
        'details_on_request',
        'page_url',
        'page_title',
        'page_description',
        'map_lat_lng',
        'map_zoom',
        'map_title',
        'map_type',
        'publicated_at',
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
        'evaluation' => [
            'title' => 'Publikované (čeká na vyhodnocení)',
            'description' => 'Projekt bude nastaven na čeká na vyhodnocení, ale bude veřejně viditelný se stavem "Vyhodnocování"',
        ],
        'finished' => [
            'title' => 'Publikované (dokončené)',
            'description' => 'Projekt bude nastaven na ukončený, ale bude veřejně viditelný se stavem "Ukončeno"',
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
        'auction' => [
            'value' => 'auction',
            'text' => 'Prodej formou aukce',
            'description' => 'Nastavíte délku trvání aukce, vyvolávací částku a minimální příhoz. Zájemci spolu soutěží. Vítězem bude ten, kdo nabídne nejvíce.',
        ],
    ];

    public const REPRESENTATION_OPTIONS = [
        'exclusive' => [
            'value' => 'exclusive',
            'text' => 'Výhradní zastoupení',
            'description' => 'Klienta zastupujete jen vy. Za zveřejnění projektu nic neplatíte. Platíte jen provizi za úspěšné zprostředkování prodeje ve výši, na které se dohodneme před zveřejněním projektu.',
        ],
        'non-exclusive' => [
            'value' => 'non-exclusive',
            'text' => 'Nevýhradní zastoupení',
            'description' => 'Nemáte exkluzivní právo na zprostředkování prodeje projektu. Za zveřejnění projektu zaplatíte inzertní poplatek. Pokud dojde k úspěšnému zprostředkování prodeje skrze portál, zaplatíte nám provizi z prodeje, na jejíž výši se domluvíme před spuštěním projektu. Od této částky bude odečten inzertní poplatek.',
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
        'evaluation',
        'finished',
    ];

    public const STATUS_EVALUATE = [
        'evaluation',
    ];

    public const STATUS_FINISHED = [
        'finished',
    ];

    public const STATUS_NOT_DELETE_USER = [
        'publicated',
        'evaluation',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }

//    public function getRouteKeyName()
//    {
//        return 'page_url'; // Název sloupce, podle kterého se má načítat model
//    }

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

    public function images()
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function shows()
    {
        return $this->hasMany(ProjectShow::class);
    }

    public function myShow()
    {
        return $this->hasMany(ProjectShow::class)->where('user_id', Auth::id());
    }

    public function states()
    {
        return $this->hasMany(ProjectState::class);
    }

    public function tags()
    {
        return $this->hasMany(ProjectTag::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function projectauctionoffers()
    {
        return $this->hasMany(ProjectAuctionOffer::class);
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

    public function endDateText($long = false): Attribute
    {
        $date = $this->end_date;
        if ($date === null) {
            $dateText = 'na neurčito';
        } else {
            $date = Carbon::parse($date, 'Europe/Prague');
            $utcDate = $date->setTimezone('UTC');

            $currentDate = Carbon::now('Europe/Prague');
            $utcCurrentDate = $currentDate->setTimezone('UTC');
            $diff = $utcCurrentDate->diff($utcDate);

            $dateText = '';

            if ($long) {
                $dateText = sprintf('%s d %s h %s m %s s', $diff->days, $diff->h, $diff->i, $diff->s);
            } else {
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
        }

        if ($this->status === 'evaluation') {
            $dateText = 'vyhodnocování';
        } elseif ($this->status === 'finished') {
            $dateText = 'dokončeno';
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $dateText
        );
    }

    public function endDateTextLong(): Attribute
    {
        return $this->endDateText(true);
    }

    public function useCountdownDateTextLong(): Attribute
    {
        if (empty($this->end_date)) {
            $ret = false;
        } else {
            $ret = Carbon::parse($this->end_date, 'Europe/Prague');
            $utcDate = $ret->setTimezone('UTC');
            $ret = $utcDate->format('Y-m-d\TH:i:s\Z');
        }

        if ($this->status !== 'publicated') {
            $ret = false;
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $ret
        );
    }

    public function endDateTextNormal(): Attribute
    {
        if (empty($this->end_date)) {
            $dateText = 'na neurčito';
        } else {
            $dateText = Carbon::parse($this->end_date)->format('d.m.Y H:i:s');
        }

        if ($this->status === 'finished') {
            $dateText = 'dokončeno';
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $dateText
        );
    }

    public function priceText($offer = false, $auctionoffer = false): Attribute
    {
        $priceText = '';
        $price = $this->price;
        $type = $this->type;
        if ($type === 'fixed-price' || $type === null) {
            if (auth()->guest()) {
                $priceText = 'jen pro přihlášené';
            } elseif (!$this->isMine() && !auth()->user()->investor) {
                $priceText = 'jen pro investory';
            } elseif (!$this->isVerified()) {
                $priceText = 'jen s ověřeným účtem';
            } elseif (empty($price)) {
                $priceText = 'cena není zadaná';
            } else {
                $priceText = number_format($price, 0, '.', ' ') . ' Kč';
            }
        } elseif ($type === 'auction' || $type === null) {
            if ($auctionoffer) {
                $price = $this->getActualAuctionPrice();
            }
            if (auth()->guest()) {
                $priceText = 'jen pro přihlášené';
            } elseif (!$this->isMine() && !auth()->user()->investor) {
                $priceText = 'jen pro investory';
            } elseif (!$this->isVerified()) {
                $priceText = 'jen s ověřeným účtem';
            } elseif (empty($price)) {
                $priceText = 'cena není zadaná';
            } else {
                $priceText = number_format($price, 0, '.', ' ') . ' Kč';
            }
        } elseif ($type === 'offer-the-price') {
            if ($this->isVerified()) {
                $priceText = number_format($price, 0, '.', ' ') . ' Kč';
            } else {
                if ($offer) {
                    $priceText = '<span style="background-color: #EBE9E9; overflow: hidden">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>';
                } else {
                    $priceText = 'navrhne investor';
                }
            }
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $priceText
        );
    }

    public function minBidAmountText(): Attribute
    {
        $minBidAmount = $this->min_bid_amount;
        if (auth()->guest()) {
            $minBidAmountText = 'jen pro přihlášené';
        } elseif (!$this->isMine() && !auth()->user()->investor) {
            $minBidAmountText = 'jen pro investory';
        } elseif (!$this->isVerified()) {
            $minBidAmountText = 'jen s ověřeným účtem';
        } elseif (empty($minBidAmount)) {
            $minBidAmountText = 'výše není zadaná';
        } else {
            $minBidAmountText = number_format($minBidAmount, 0, '.', ' ') . ' Kč';
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $minBidAmountText
        );
    }

    public function priceTextOffer(): Attribute
    {
        return $this->priceText(true);
    }

    public function priceTextAuction(): Attribute
    {
        return $this->priceText(false, true);
    }

    public function actualMinBidAmountText(): Attribute
    {
        $minBidAmount = $this->getActualMinBidAmount();
        if (auth()->guest()) {
            $minBidAmountText = 'jen pro přihlášené';
        } elseif (!$this->isMine() && !auth()->user()->investor) {
            $minBidAmountText = 'jen pro investory';
        } elseif (!$this->isVerified()) {
            $minBidAmountText = 'jen s ověřeným účtem';
        } elseif (empty($minBidAmount)) {
            $minBidAmountText = 'výše není zadaná';
        } else {
            $minBidAmountText = number_format($minBidAmount, 0, '.', ' ') . ' Kč';
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $minBidAmountText
        );
    }

    public function actualMinBidAmount(): Attribute
    {
        $price = $this->getActualMinBidAmount();
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $price
        );
    }

    private function getActualMinBidAmount()
    {
        $price = $this->getActualAuctionPrice();

        if ($this->projectauctionoffers()->count()) {
            $price += $this->min_bid_amount ?? 0;
        }

        return $price;
    }

    private function getActualAuctionPrice()
    {
        return $this->projectauctionoffers()->max('offer_amount') ?? $this->price;
    }

    public function minimumPrincipalText(): Attribute
    {
        $price = $this->minimum_principal;

        if (!$this->isVerified()) {
            $priceText = '<span style="background-color: #EBE9E9; overflow: hidden">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>';
        } elseif (empty($price)) {
            $priceText = 'výše není zadaná';
        } else {
            $priceText = number_format($price, 0, '.', ' ') . ' Kč';
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $priceText
        );
    }

    public function urlPart(): Attribute
    {
//        $slugTitle = Str::slug($this->title);
//        $id = $this->id;
//        $url = sprintf('%d-%s', $id, $slugTitle);

        $url = $this->page_url;

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $url
        );
    }

    public function urlDetail(): Attribute
    {
        $url = route('projects.show', ['project' => $this->url_part]);

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $url
        );
    }

    public function shortInfoStrip(): Attribute
    {
        $short_info = strip_tags($this->short_info ?? '');

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $short_info
        );
    }

    public function actualStateText(): Attribute
    {
        $state = null;
        if (in_array($this->status, self::STATUS_PREPARE)) {
            $state = nl2br(htmlspecialchars(trim($this->actual_state ?? 'Projekt čeká na kontrolu provozovatelem')));
            if (empty(trim($state))) {
                $state = 'Projekt čeká na kontrolu provozovatelem';
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
        } elseif ($this->status === 'evaluation') {
            $state = '<span class="text-app-green">Vyhodnocování</span>';
        } elseif ($this->status === 'finished') {
            $state = '<span class="text-app-green">Ukončeno</span>';
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $state
        );
    }

    public function statesPrepared(): Attribute
    {
        $retX = [];
        foreach ($this->states as $item) {
            $retX[] = (object)$item->toArray();
        }

        if (!$this->isVerified()) {
            $ret = [];
            foreach ($retX as $item) {
                $description = html_entity_decode($item->description ?? '');
                $description = preg_split("/\R/", strip_tags($description));
                foreach ($description as $index => $itemX) {
                    $strLen = ceil((mb_strlen($itemX) / 2) * 1.5);
                    $description[$index] = '<p><span style="background-color: #EBE9E9; overflow: hidden">' . str_repeat(' &nbsp;', $strLen) . '</span></p>';
                }
                $item->description = implode("\n", $description);

                $ret[] = $item;
            }
        } else {
            $ret = $retX;
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $ret
        );
    }

    public function detailsPrepared(): Attribute
    {
        $retX = [];
        foreach ($this->details as $item) {
            $retX[] = (object)$item->toArray();
        }

        if (!$this->isVerified()) {
            $ret = [];
            foreach ($retX as $item) {
                $description = html_entity_decode($item->description ?? '');
                $description = preg_split("/\R/", strip_tags($description));
                foreach ($description as $index => $itemX) {
                    $strLen = ceil((mb_strlen($itemX) / 2) * 1.5);
                    $description[$index] = '<p><span style="background-color: #EBE9E9; overflow: hidden">' . str_repeat(' &nbsp;', $strLen) . '</span></p>';
                }
                $item->description = implode("\n", $description);

                $ret[] = $item;
            }
        } else {
            $ret = $retX;
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $ret
        );
    }

    public function aboutPrepared(): Attribute
    {
        $ret = $this->about;

        if (!$this->isVerified()) {
            $description = html_entity_decode($ret ?? '');
            $description = preg_split("/\R/", strip_tags($description));
            foreach ($description as $index => $itemX) {
                $strLen = ceil((mb_strlen($itemX) / 2) * 1.5);
                $description[$index] = '<p><span style="background-color: #EBE9E9; overflow: hidden">' . str_repeat(' &nbsp;', $strLen) . '</span></p>';
            }
            $item = implode("\n", $description);

            $ret = $item;
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
            ((end_date is not null
            and end_date < NOW())
            or status = ?)
            ', 'finished');
    }

    public function scopeForList(Builder $query): Builder
    {
        return $query->withOnly(['tags'])->select([
            'id',
            'page_url',
            'title',
            'short_info',
            'type',
            'status',
            'actual_state',
            'end_date',
        ])->orderByRaw('
            if(status = "publicated", 3,
                if(status = "evaluation", 2,
                    if(status = "finished", 1, 0)
                )
            ) desc
        ')->orderBy('publicated_at', 'desc')
            ->orderBy('id', 'desc');
    }

    private function isVerifiedDefault($checkPublic = true): bool
    {
        if (auth()->guest()) {
            return false;
        }

        if ($this->isMine()) {
            return true;
        }

        if (auth()->user()->isVerifiedInvestor()
            && (!$checkPublic || $this->isPublicForInvestor())) {
            return true;
        }

        return false;
    }

    public function isVerified(): bool
    {
        return $this->isVerifiedDefault();
    }

    public function isVerifiedWithoutCheckPublic(): bool
    {
        return $this->isVerifiedDefault(false);
    }

    public function isPublicForInvestor(): bool
    {
        if (auth()->guest()) {
            return false;
        }

        if (!$this->exclusive_contract) {
            return true;
        }

        if ($this->details_on_request) {
            return (bool)$this->myShow()
                ->where('details_on_request', 999)
                ->count();
        }

        return true;
    }

    public function isMine(): bool
    {
        if (auth()->guest()) {
            return false;
        }

        return ($this->user_id === auth()->id() || auth()->user()->isSuperadmin());
    }

    public function offers()
    {
        if($this->type === 'auction') {
            return $this->projectauctionoffers()->orderBy('offer_amount', 'desc')->orderBy('offer_time', 'desc')->orderBy('id')->get();
        }

        if (!$this->isMine()) {
            return [];
        }

        return $this->shows()->where('offer', 1)->get();
    }

    public function offersCountAll()
    {
        if($this->type === 'auction') {
            return $this->projectauctionoffers->count();
        }

        return $this->shows()->where('offer', 1)->count();
    }

    public function myOffer()
    {
        return ProjectShow::where('user_id', auth()->id())->where('project_id', $this->id)->where('offer', 1)->first();
    }

    public function isStateEvaluation()
    {
        if ($this->shows()->where('winner', 1)->count()) {
            return false;
        }

        if ($this->status === 'evaluation') {
            return true;
        }

        if ($this->status === 'publicated' && $this->end_date === null) {
            return true;
        }

        return false;
    }

    public function zipUrl(): Attribute
    {
        if (!$this->isVerified()) {
            return Attribute::make(
                get: fn(mixed $value, array $attributes) => route('homepage')
            );
        }

        $projectId = $this->id;
        $hash = sha1(sprintf('%s-sadfas##&f58gdfjh-zip', $projectId));

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => route('zip', [
                'project' => $projectId,
                'hash' => $hash,
                'filename' => sprintf('%s.zip', Str::slug(Str::substr($this->title, 0, 32))),
            ])
        );
    }
}
