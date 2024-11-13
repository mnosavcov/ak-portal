<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'category',
        'subcategory',
        'description',
        'url',
        'order',
    ];

    public const CATEGORIES = [
        'auction' => [
            'id' => 'auction',
            'title' => 'kategorie.Auction-Title',
            'description' => 'kategorie.Auction-Description',
            'url' => 'aukce',
        ],
        'fixed-price' => [
            'id' => 'fixed-price',
            'title' => 'kategorie.FixedPrice-Title',
            'description' => 'kategorie.FixedPrice-Description',
            'url' => 'cenu-navrhuje-nabizejici',
        ],
        'offer-the-price' => [
            'id' => 'offer-the-price',
            'title' => 'kategorie.OfferThePrice-Title',
            'description' => 'kategorie.OfferThePrice-Description',
            'url' => 'cenu-navrhuje-investor',
        ],
        'preliminary-interest' => [
            'id' => 'preliminary-interest',
            'title' => 'kategorie.PreliminaryInterest-Title',
            'description' => 'kategorie.PreliminaryInterest-Description',
            'url' => 'projev-predbezneho-zajmu',
        ],
    ];

    public function url(): Attribute
    {
        return Attribute::make(
            set: function (mixed $value, array $attributes) {
                if (empty($value)) {
                    return Str::slug(trim($attributes['subcategory']));
                }
                return Str::slug(trim($value));
            }
        );
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'subcategory_id');
    }
}
