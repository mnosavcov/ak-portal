<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Services\UsersService;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (!empty(env('DATE_PUBLISH'))) {
            $date = Carbon::create(env('DATE_PUBLISH'));
            $date->subHours(+2);

            if (!in_array($request->getRequestUri(),
                    [
                        '/',
                        '/save-email',
                        '/zasady-zpracovani-osobnich-udaju',
                        '/projects/add',
                        '/projects/save',
                        '/login',
                        '/logout',
                        '/admin',
                        '/admin/projects',
                    ]
                ) && !$date->isPast()
                && !str_starts_with($request->getRequestUri(), '/admin/projects')
                && !str_starts_with($request->getRequestUri(), '/projects/')
                && !str_starts_with($request->getRequestUri(), '/gallery/')
                && !str_starts_with($request->getRequestUri(), '/file/')
            ) {
                return redirect()->route('homepage');
            }
        }

        if (!empty(env('DATE_PUBLISH_PAST')) && $request->getRequestUri() !== '/') {
            return redirect()->route('homepage');
        }

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
                || str_starts_with($request->getUri(), route('verification.notice'))
            )
        ) {
            return redirect()->route('profile.edit');
        }

        Project::IsPublicated()->where('status', '!=', 'finished')->isNotActive()->update(['status' => 'evaluation']);
        return $next($request);
    }
}
