<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'filepath',
        'filename',
        'order',
    ];

    protected $appends = [
        'url',
        'copy_url',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function url(): Attribute
    {
        $projectId = $this->project_id;
        $fileId = $this->id;
        $hash = sha1(sprintf('%s-KUYsdflogkd87fff8-%s-image', $projectId, $fileId));
        $filename = $this->filename;
        $partsFilename = explode('.', $filename);
        foreach($partsFilename as $index => $partFilename) {
            $partsFilename[$index] = Str::slug($partFilename);
        }
        $filename = implode('.', $partsFilename);

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => route('image', [
                'project' => $projectId,
                'project_image' => $fileId,
                'hash' => $hash,
                'filename' => $filename,
            ])
        );
    }

    public function copyUrl(): Attribute
    {
        $url = $this->url;
        $url = explode('image/', $url);
        $url = '/image/' . $url[1];

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $url
        );
    }
}
