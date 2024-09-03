<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectTag extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'color',
    ];

    protected $appends = [
        'file_url',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function fileUrl(): Attribute
    {
        $file = json_decode($this->file, true);
        if (empty($file)) {
            return Attribute::make(
                get: fn(mixed $value, array $attributes) => null
            );
        }

        $index = array_keys($file)[0];

        $hash = sha1(sprintf('%s-341VJnP1Hd9-%s-tags', $this->project->id, $this->id));
        $filename = $file[$index];
        $partsFilename = explode('.', $filename);
        foreach ($partsFilename as $index => $partFilename) {
            $partsFilename[$index] = Str::slug($partFilename);
        }
        $filename = implode('.', $partsFilename);

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => route('project-tags.image', [
                'project' => $this->project->id,
                'project_tag' => $this->id,
                'hash' => $hash,
                'filename' => $filename,
            ])
        );
    }
}
