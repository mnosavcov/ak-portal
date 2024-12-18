<?php

namespace App\Services\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

    private const EXCEPT_PATTH_TOL_LOCALIZATION_JS = [
        'template-mail-subject',
        'localizationX'
    ];

    public function getLanguages($readCountNeprelozeno = true): array
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
            if ($readCountNeprelozeno) {
                $data = $this->load($lng);
            }

            $languages[$lng] = [
                'title' => $lng,
                'category' => [
                    '__default__' => [
                        'title' => __('localization.Basic'),
                        'countNeprelozeno' => $this->countNeprelozenoKategorie($data['__default__'] ?? []),
                    ]
                ]
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
                if (in_array($subtitle, self::EXCEPT_PATTH_TOL_LOCALIZATION_JS)) {
                    continue;
                }
                $languages[$lng]['category'][$subtitle] = [
                    'title' => __('localization.' . $subtitle),
                    'countNeprelozeno' => $this->countNeprelozenoKategorie($data[$subtitle] ?? []),
                ];
            }

            $longTexts = $this->searchLongText($lng, 'long-text-');
            foreach ($longTexts as $longText) {
                $languages[$lng]['category'][$longText['category']] = [
                    'title' => __('localization.' . $longText['category']),
                    'pathname' => $longText['pathname'],
                    'countNeprelozeno' => Str::length(trim($this->loadLong($longText['pathname']))) > 0 ? 0 : 1,
                ];
            }

            $templateEmail = $this->searchLongText($lng, 'template-mail-', '/emails');
            foreach ($templateEmail as $longText) {
                $languages[$lng]['category'][$longText['category']] = [
                    'title' => __('localization.' . $longText['category']),
                    'pathname' => $longText['pathname'],
                    'countNeprelozeno' =>
                        (Str::length(trim($this->loadLong($longText['pathname']))) > 0 ? 0 : 1)
                        + (Str::length(trim($data['template-mail-subject'][Str::replaceStart('template-mail-', '', $longText['category'])] ?? '')) > 0 ? 0 : 1)
                    ,
                ];
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

    public function loadLong($path)
    {
        [$type, $filepath] = explode(':', Crypt::decryptString(base64_decode($path)), 2);
        $filepath = realpath($filepath);
        if (!File::isFile($filepath)) {
            return '';
        }

        return trim(File::get($filepath));
    }

    public function loadLongEmailSubject($path, $lng)
    {
        [$type, $filepath] = explode(':', Crypt::decryptString(base64_decode($path)), 2);
        if($type !== 'template-mail') {
            return null;
        }
        $template = Str::replaceEnd('-text.blade.php', '', $filepath);
        $template = explode('/', $template);
        $template = $template[count($template) - 1];
        $template = explode('\\', $template);
        $template = $template[count($template) - 1];

        $data = $this->load($lng);

        return $data['template-mail-subject'][$template] ?? '';
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

    public function saveLong($request, $lng, $path, $translateRealPath = true)
    {
        if (env('LANG_ADMIN_READONLY', true)) {
            return;
        }

        [$type, $filepath] = explode(':', Crypt::decryptString(base64_decode($path)), 2);
        if ($translateRealPath) {
            $filepath = realpath($filepath);
        }
        File::replace($filepath, $request->post('translateText') . "\n");

        if ($type === 'template-mail') {
            $filepathTemplate = Str::replaceLast('-text.blade.php', '.blade.php', $filepath);
            File::replace($filepathTemplate, '<x-email-layout :unsubscribeUrl="$unsubscribeUrl ?? null">' . "\n" . $request->post('translateHtml') . "\n" . '</x-email-layout>' . "\n");
            $this->saveSub($request, $lng, 'template-mail-subject');
        }

        return true;
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

        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";

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
        $languages = $this->getLanguages(false);

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
        $languages = $this->getLanguages(false);

        foreach ($languages as $lng => $items) {
            $localizationData = [];
            $localizationDataNew = [];

            $filename = resource_path('lang/' . $lng . '/localization.php');
            $filenameNew = resource_path('lang/' . $lng . '/localizationX.php');
            if (File::exists($filenameNew)) {
                $localizationData = require $filenameNew;
            }

            foreach ($items['category'] as $title => $item) {
                if (in_array($title, self::EXCEPT_PATTH_TOL_LOCALIZATION_JS)) {
                    continue;
                }

                $use = ($title === '__default__' ? 'Basic' : $title);
                $localizationDataNew[$use] = $localizationData[$use] ?? '';
            }

            $content = "<?php\n\nreturn " . var_export($localizationDataNew, true) . ";\n";
            File::replace($filename, $content);
            File::delete($filenameNew);

            $this->searchFilesForText();
        }
    }

    private function searchFilesForText()
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

        File::replace(resource_path('data/localization-meta.php'), "<?php\n\nreturn " . var_export($results, true) . ";\n");

        return $results;
    }

    private function findAllTranslatesPlaces()
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

    public function findMailClassByTemplate($template)
    {
        $finded = [];

        $allClasses = require base_path('vendor/composer/autoload_classmap.php');
        $findTemplate = '/' . Str::replaceFirst('mail-', '', $template) . '.php';
        foreach ($allClasses as $className => $path) {
            if (Str::endsWith($path, $findTemplate)) {
                $content = File::get($path);
                if (!Str::contains($content, $template)) {
                    continue;
                }

                $finded = [
                    'path' => $path,
                    'className' => $className,
                ];
                break;
            }
        }

        if (empty($finded)) {
            throw new Exception('Mail class "' . Str::replaceFirst('mail-', '', $template) . '" not found');
        }

        return $finded;
    }

    private function countNeprelozenoKategorie($data)
    {
        $count = 0;

        foreach ($data as $key => $value) {
            if (empty(trim($value ?? ''))) {
                $count++;
            }
        }
        return $count;
    }

    private function searchLongText($lng, $categoryPrefix, $subDir = '')
    {
        $ret = [];
        $files = File::files(resource_path('views/lang/' . $lng . $subDir));
        foreach ($files as $file) {
            if ($categoryPrefix === 'template-mail-') {
                if (!Str::endsWith($file->getFilename(), '-text.blade.php')) {
                    continue;
                }
            }

            if (!Str::endsWith($file->getFilename(), '.blade.php')) {
                continue;
            }

            $ret[] = [
                'category' => $categoryPrefix . Str::replaceLast('-text', '', explode('.', $file->getFilename())[0]),
                'pathname' => base64_encode(Crypt::encryptString(Str::replaceLast('-', ':', $categoryPrefix) . $file->getPathname())),
            ];
        }

        return $ret;
    }
}
