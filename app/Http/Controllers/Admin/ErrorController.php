<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ErrorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ErrorController extends Controller
{
    public function index(ErrorService $errorService)
    {
        $errors = $errorService->getErrors();
        return view('admin.error.index', ['errors' => $errors]);
    }

    public function load($filename)
    {
        $error = preg_split('/\R/', Storage::disk('locallog')->get($filename));

        foreach ($error as $index => $content) {
            $error[$index] = '<div class="admin-error-row">' . e($content) . '</div>';
        }

        $error = implode('', $error);

        return [
            'status' => 'success',
            'content' => $error,
        ];
    }

    public function archive(Request $request)
    {
        $filename = $request->post('filename');
        Storage::disk('locallog')->move($filename, 'archive/' . $filename);

        return ['status' => 'success'];
    }
}
