<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectGallery extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'filepath',
        'filename',
        'order',
        'public',
        'head_img',
    ];

    protected $appends = [
        'url',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function url(): Attribute
    {
        $projectId = $this->project_id;
        $fileId = $this->id;
        $hash = sha1(sprintf('%s-KUYGddfg878-%s-gallery', $projectId, $fileId));
        $filename = $this->filename;

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => route('gallery', [
                'project' => $projectId,
                'project_gallery' => $fileId,
                'hash' => $hash,
                'filename' => $filename,
            ])
        );
    }
}
