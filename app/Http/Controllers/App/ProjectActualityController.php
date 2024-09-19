<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectActuality;
use App\Models\TempProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectActualityController extends Controller
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
        $projectId = $request->post('data')['projectId'];
        $project = Project::find($projectId);

        if($project->user_id != auth()->user()->id) {
            return [
                'status' => 'success',
                'list' => Project::find($project->id)->getActualities(),
            ];
        }

        $projectActuality = new ProjectActuality([
            'content' => $request->post('data')['actuality'],
        ]);

        $project->projectactualities()->save($projectActuality);

        $images = TempProjectFile::where('temp_project_id', $request->post('data')['uuid'])
            ->whereIn('id', array_keys($request->post('data')['files']))->get();
        if ($images) {
            $actualityFiles = [];
            foreach ($images as $image) {
                $path = $image->filepath;
                $path = str_replace(
                    'temp/' . $request->post('data')['uuid'],
                    'projects/' . $projectActuality->project->user_id . '/' . $projectActuality->project->id . '/actualities/' . $projectActuality->id,
                    $path
                );

                Storage::copy($image->filepath, $path);
                $actualityFiles[$path] = $image->filename;
            }

            $projectActuality->files = $actualityFiles;
            $projectActuality->save();
        }

        return [
            'status' => 'success',
            'list' => Project::find($projectActuality->project->id)->getActualities(),
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

    public function file(Project $project, ProjectActuality $projectActuality, $actualityFile, $urlHash)
    {
        if ($projectActuality->project_id !== $project->id) {
            return redirect()->route('homepage');
        }

        $files = json_decode($projectActuality->files, true);
        $selectedFileIndex = null;
        foreach ($files as $index => $file) {
            if (sha1($index) === $actualityFile) {
                $selectedFileIndex = $index;
                break;
            }
        }

        if (!$selectedFileIndex) {
            return redirect()->route('homepage');
        }

        $hash = sha1(sprintf('%s-W1zBaIoqfqw-%s-%s', $project->id, $selectedFileIndex, $projectActuality->id));
        if ($urlHash !== $hash) {
            return redirect()->route('homepage');
        }

        if (!$projectActuality->isVerified()) {
            return redirect()->route('homepage');
        }

        return Storage::download($selectedFileIndex, $files[$selectedFileIndex]);
    }
}
