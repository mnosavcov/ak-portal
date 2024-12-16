<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAuctionOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_amount',
    ];

    protected $appends = [
        'offer_amount_text',
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

    public function offerAmountText(): Attribute
    {
        $price = $this->offer_amount;
        if (auth()->guest()) {
            $priceText = 'jen pro přihlášené';
        } elseif (!$this->project->isMine() && !auth()->user()->investor) {
            $priceText = 'jen pro investory';
        } elseif (!$this->project->isVerified()) {
            $priceText = 'jen s ověřeným účtem';
        } elseif (empty($price)) {
            $priceText = 'cena není zadaná';
        } else {
            $priceText = number_format($price, 0, '.', ' ') . ' Kč';
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $priceText
        );
    }
}
