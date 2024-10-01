<?php

namespace App\Services\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class LocalizationService extends Controller
{
    public function getLanguages(): array
    {
        if (!File::isDirectory(resource_path('lang'))) {
            return [];
        }

        $files = File::allFiles(resource_path('lang'));

        $jsonFiles = collect($files)->filter(function ($file) {
            return preg_match('/^[^.]+\.json$/', $file->getFilename());
        });

        $languages = [];
        foreach ($jsonFiles as $file) {
            $lng = explode('.', $file->getFilename())[0];
            $languages[$lng] = [
                'title' => $lng,
                'sub' => ['__default__' => ['title' => 'Basic']]
            ];

            if (!File::isDirectory(resource_path('lang/' . $lng))) {
                continue;
            }

            $subfiles = File::allFiles(resource_path('lang/' . $lng));
            $jsonSubFiles = collect($subfiles)->filter(function ($subfiles) {
                return preg_match('/^[^.]+\.php$/', $subfiles->getFilename());
            });

            foreach ($jsonSubFiles as $subFile) {
                $subtitle = explode('.', $subFile->getFilename())[0];
                $languages[$lng]['sub'][$subtitle] = ['title' => $subtitle];
            }
        }

        return $languages;
    }

    public function load($lng): array
    {
        $translates = ['__default__' => $this->loadDefault($lng)];
        $translates += $this->getSubs($lng);

        return $translates;
    }

    private function loadDefault($lng)
    {
        $filename = resource_path('lang/' . $lng . '.json');

        if (!File::isFile($filename)) {
            return [];
        }

        $translations = json_decode(File::get($filename), true);

        return $translations;
    }

    private function getSubs($lng)
    {
        $dirname = resource_path('lang/' . $lng);

        if (!File::isDirectory($dirname)) {
            return [];
        }

        $translations = [];

        $subfiles = File::allFiles(resource_path('lang/' . $lng));
        $jsonSubFiles = collect($subfiles)->filter(function ($subfiles) {
            return preg_match('/^[^.]+\.php$/', $subfiles->getFilename());
        });

        foreach ($jsonSubFiles as $subFile) {
            $subtitle = explode('.', $subFile->getFilename())[0];
            $translations[$subtitle] = require $subFile->getPathname();
        }

        return $translations;
    }

    public function save($request, $lng, $sub)
    {
        if ($sub === '__default__') {
            $this->saveDefault($request, $lng);
            return;
        }

        $this->saveSub($request, $lng, $sub);
    }

    private function saveDefault($request, $lng)
    {
        $filename = resource_path('lang/' . $lng . '.json');

        if (!File::isFile($filename)) {
            return;
        }

        $translations = json_decode(File::get($filename), true);
        $translations[$request->index] = $request->translate;

        File::replace($filename . '.bkp', json_encode($translations, JSON_PRETTY_PRINT));
        File::replace($filename, json_encode($translations, JSON_PRETTY_PRINT));
    }

    private function saveSub($request, $lng, $sub)
    {
        $filename = resource_path('lang/' . $lng . '/' . $sub . '.php');

        if (!File::isFile($filename)) {
            return;
        }

        if (!File::isFile($filename . '.bkp')) {
            File::copy($filename, $filename . '.bkp');
        }

        $translations = require $filename . '.bkp';
        $translations[$request->index] = $request->translate;

        $content = "<?php\n\nreturn " . str_replace(['array (', ')', '  \''], ['[', ']', '    \''], var_export($translations, true)) . ";\n";

        File::replace($filename . '.bkp', $content);
        File::replace($filename, $content);
    }

    public function getTestLng()
    {
        $filename = resource_path('lang/.setting');

        if (!File::isFile($filename)) {
            return '__default__';
        }

        return File::get($filename);
    }
}
