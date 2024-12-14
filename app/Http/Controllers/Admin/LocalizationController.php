<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\QueuedEmail;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\ProjectTag;
use App\Services\Admin\LocalizationService;
use App\Services\ProjectService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LocalizationController extends Controller
{
    public function index(LocalizationService $localizationService): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $defaultConfig = include config_path('app.php');
        $defaultLocale = $defaultConfig['locale'];

        $languages = $localizationService->getLanguages();
        return view(
            'admin.localization.index',
            [
                'languages' => $languages,
                'is_test' => File::isFile(resource_path('lang/.test')),
                'default_language' => $defaultLocale,
                'test_language' => $localizationService->getTestLng(),
                'from_language' => $localizationService->getFromLng(),
            ]
        );
    }

    public function load(LocalizationService $localizationService, $lng, int $meta = 0): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'translates' => $localizationService->load($lng),
            'meta' => $localizationService->loadMeta((bool)$meta)
        ]);
    }

    public function loadLong(LocalizationService $localizationService, $lng, $path): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'translate' => $localizationService->loadLong($path),
            'translateSubject' => $localizationService->loadLongEmailSubject($path, $lng)
        ]);
    }

    public function save(Request $request, LocalizationService $localizationService, $lng, $sub): JsonResponse
    {
        $localizationService->save($request, $lng, $sub);

        return response()->json([
            'status' => 'success',
            'translates' => $localizationService->load($lng),
        ]);
    }

    public function saveLong(Request $request, LocalizationService $localizationService, $lng, $path): JsonResponse
    {
        $localizationService->saveLong($request, $lng, $path);

        return response()->json([
            'status' => 'success',
            'translate' => $localizationService->loadLong($path),
            'translateSubject' => $localizationService->loadLongEmailSubject($path, $lng)
        ]);
    }

    public function setTest($bool): JsonResponse
    {
        if ((int)$bool === 1 && !File::isFile(resource_path('lang/.test'))) {
            File::put(resource_path('lang/.test'), '');
        }

        if ((int)$bool !== 1 && File::isFile(resource_path('lang/.test'))) {
            File::delete(resource_path('lang/.test'));
        }

        return response()->json([
            'status' => 'success',
            'is_test' => File::isFile(resource_path('lang/.test')),
        ]);
    }

    public function setTestLng(LocalizationService $localizationService, $lng): JsonResponse
    {
        if ($lng === '__default__' && File::isFile(resource_path('lang/.setting'))) {
            File::delete(resource_path('lang/.setting'));
        }

        if ($lng !== '__default__') {
            File::replace(resource_path('lang/.setting'), $lng);
        }

        return response()->json([
            'status' => 'success',
            'test_language' => $localizationService->getTestLng(),
        ]);
    }

    public function setFromLng(LocalizationService $localizationService, $lng): JsonResponse
    {
        if ($lng === '__default__' && File::isFile(resource_path('lang/.from'))) {
            File::delete(resource_path('lang/.from'));
        }

        if ($lng !== '__default__') {
            File::replace(resource_path('lang/.from'), $lng);
        }

        return response()->json([
            'status' => 'success',
            'from_language' => $localizationService->getFromLng(),
        ]);
    }

    public function preview(LocalizationService $localizationService, $lng, $template)
    {
        App::setLocale($lng);

        $findClass = $localizationService->findMailClassByTemplate($template);
        $className = $findClass['className'];

        $user = auth()->user();

        $notification = new $className();
        $mailMessage = $notification->toMail($user)->markdown('vendor.email');

        echo '<h2 style="color: #000000; font-weight: normal; padding-top: 15px;"><span style="color: #888888; font-weight: bold;">Subject: </span>' . $mailMessage->subject . '</h2>';
        echo $mailMessage->render();
    }

    public function sendTest(LocalizationService $localizationService, $lng, $template)
    {
        App::setLocale($lng);

        $findClass = $localizationService->findMailClassByTemplate($template);
        $className = $findClass['className'];

        auth()->user()->notify(new $className());

        return response()->json(['status' => 'success']);
    }

    public function sendTemplateTest(Request $request, LocalizationService $localizationService)
    {
        $localizationService->saveLong(
            $request,
            null,
            base64_encode(Crypt::encryptString('template-mail:' . resource_path('views/app/temp/test-mail-text.blade.php'))),
            false
        );

        $data = [];
        $data['subject'] = $request->post('subject');
        $data['view'] = 'app.temp.test-mail';
        $data['text'] = 'app.temp.test-mail-text';

        $project = new Project();
        $project->title = 'Název projektu (test)';
        $project->short_info = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';
        $project->type = array_keys(Category::getCATEGORIES())[0];
        $project->subject_offer = array_keys(ProjectService::getSUBJECT_OFFERS())[0];
        $project->location_offer = array_keys(ProjectService::getLOCATION_OFFERS()[$project->subject_offer])[0];
        $project->end_date = Carbon::now()->addDays(7);
        $tags = collect([
            new ProjectTag(['title' => 'Tag 1']),
            new ProjectTag(['title' => 'Tag 2']),
            new ProjectTag(['title' => 'Tag 3']),
        ]);
        $project->setRelation('tags', $tags);

        $document = new ProjectFile();
        $document->filename = 'new-file.pdf';

        $data['subject'] = Str::replace('{{ $project->title }}', $project->title, $data['subject']);

        Mail::to(auth()->user()->email, trim(auth()->user()->username))->send(new QueuedEmail($data, [
            'project' => $project,
            'document' => $document,
        ]));

        return response()->json(['status' => 'success']);
    }
}
