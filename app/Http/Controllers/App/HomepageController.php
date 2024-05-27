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
        $currentDateTime = Carbon::now()->subHours(-1);

        $currentHour = $currentDateTime->hour;
        $targetHour = $targetDateTime->hour;
        $hourDifference = $targetHour - $currentHour - 2;
        if ($hourDifference < 0) {
            $hourDifference += 24;
        }

        $currentMinute = $currentDateTime->minute;
        $targetMinute = $targetDateTime->minute;
        $minutDifference = $targetMinute - $currentMinute - 1;
        if ($minutDifference < 0) {
            $minutDifference += 60;
        }

        $currentSecond = $currentDateTime->second;
        $targetSecond = $targetDateTime->second;
        $secondDifference = $targetSecond - $currentSecond - 1;
        if ($secondDifference < 0) {
            $secondDifference += 60;
        }

        return view('homepage-temporary', [
            'date' => $targetDateTime,
            'days' => $targetDateTime->diffInDays(),
            'hours' => $hourDifference,
            'minutes' => $minutDifference,
            'seconds' => $secondDifference,
        ]);
    }

    public function saveEmail(Request $request)
    {
        DB::table('emails')->insert(['email' => $request->email]);
        return response()->json(['status' => 'ok']);
    }
}
