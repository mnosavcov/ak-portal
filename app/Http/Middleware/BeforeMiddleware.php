<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
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
                        '/zpracovani-osobnich-udaju',
                    ]
                ) && !$date->isPast()
            ) {
                return redirect()->route('homepage');
            }
        }

        Project::IsPublicated()->where('status', '!=', 'finished')->isNotActive()->update(['status' => 'finished']);
        return $next($request);
    }
}
