<?php

namespace App\Models;

use App\Services\NotificationEventService;
use App\Services\ProjectService;
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
        'tag_list',
        'type_text',
        'category_text',
        'short',
        'short_info_strip',
        'actual_state_text',
        'status_text',
        'states_prepared',
        'details_prepared',
        'about_prepared',
        'use_countdown_date_text_long',
        'zip_url',
        'min_bid_amount_text',
        'new_questions_count',
        'new_actualities_count',
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

    public static function getSTATUSES()
    {
        return [
            'draft' => [
                'title' => __('Rozpracováno'),
                'description' => __('Stav ve kterém může zadavatel upravovat zadání (nemůže upravovat data, která jsou zadaná administrátorem))'),
            ],
            'send' => [
                'title' => __('Odesláno ke zpracování'),
                'description' => __('Zadavatel odeslal své zadání ke zpracování projektu'),
            ],
            'prepared' => [
                'title' => __('Připraveno ke kontrole zadavatelem'),
                'description' => __('Zpracované administrátorem a zaslané zadavateli ke schválení'),
            ],
            'confirm' => [
                'title' => __('Potvrzeno zadavatelem'),
                'description' => __('Zadavatel potvrdil správnost projektu'),
            ],
            'reminder' => [
                'title' => __('Zadavatel má připomínky'),
                'description' => __('Zadavatel má připomínky ke správnosti projektu'),
            ],
            'publicated' => [
                'title' => __('Publikované (aktivní)'),
                'description' => __('Projekt bude vypublikován a bude veřejně přístupný'),
            ],
            'evaluation' => [
                'title' => __('Publikované (čeká na vyhodnocení)'),
                'description' => __('Projekt bude nastaven na čeká na vyhodnocení, ale bude veřejně viditelný se stavem "Vyhodnocování"'),
            ],
            'finished' => [
                'title' => __('Publikované (dokončené)'),
                'description' => __('Projekt bude nastaven na ukončený, ale bude veřejně viditelný se stavem "Ukončeno"'),
            ],
        ];
    }


    public static function getPAID_TYPES()
    {
        return [
            'fixed-price' => [
                'value' => 'fixed-price',
                'text' => __('Cenu stanovíte vy (prodávající)'),
                'description' => __('V projektu nastavíte fixní cenu, kterou chcete za projekt obdržet. Jakmile ji některý z investorů nabídne, dochází k ukončení projektu.'),
            ],
            'offer-the-price' => [
                'value' => 'offer-the-price',
                'text' => __('Cenu stanoví zájemce o projekt (investor)'),
                'description' => __('Zájemci o projekt předkládají po vámi určenou dobu své nabídky, jejichž výše není veřejná. Po skončení sběru nabídek vyberete vítěze. Můžete nastavit minimální částku, za kterou jste ochotni projekt prodat.'),
            ],
//            'auction' => [
//                'value' => 'auction',
//                'text' => __('Prodej formou aukce'),
//                'description' => __('Nastavíte délku trvání aukce, vyvolávací částku a minimální příhoz. Zájemci spolu soutěží. Vítězem bude ten, kdo nabídne nejvíce.'),
//            ],
            'preliminary-interest' => [
                'value' => 'preliminary-interest',
                'text' => __('Chci získat jen projevy předběžného zájmu'),
                'description' => __('Máte projekt v rané fázi? Informujte o něm už nyní. Investoři se nezávazně přihlásí a k prodeji dojde, až bude projekt připraven.'),
            ],
        ];
    }

    public static function getREPRESENTATION_OPTIONS()
    {
        return [
            'exclusive' => [
                'value' => 'exclusive',
                'text' => __('Výhradní zastoupení'),
                'description' => __('Klienta zastupujete jen vy. Za zveřejnění projektu nic neplatíte. Platíte jen provizi za úspěšné zprostředkování prodeje ve výši, na které se dohodneme před zveřejněním projektu.'),
            ],
            'non-exclusive' => [
                'value' => 'non-exclusive',
                'text' => __('Nevýhradní zastoupení'),
                'description' => __('Nemáte exkluzivní právo na zprostředkování prodeje projektu. Za zveřejnění projektu zaplatíte inzertní poplatek. Pokud dojde k úspěšnému zprostředkování prodeje skrze portál, zaplatíte nám provizi z prodeje, na jejíž výši se domluvíme před spuštěním projektu. Od této částky bude odečten inzertní poplatek.'),
            ],
        ];
    }

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

        static::updated(function ($model) {
            (new NotificationEventService())->ProjectChange($model->getOriginal(), $model->getChanges());
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

    public function projectquestions()
    {
        return $this->hasMany(ProjectQuestion::class);
    }

    public function projectactualities()
    {
        return $this->hasMany(ProjectActuality::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notificationevents()
    {
        return $this->belongsTo(NotificationEvent::class);
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
            $dateText = __('projekt.na_neurčito');
        } else {
            $date = Carbon::parse($date);

            $currentDate = Carbon::now();
            $diff = $currentDate->diff($date);

            $dateText = '';

            if ($long) {
                $dateText = sprintf('%s d %s h %s m %s s', $diff->days, $diff->h, $diff->i, $diff->s);
            } else {
                if (isset($diff->days) && $diff->days > 0) {
                    if ($diff->days === 1) {
                        $dateText .= __('projekt.1_den');
                    } elseif ($diff->days > 1 && $diff->days < 5) {
                        $dateText .= $diff->days . __('projekt._dny');
                    } else {
                        $dateText .= $diff->days . __('projekt._dní');
                    }
                }
                if ($diff->h === 1) {
                    $dateText .= __('projekt._1_hodina');
                } elseif ($diff->h > 1 && $diff->h < 5) {
                    $dateText .= ' ' . $diff->h . __('projekt._hodiny');
                } elseif ($diff->h > 4) {
                    $dateText .= ' ' . $diff->h . __('projekt._hodin');
                }
            }
        }

        if ($this->status === 'evaluation') {
            $dateText = __('projekt.vyhodnocování');
        } elseif ($this->status === 'finished') {
            $dateText = __('projekt.dokončeno');
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
            $ret = Carbon::parse($this->end_date)->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z');
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
            $dateText = __('projekt.na_neurčito');
        } else {
            $dateText = Carbon::parse($this->end_date)->format('d.m.Y H:i:s');
        }

        if ($this->status === 'finished') {
            $dateText = __('projekt.dokončeno');
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
                $priceText = __('projekt.jen_pro_přihlášené');
            } elseif (!$this->isMine() && !auth()->user()->investor) {
                $priceText = __('projekt.jen_pro_investory');
            } elseif (!$this->isVerified()) {
                $priceText = __('projekt.jen_s_ověřeným_účtem');
            } elseif (empty($price)) {
                $priceText = __('projekt.cena_není_zadaná');
            } else {
                $priceText = number_format($price, 0, '.', ' ') . __('projekt._Kč');
            }
        } elseif ($type === 'auction' || $type === null) {
            if ($auctionoffer) {
                $price = $this->getActualAuctionPrice();
            }
            if (auth()->guest()) {
                $priceText = __('projekt.jen_pro_přihlášené');
            } elseif (!$this->isMine() && !auth()->user()->investor) {
                $priceText = __('projekt.jen_pro_investory');
            } elseif (!$this->isVerified()) {
                $priceText = __('projekt.jen_s_ověřeným_účtem');
            } elseif (empty($price)) {
                $priceText = __('projekt.cena_není_zadaná');
            } else {
                $priceText = number_format($price, 0, '.', ' ') . __('projekt._Kč');
            }
        } elseif ($type === 'offer-the-price') {
            if ($this->isVerified()) {
                $priceText = number_format($price ?? 0, 0, '.', ' ') . __('projekt._Kč');
            } else {
                if ($offer) {
                    $priceText = '<span style="background-color: #EBE9E9; overflow: hidden">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>';
                } else {
                    $priceText = __('projekt.navrhne_investor');
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
            $minBidAmountText = __('projekt.jen_pro_přihlášené');
        } elseif (!$this->isMine() && !auth()->user()->investor) {
            $minBidAmountText = __('projekt.jen_pro_investory');
        } elseif (!$this->isVerified()) {
            $minBidAmountText = __('projekt.jen_s_ověřeným_účtem');
        } elseif (empty($minBidAmount)) {
            $minBidAmountText = __('projekt.výše_není_zadaná');
        } else {
            $minBidAmountText = number_format($minBidAmount, 0, '.', ' ') . __('projekt._Kč');
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
            $minBidAmountText = __('projekt.jen_pro_přihlášené');
        } elseif (!$this->isMine() && !auth()->user()->investor) {
            $minBidAmountText = __('projekt.jen_pro_investory');
        } elseif (!$this->isVerified()) {
            $minBidAmountText = __('projekt.jen_s_ověřeným_účtem');
        } elseif (empty($minBidAmount)) {
            $minBidAmountText = __('projekt.výše_není_zadaná');
        } else {
            $minBidAmountText = number_format($minBidAmount, 0, '.', ' ') . __('projekt._Kč');
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
            $priceText = __('projekt.výše_není_zadaná');
        } else {
            $priceText = number_format($price, 0, '.', ' ') . __('projekt._Kč');
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

    public function short(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $this->short_info_strip
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
            $state = nl2br(htmlspecialchars(trim($this->actual_state ?? __('projekt.Projekt_čeká_na_kontrolu_provozovatelem'))));
            if (empty(trim($state))) {
                $state = __('projekt.Projekt_čeká_na_kontrolu_provozovatelem');
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
            if ($this->type === 'preliminary-interest') {
                $state = '<span class="text-app-green">' . __('projekt.Příjem_zájemců') . '</span>';
            } else {
                $state = '<span class="text-app-green">' . __('projekt.Aktivní') . '</span>';
            }
        } elseif ($this->status === 'evaluation') {
            $state = '<span class="text-app-green">' . __('projekt.Vyhodnocování') . '</span>';
        } elseif ($this->status === 'finished') {
            $state = '<span class="text-app-green">' . __('projekt.Ukončeno') . '</span>';
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

    public function getQuestionsWithAnswers()
    {
        $questions = $this->projectquestions()
            ->orderBy('id', 'desc')
            ->whereNull('parent_id')
            ->with(['childAnswers', 'user']);

        if (!auth()->user() || !auth()->user()->isSuperadmin()) {
            if (auth()->user()) {
                $questions = $questions->where(function ($query) {
                    $query->where('confirmed', 1)
                        ->orWhere('user_id', auth()->user()->id);
                });
            } else {
                $questions = $questions->where('confirmed', 1);
            }
        }

        return $questions->get()->map(function ($question) {
            $appendColumns = [];
            if (auth()->user()) {
                if (auth()->user()->isSuperadmin()) {
                    $appendColumns = [
                        'not_confirmed_reason',
                        'not_confirmed_reason_text',
                    ];
                } elseif (auth()->user()->id === $question->user_id) {
                    $appendColumns = [
                        'not_confirmed_reason_text',
                    ];
                }
            }

            $questionData = $question->only(array_merge([
                'id',
                'content_text',
                'content_text_edit',
                'file_list',
                'confirmed',
                'user_id',
                'user_name_text',
                'date_text',
                'verified',
                'file_uuid',
                'temp_file_url',
                'parent_id',
                'project_id',
                'response_button',
                'level',
                'history_list',
            ], $appendColumns));

            $questionData['child_answers'] = $this->getChildAnswersRecursive($question->childAnswers);

            return $questionData;
        });
    }

    private function getChildAnswersRecursive($answers)
    {
        return $answers->map(function ($answer) {
            $appendColumns = [];
            if (auth()->user()) {
                if (auth()->user()->isSuperadmin()) {
                    $appendColumns = [
                        'not_confirmed_reason',
                        'not_confirmed_reason_text',
                    ];
                } elseif (auth()->user()->id === $answer->user_id) {
                    $appendColumns = [
                        'not_confirmed_reason_text',
                    ];
                }
            }

            $answerData = $answer->only(array_merge([
                'id',
                'content_text',
                'content_text_edit',
                'file_list',
                'confirmed',
                'user_id',
                'user_name_text',
                'date_text',
                'verified',
                'file_uuid',
                'temp_file_url',
                'parent_id',
                'project_id',
                'response_button',
                'level',
                'history_list',
            ], $appendColumns));

            // Rekurzivně zpracujeme childAnswers, pokud existují
            if ($answer->childAnswers->isNotEmpty()) {
                $answerData['child_answers'] = $this->getChildAnswersRecursive($answer->childAnswers, $appendColumns);
            } else {
                $answerData['child_answers'] = [];
            }

            return $answerData;
        });
    }

    public function getActualities()
    {
        $actualities = $this->projectactualities()
            ->orderBy('id', 'desc')
            ->whereNull('parent_id')
            ->with(['user']);

        $appendColumns = [];

        if (!auth()->user() || !auth()->user()->isSuperadmin()) {
            if (auth()->user()) {
                $actualities = $actualities->where(function ($query) {
                    $query->where('confirmed', 1)
                        ->orWhere('user_id', auth()->user()->id);
                });
            } else {
                $actualities = $actualities->where('confirmed', 1);
            }
        }

        if (auth()->user()) {
            if (auth()->user()->isSuperadmin()) {
                $appendColumns = [
                    'not_confirmed_reason',
                    'not_confirmed_reason_text',
                ];
            } elseif (auth()->user()->id === $this->user_id) {
                $appendColumns = [
                    'not_confirmed_reason_text',
                ];
            }
        }

        return $actualities->get()->map(function ($actualities) use ($appendColumns) {
            return $actualities->only(array_merge([
                'id',
                'content_text',
                'content_text_edit',
                'file_list',
                'confirmed',
                'user_id',
                'user_name_text',
                'date_text',
                'verified',
                'file_uuid',
                'temp_file_url',
                'history_list',
            ], $appendColumns));
        });
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
        if ($this->type === 'auction') {
            return $this->projectauctionoffers()->orderBy('offer_amount', 'desc')->orderBy('offer_time', 'desc')->orderBy('id')->get();
        }

        if (!$this->isMine()) {
            return [];
        }

        return $this->shows()->where('offer', 1)->orderBy('offer_time', 'asc')->get();
    }

    public function offersCountAll()
    {
        if ($this->type === 'auction') {
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

    public function newQuestionsCount(): Attribute
    {
        //        throw new \Exception('tuhle funkčnost nepoužívat, počítá chybně');
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => -999
        );

        $count = 0;
        if (auth()->user()) {
            $maxId = $this->myShow()->max('max_question_id') ?? 0;
            $count = $this->projectquestions()
                ->where('confirmed', 1)
                ->where('id', '>', $maxId)
                ->count();
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $count
        );
    }

    public function newActualitiesCount(): Attribute
    {
        $count = 0;
        if (auth()->user()) {
            $maxId = $this->myShow()->max('max_actuality_id') ?? 0;
            $count = $this->projectactualities()
                ->where('confirmed', 1)
                ->where('id', '>', $maxId)
                ->count();
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $count
        );
    }

    public function typeText(): Attribute
    {
        $type = Category::getCATEGORIES()[$this->type]['title'];

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $type
        );
    }

    public function categoryText(): Attribute
    {
        $subject = ProjectService::getSUBJECT_OFFERS_ALL_VERSIONS()[$this->subject_offer] ?? $this->subject_offer;
        $location = ProjectService::getLOCATION_OFFERS_ALL_VERSIONS()[$this->location_offer] ?? $this->location_offer;

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $subject . ' / ' . $location
        );
    }

    public function tagList(): Attribute
    {
        $tagList = $this->tags->pluck('title')->implode(', ');

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $tagList
        );
    }
}
