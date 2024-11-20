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

    public static function getCATEGORIES()
    {
        return [
            'auction' => [
                'id' => 'auction',
                'title' => __('kategorie.Auction-Title'),
                'description' => __('kategorie.Auction-Description'),
                'url' => 'aukce',
            ],
            'fixed-price' => [
                'id' => 'fixed-price',
                'title' => __('kategorie.FixedPrice-Title'),
                'description' => __('kategorie.FixedPrice-Description'),
                'url' => 'cenu-navrhuje-nabizejici',
            ],
            'offer-the-price' => [
                'id' => 'offer-the-price',
                'title' => __('kategorie.OfferThePrice-Title'),
                'description' => __('kategorie.OfferThePrice-Description'),
                'url' => 'cenu-navrhuje-investor',
            ],
            'preliminary-interest' => [
                'id' => 'preliminary-interest',
                'title' => __('kategorie.PreliminaryInterest-Title'),
                'description' => __('kategorie.PreliminaryInterest-Description'),
                'url' => 'projev-predbezneho-zajmu',
            ],
        ];
    }

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
