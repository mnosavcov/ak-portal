<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'pohyb_id',
        'pokyn_id',
        'datum',
        'castka',
        'mena',
        'protiucet',
        'protiucet_kodbanky',
        'protiucet_nazevbanky',
        'protiucet_nazevprotiuctu',
        'protiucet_uzivatelska_identifikace',
        'vs',
        'zprava_pro_prijemce',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
