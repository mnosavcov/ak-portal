<?php

namespace App\Http\Controllers\App;

use App\Events\ProjectCommentAddedEvent;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectQuestion;
use App\Models\TempProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (($request->post('data')['projectId'] ?? null) > 0) {
            $projectId = $request->post('data')['projectId'];
            $project = Project::find($projectId);

            $projectAnswer = new ProjectQuestion([
                'content' => $request->post('data')['question'],
            ]);

            $project->projectquestions()->save($projectAnswer);
        }

        if (($request->post('data')['questionId'] ?? null) > 0) {
            $projectQuestion = ProjectQuestion::find($request->post('data')['questionId']);

            $projectAnswer = new ProjectQuestion([
                'content' => $request->post('data')['answer'],
            ]);
            $projectAnswer->project_id = $projectQuestion->project_id;
            $projectAnswer->level = $projectQuestion->level + 1;

            if (auth()->user()->isSuperadmin()) {
                $projectAnswer->confirmed = true;
            }

            $projectQuestion->answers()->save($projectAnswer);

            if (auth()->user()->isSuperadmin()) {
                event(new ProjectCommentAddedEvent($projectAnswer->project, $projectAnswer));
            }
        }

        $images = TempProjectFile::where('temp_project_id', $request->post('data')['uuid'])
            ->whereIn('id', array_keys($request->post('data')['files']))->get();
        if ($images) {
            $questionFiles = [];
            foreach ($images as $image) {
                $path = $image->filepath;
                $path = str_replace(
                    'temp/' . $request->post('data')['uuid'],
                    'projects/' . $projectAnswer->project->user_id . '/' . $projectAnswer->project->id . '/questions/' . $projectAnswer->id,
                    $path
                );

                Storage::copy($image->filepath, $path);
                $questionFiles[$path] = $image->filename;
            }

            $projectAnswer->files = $questionFiles;
            $projectAnswer->timestamps = false;
            $projectAnswer->save();
            $projectAnswer->timestamps = true;
        }

        return [
            'status' => 'success',
            'list' => Project::find($projectAnswer->project->id)->getQuestionsWithAnswers(),
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function file(Project $project, ProjectQuestion $projectQuestion, $questionFile, $urlHash)
    {
        if ($projectQuestion->project_id !== $project->id) {
            return redirect()->route('homepage');
        }

        $files = json_decode($projectQuestion->files, true);
        $selectedFileIndex = null;
        foreach ($files as $index => $file) {
            if (sha1($index) === $questionFile) {
                $selectedFileIndex = $index;
                break;
            }
        }

        if (!$selectedFileIndex) {
            return redirect()->route('homepage');
        }

        $hash = sha1(sprintf('%s-77WOLvgNgjs-%s-%s', $project->id, $selectedFileIndex, $projectQuestion->id));
        if ($urlHash !== $hash) {
            return redirect()->route('homepage');
        }

        if (!$projectQuestion->isVerified()) {
            return redirect()->route('homepage');
        }

        return Storage::download($selectedFileIndex, $files[$selectedFileIndex]);
    }
}
