<?php

namespace App\Models;

use App\Services\BackupService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectQuestion extends Model
{
    use HasFactory;

    protected $table = 'project_contents';

    protected $fillable = [
        'content',
        'files',
    ];

    protected $appends = [
        'date_text',
        'file_uuid',
        'temp_file_url',
        'user_name_text',
        'content_text',
        'verified',
        'file_list',
        'response_button',
        'content_text_edit',
        'not_confirmed_reason_text',
        'history_list',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
            $model->type = 'questions';
        });

        static::updating(function ($model) {
            if (
                $model->getDirty()
                && is_array($model->getDirty())
                && count($model->getDirty()) === 1
                && array_keys($model->getDirty())[0] === 'files'
                && json_encode($model->files) === $model->getOriginal()['files']
            ) {
                return;
            }

            if (!$model->getDirty()) {
                return;
            }

            (new BackupService)->backup2Table($model);
        });

        static::addGlobalScope('type', function ($builder) {
            $builder->where('type', 'questions');
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dateText(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => str_replace(' ', '&nbsp;', Carbon::create($this->created_at, 'UTC')->setTimezone('Europe/Prague')->format('d.m.Y   H:i:s'))
        );
    }

    public function fileUuid(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => Str::uuid()
        );
    }

    public function tempFileUrl(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => route('project-questions.store-temp-file', ['uuid' => $this->file_uuid])
        );
    }

    public function answers()
    {
        return $this->hasMany(ProjectQuestion::class, 'parent_id');
    }

    public function childAnswers()
    {
        $questions = $this->hasMany(ProjectQuestion::class, 'parent_id')
            ->orderBy('id')
            ->with('childAnswers', 'user');

        if (!auth()->user() || !auth()->user()->isSuperadmin()) {
            if (auth()->user()) {
                $questions = $questions->where(function ($query) {
                    $query->where('confirmed', 1)
                        ->orWhere('user_id', auth()->user()->id);
                });
            } else {
                $questions = $questions->where('confirmed', 1);
            }
        }

        return $questions;
    }

    public function parentAnswer()
    {
        return $this->belongsTo(ProjectQuestion::class, 'parent_id');
    }

    public function isVerified(): bool
    {
        if (auth()->guest()) {
            return false;
        }

        if (auth()->user()->isSuperadmin()) {
            return true;
        }

        if (auth()->user()->id === $this->user_id) {
            return true;
        }

        if (!$this->project->isVerified()) {
            return false;
        }

        if ($this->confirmed === 1) {
            return true;
        }

        return false;
    }

    public function verified(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $this->isVerified()
        );
    }

    public function userNameText(): Attribute
    {
        $questionUserId = $this->user_id;
        $projectUserId = $this->project->user_id;

        $userName = 'Investor ' . $this->user->crypt;
        if (auth()->user() && $questionUserId === auth()->user()->id) {
            $userName = '<span class="text-app-orange">Vy</span>';
        } elseif ($this->user->owner === 1 || $this->user->superadmin === 1) {
            $userName = 'Administrátor';
        } elseif ($questionUserId === $projectUserId) {
            $userName = 'Zadavatel';
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $userName
        );
    }

    public function contentText(): Attribute
    {
        $content = $this->content;
        if (!$this->isVerified()) {
            $description = html_entity_decode($content);
            $description = preg_split("/\R/", strip_tags($description));
            foreach ($description as $index => $itemX) {
                $strLen = ceil((mb_strlen($itemX) / 2) * 1.5);
                $description[$index] = '<p><span style="background-color: #EBE9E9; overflow: hidden">' . str_repeat(' &nbsp;', $strLen) . '</span></p>';
            }
            $content = implode("\n", $description);
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $content
        );
    }

    public function fileList(): Attribute
    {
        $order = 0;
        $fileList = [];
        $files = json_decode($this->files);
        foreach ($files as $index => $filename) {
            $projectId = $this->project_id;
            $fileId = $index;
            $hash = sha1(sprintf('%s-77WOLvgNgjs-%s-%s', $projectId, $fileId, $this->id));
            $partsFilename = explode('.', $filename);
            if ($this->isVerified()) {
                foreach ($partsFilename as $index2 => $partFilename) {
                    $partsFilename[$index2] = Str::slug($partFilename);
                }
                $slugFilename = implode('.', $partsFilename);
                $fileurl = route('question-file', [
                    'project' => $projectId,
                    'project_question' => $this->id,
                    'question_file' => sha1($fileId),
                    'hash' => $hash,
                    'filename' => $slugFilename,
                ]);
                $fileindex = $index;
            } else {
                $order++;
                $filename = 'příloha ' . $order . '.' . $partsFilename[count($partsFilename) - 1];
                $fileurl = '#';
                $fileindex = null;
            }

            $fileList[] = [
                'filename' => $filename,
                'fileindex' => $fileindex,
                'fileurl' => $fileurl,
            ];
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $fileList
        );
    }

    public function responseButton(): Attribute
    {
        $button = [
            'canResponse' => false,
            'textButton' => '',
        ];

        if (auth()->guest()) {
            $button['textButton'] = '';
        } elseif (!$this->project->isMine() && auth()->user()->investor === 0) {
            $button['textButton'] = '';
        } elseif (!$this->project->isMine() && !auth()->user()->isVerifiedInvestor()) {
            $button['textButton'] = '';
        } elseif (!$this->project->isMine() && !$this->project->isPublicForInvestor()) {
            if ($this->project->myShow()->first()->details_on_request === 0) {
                $button['textButton'] = '';
            } elseif ($this->project->myShow()->first()->details_on_request === 1) {
                $button['textButton'] = '';
            } elseif ($this->project->myShow()->first()->details_on_request === -1) {
                $button['textButton'] = '';
            }
        } elseif ($this->confirmed === 0) {
            $button['textButton'] = '';
        } elseif ($this->confirmed === -1) {
            $button['textButton'] = '';
        } elseif ($this->isVerified()) {
            $button = [
                'canResponse' => true,
                'textButton' => 'Odpovědět',
            ];
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $button
        );
    }

    public function contentTextEdit(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $this->content_text
        );
    }

    public function notConfirmedReasonText(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => nl2br(e($this->not_confirmed_reason))
        );
    }

    public function historyList(): Attribute
    {
        if (auth()->guest()) {
            return Attribute::make(
                get: fn(mixed $value, array $attributes) => []
            );
        }

        if (!auth()->user()->isSuperadmin()) {
            return Attribute::make(
                get: fn(mixed $value, array $attributes) => []
            );
        }

        $historyData = DB::table('backups')
            ->where('table', $this->getTable())
            ->where('column_id', $this->id)
            ->orderBy('id', 'desc')
            ->get();

        $history = [];
        foreach ($historyData as $item) {
            $history[(-1 * $item->id)] = (array)$item;
            $history[(-1 * $item->id)]['ext_data'] = json_decode($item->data, true);
            $history[(-1 * $item->id)]['ext_user'] = User::find($item->user_id);
            $history[(-1 * $item->id)]['ext_date'] = Carbon::create($item->created_at, 'UTC')->setTimezone('Europe/Prague')->format('d.m.Y H:i:s');
        }

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $history
        );
    }
}
