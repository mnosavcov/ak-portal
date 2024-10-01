<?php

namespace App\Services\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SplFileObject;

class ErrorService extends Controller
{
    const LOG_PATH = 'laravel.log';

    const EXCEPTION_FILES = ['.gitignore', 'laravel.log'];

    public function getErrors(): array
    {
        $this->parseErrorLog();

        $errors = Storage::disk('locallog')->files();
        return array_filter($errors, function ($errors) {
            return !in_array(basename($errors), self::EXCEPTION_FILES);
        });
    }

    private function parseErrorLog(): void
    {
        $newFile = Str::uuid() . '.log';

        if (!Storage::disk('locallog')->exists(self::LOG_PATH)) {
            return;
        }

        Storage::disk('locallog')->move(self::LOG_PATH, $newFile);

        try {
            $file = new SplFileObject(Storage::disk('locallog')->path($newFile));

            $errorFileName = Str::slug(date('Y-m-d')) . '-XXXXXX-' . Str::uuid() . '.log';
            while (!$file->eof()) {
                $row = $file->current();
                if (preg_match('/^\[(\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d)] /', $row, $matches)) {
                    $errorFileName = Str::slug($matches[1]) . '-' . Str::uuid() . '.log';
                }

                Storage::disk('locallog')->append($errorFileName, $row, null);

                $file->next();
            }
        } catch (Exception $e) {
            return;
        }

        Storage::disk('locallog')->delete($newFile);
    }
}
