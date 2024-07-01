<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'input_data'
    ];

    public function getFJmenoAttribute()
    {
        $data = unserialize($this->input_data);
        return ucfirst($data['kontaktJmeno'] ?? '') . ' ' . ucfirst($data['kontaktPrijmeni'] ?? '');
    }
}
