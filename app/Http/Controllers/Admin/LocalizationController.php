<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\LocalizationService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

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
            'translate' => htmlspecialchars($localizationService->loadLong($path))
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
}
