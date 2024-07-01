<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomepageController extends Controller
{

    public function index(Request $request)
    {
        $date = Carbon::create(env('DATE_PUBLISH'));
        $currentDateTime = clone $date;
        $currentDateTime->subHours(+2);

        if (!$currentDateTime->isPast()) {
            return $this->temporary();
        }

        if (!empty(env('DATE_PUBLISH_PAST'))) {
            return view('homepage-temporary-x');
        }

        //

        $projectAll = Project::isPublicated()->forDetail()->get();

        $projects = [
            'Nejnovější projekty' => [
                'selected' => '1',
                'titleCenter' => true,
                'data' => [
                    '1' => $projectAll,
                ],
            ]
        ];

        return view('homepage', [
            'projects' => $projects,
            'projectsListButtonAll' => ['title' => 'Zobrazit vše', 'url' => Route('projects.index')]
        ]);
    }

    public function temporary()
    {
        $targetDateTime = Carbon::create(env('DATE_PUBLISH'));
        $currentDateTime = Carbon::now()->subHours(-2);

        $diff = $targetDateTime->diff($currentDateTime);

        return view('homepage-temporary', [
            'date' => $targetDateTime,
            'days' => $diff->format('%d'),
            'hours' => $diff->format('%h'),
            'minutes' => $diff->format('%i'),
            'seconds' => $diff->format('%s'),
        ]);
    }

    public function saveEmail(Request $request)
    {
        DB::table('emails')->insert(['email' => $request->email]);
        return response()->json(['status' => 'ok']);
    }

    public function kontakt()
    {
        return view('app.kontakt');
    }

    public function ajaxForm(Request $request)
    {
        sleep(2);
    }
}
