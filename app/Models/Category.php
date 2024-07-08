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
            'title' => 'Aukce',
            'description' => 'Investujte do projektů v oblasti výstavby a provozu obnovitelných zdrojů energie v různých stupních rozpracovanosti.',
            'url' => 'aukce',
        ],
        'fixed-price' => [
            'id' => 'fixed-price',
            'title' => 'Cenu navrhuje prodávající',
            'description' => 'Investujte do projektů v oblasti výstavby a provozu obnovitelných zdrojů energie v různých stupních rozpracovanosti.',
            'url' => 'cenu-navrhuje-prodavajici',
        ],
        'offer-the-price' => [
            'id' => 'offer-the-price',
            'title' => 'Cenu navrhuje investor',
            'description' => 'Investujte do projektů v oblasti výstavby a provozu obnovitelných zdrojů energie v různých stupních rozpracovanosti.',
            'url' => 'cenu-navrhuje-investor',
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
