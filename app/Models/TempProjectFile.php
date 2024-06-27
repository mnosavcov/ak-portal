<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempProjectFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'temp_project_id',
        'filepath',
        'filename',
    ];
}
