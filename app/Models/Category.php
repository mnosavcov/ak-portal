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
            'title' => 'Aukce',
            'description' => 'Aukce',
            'url' => 'auction',
        ],
        'fixed-price' => [
            'title' => 'Cenu navrhuje prodávající',
            'description' => 'Cenu navrhuje prodávající',
            'url' => 'fixed-price',
        ],
        'offer-the-price' => [
            'title' => 'Cenu navrhuje kupující',
            'description' => 'Cenu navrhuje kupující',
            'url' => 'offer-the-price',
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
}
