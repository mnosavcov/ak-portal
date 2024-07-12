<?php

namespace App\Services;

use App\Models\TempProjectFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class TempProjectFileService
{
    public function clear()
    {
        $cacheKey = 'TempProjectFileService_clear';
        $cacheDuration = 60 * 60 * 24;

        if (Cache::has($cacheKey)) {
            return;
        }

        Cache::put($cacheKey, true, $cacheDuration);

        $oneWeekAgo = Carbon::now()->subWeek();

        $results = TempProjectFile::where('created_at', '<', $oneWeekAgo)->get();

        foreach ($results as $result) {
            $directory =  trim(dirname($result->filepath), '/');

            if(Storage::fileExists($result->filepath)) {
                Storage::delete($result->filepath);
            }

            if(!Storage::fileExists($result->filepath)) {
                $result->delete();
            }

            $files = Storage::files($directory);
            if(empty($files) && Storage::exists($directory)) {
                Storage::deleteDirectory($directory);
            }
        }
    }
}
