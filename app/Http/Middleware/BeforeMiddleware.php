<?php

namespace App\Http\Middleware;

use App\Facades\QueryLog;
use App\Models\Project;
use App\Services\TempProjectFileService;
use App\Services\UsersService;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BeforeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user() && Auth::user()->deleted_at) {
            (new UsersService())->logout();
            return redirect('/');
        }

        if (
            Auth::user()
            && (Auth::user()->banned_at)
            && !(
                $request->getUri() === route('profile.edit')
                || $request->getUri() === route('logout')
            )
        ) {
            return redirect()->route('profile.edit');
        }

        if (
            Auth::user()
            && (!Auth::user()->hasVerifiedEmail())
            && !(
                $request->getUri() === route('profile.edit')
                || $request->getUri() === route('profile.resend-verify-email')
                || $request->getUri() === route('logout')
                || $request->getUri() === route('profile.verify-new-email')
                || str_starts_with($request->getUri(), route('verification.verify', ['id' => $request->id ?? 'id', 'hash' => $request->hash ?? 'hash']))
            )
        ) {
            return redirect()->route('profile.edit');
        }

        if (Schema::hasTable('projects')) {
            QueryLog::disable();
            Project::IsPublicated()
                ->where('status', '!=', 'finished')
                ->where('status', '!=', 'evaluation')
                ->isNotActive()
                ->update(['status' => DB::raw(
                    'if(`type` in (\'auction\', \'preliminary-interest\'), \'finished\', \'evaluation\')'
                )]);
        }

        (new TempProjectFileService())->clear();

        // promazani starych zaloh SQL - begin
        $cacheKey = 'QueryLogServiceProvider_register';
        $cacheDuration = 60 * 60 * 24;

        if (!Cache::has($cacheKey)) {
            Cache::put($cacheKey, true, $cacheDuration);

            $eightDaysAgo = Carbon::now()->subDays(8);
            $files = Storage::files('logs');
            foreach ($files as $file) {
                $filePath = storage_path('app/' . $file);
                $lastModified = File::lastModified($filePath);

                if (Carbon::createFromTimestamp($lastModified)->lessThan($eightDaysAgo)) {
                    Storage::delete($file);
                }
            }
        }
        // promazani starych zaloh SQL - end

        return $next($request);
    }
}
