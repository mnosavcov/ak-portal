<?php

namespace App\Services\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LocalizationService extends Controller
{

    private const EXCLUDED_DIRS = [
        'node_modules',
        'database',
        'vendor',
        'tests',
        'bootstrap',
        'sportingsun.cz',
        'storage',
        'config',
        'public',
        'resources\css',
        'resources\js',
        'resources\lang',
        'resources\fonts',
        'resources\images',
    ];

    private const CHECK_EXCEPT = [
        'localization.',
        'validation.',
        'auth.failed',
        'auth.throttle',
    ];

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
                'sub' => ['__default__' => ['title' => __('localization.Basic')]]
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
                $languages[$lng]['sub'][$subtitle] = ['title' => __('localization.' . $subtitle)];
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

    public function loadMeta(bool $meta)
    {
        if (!$meta) {
            return false;
        }

        return require_once resource_path('data/localization-meta.php');
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
            $filename = $subFile->getPathname();
            if (!File::isFile($filename . '.bkp')) {
                File::copy($filename, $filename . '.bkp');
            }

            if (!File::isFile($filename . '.json')) {
                $translationsData = require $filename . '.bkp';
                File::replace($filename . '.json', json_encode($translationsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            $subtitle = explode('.', $subFile->getFilename())[0];
            $translations[$subtitle] = json_decode(File::get($filename . '.json'), true);
        }

        return $translations;
    }

    public function save($request, $lng, $sub)
    {
        if (env('LANG_ADMIN_READONLY', true)) {
            return;
        }

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

        File::replace($filename . '.bkp', json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        File::replace($filename, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
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

        if (!File::isFile($filename . '.json')) {
            $translationsData = require $filename . '.bkp';
            File::replace($filename . '.json', json_encode($translationsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $translations = json_decode(File::get($filename . '.json'), true);
        $translations[$request->index] = $request->translate;

        $content = "<?php\n\nreturn " . str_replace(['array (', ')', '  \''], ['[', ']', '    \''], var_export($translations, true)) . ";\n";

        File::replace($filename . '.json', json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        File::replace($filename . '.bkp', $content);
        File::replace($filename, $content);
    }

    public function getTestLng(): string
    {
        $filename = resource_path('lang/.setting');

        if (!File::isFile($filename)) {
            return '__default__';
        }

        return File::get($filename);
    }

    public function getFromLng(): string
    {
        $filename = resource_path('lang/.from');

        if (!File::isFile($filename)) {
            return '__default__';
        }

        return File::get($filename);
    }

    public function clearBkps()
    {
        $languages = $this->getLanguages();

        foreach ($languages as $lng => $items) {
            $filename = resource_path('lang/' . $lng . '/localization.php');
            if (!File::exists($filename)) {
                continue;
            }
            $filenameNew = resource_path('lang/' . $lng . '/localizationX.php');
            File::copy($filename, $filenameNew);
        }

        $files = File::allFiles(resource_path('lang'));
        $bkpFiles = collect($files)->filter(function ($file) {
            $extension = $file->getExtension();
            return $extension === 'bkp' || str_ends_with($file->getFilename(), '.php.json');
        });

        $bkpFiles->each(function ($file) {
            File::delete($file);
        });
    }

    public function setLocalizationLangs()
    {
        $languages = $this->getLanguages();

        foreach ($languages as $lng => $items) {
            $localizationData = [];
            $localizationDataNew = [];

            $filename = resource_path('lang/' . $lng . '/localization.php');
            $filenameNew = resource_path('lang/' . $lng . '/localizationX.php');
            if (File::exists($filenameNew)) {
                $localizationData = require $filenameNew;
            }

            foreach ($items['sub'] as $title => $item) {
                if ($title === 'localizationX') {
                    continue;
                }

                $use = ($title === '__default__' ? 'Basic' : $title);
                $localizationDataNew[$use] = $localizationData[$use] ?? '';
            }

            $content = "<?php\n\nreturn " . str_replace(['array (', ')', '  \''], ['[', ']', '    \''], var_export($localizationDataNew, true)) . ";\n";
            File::replace($filename, $content);
            File::delete($filenameNew);

            $this->searchFilesForText();
        }
    }

    function searchFilesForText()
    {
        $findItemAll = $this->findAllTranslatesPlaces();

        $files = File::allFiles(base_path());
        $results = [];
        $checks = [];
        foreach ($findItemAll as $findItem => $searchText) {
            $results[$findItem] = [];

            foreach (self::CHECK_EXCEPT as $except) {
                if (str_starts_with($findItem, $except) !== false) {
                    continue 2;
                }
            }

            $checks[$findItem] = true;
        }

        foreach ($files as $file) {
            $directories = explode(DIRECTORY_SEPARATOR, $file->getRelativePath());
            if (empty($directories[0])) {
                continue;
            }

            foreach (self::EXCLUDED_DIRS as $excludedDir) {
                if (str_starts_with($file->getRelativePath(), $excludedDir) !== false) {
                    continue 2;
                }
            }

            $lines = file($file->getRealPath());
            foreach ($findItemAll as $findItem => $searchText) {
                foreach ($lines as $lineNumber => $line) {
                    if (strpos($line, $searchText) !== false) {
                        if (array_key_exists($findItem, $checks)) {
                            unset($checks[$findItem]);
                        }
                        $results[$findItem][] = [
                            'path' => $file->getRelativePathname(),
                            'line' => $lineNumber + 1,
                        ];
                    }
                }
            }
        }

        if (!empty($checks)) {
            dump($checks);
        }

        File::replace(resource_path('data/localization-meta.php'), "<?php\n\nreturn " . str_replace(['array (', ')', '  \''], ['[', ']', '    \''], var_export($results, true)) . ";\n");

        return $results;
    }

    public function findAllTranslatesPlaces()
    {
        $findItemAll = [];

        $translates = $this->load('cs');
        foreach ($translates as $parentIndex => $items) {
            foreach ($items as $index => $item) {
                $findItem = $index;
                if ($parentIndex !== '__default__') {
                    $findItem = $parentIndex . '.' . $index;
                }

                $searchString = '__(\'' . $findItem . '\'';
                $findItemAll[$findItem] = $searchString;
            }
        }

        return $findItemAll;
    }
}
