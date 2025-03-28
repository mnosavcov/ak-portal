<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'filepath',
        'filename',
        'order',
        'public',
        'folder',
        'description',
    ];

    protected $appends = [
        'url',
        'filefolder',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function url(): Attribute
    {
        $projectId = $this->project_id;
        $fileId = $this->id;
        $hash = sha1(sprintf('%s-KUYGddfg878-%s', $projectId, $fileId));
        $filename = $this->filename;
        $partsFilename = explode('.', $filename);
        foreach($partsFilename as $index => $partFilename) {
            $partsFilename[$index] = Str::slug($partFilename);
        }
        $filename = implode('.', $partsFilename);

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => route('file', [
                'project' => $projectId,
                'project_file' => $fileId,
                'hash' => $hash,
                'filename' => $filename,
            ])
        );
    }

    public function filefolder(): Attribute
    {
        if(empty($this->folder)) {
            return Attribute::make(
                get: fn(mixed $value, array $attributes) => 'Hlavní adresář'
            );
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $this->folder
        );
    }

}
