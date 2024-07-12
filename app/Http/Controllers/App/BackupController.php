<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function __invoke(Request $request)
    {
        $client = new Client();
        $response = $client->request('GET', 'https://www.sportingsun.cz/pvtrusted/check.php');
        $responseContent = $response->getBody()->getContents();
        if ($responseContent !== 'success') {
            $errorText = 'Chyba backup pvtrusted.cz: ' . $responseContent;
            Mail::raw(
                $errorText,
                function ($mail) use ($errorText) {
                    $mail->to(env('MAIL_TO_INFO2'))
                        ->subject($errorText);
                });
        }

        $tables = DB::select('SHOW TABLES');

        $sql = '';
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            $insertData = DB::table($tableName)->get();

            foreach ($insertData as $row) {
                $values = array_map(function ($value, $index) {
                    $val = is_null($value) ? 'NULL' : DB::getPdo()->quote($value);
                    return $index === 'password' ? DB::getPdo()->quote('*****') : $val;
                }, (array)$row, array_keys((array)$row));

                $sql .= "INSERT INTO $tableName VALUES (" . implode(', ', $values) . ");\n";
            }

            $sql .= "\n\n";
        }

        $sql = Crypt::encryptString($sql);

        $filename = sprintf('temp/backup/backup-%s.sql', date('Ymd-His'));

        Storage::put($filename, $sql);
//        dump(Crypt::decryptString($sql));

        $fileTransferService = new FileTransferService();
        $responseContent = $fileTransferService->sendFile($filename, 'https://www.sportingsun.cz/pvtrusted/backup.php');

        if ($responseContent !== 'success') {
            $errorText = 'Chyba backup pvtrusted.cz: ' . $responseContent;
            Mail::raw(
                $errorText,
                function ($mail) use ($errorText) {
                    $mail->to(env('MAIL_TO_INFO2'))
                        ->subject($errorText);
                });
        } elseif(date('H') === '12') {
            $errorText = 'Backup pvtrusted.cz: ' . $responseContent;
            Mail::raw(
                $errorText,
                function ($mail) use ($errorText) {
                    $mail->to(env('MAIL_TO_INFO2'))
                        ->subject($errorText);
                });
        }
    }
}

class FileTransferService
{
    public function sendFile($filePath, $targetUrl)
    {
        $client = new Client();
        $response = $client->request('POST', $targetUrl, [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => Storage::get($filePath),
                    'filename' => basename($filePath)
                ],
            ],
        ]);

        return $response->getBody()->getContents();
    }
}
