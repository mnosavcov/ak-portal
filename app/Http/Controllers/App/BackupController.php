<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function __invoke(Request $request)
    {
        echo "dělám zálohu";

        $tables = DB::select('SHOW TABLES');

        $sql = '';
        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            $insertData = DB::table($tableName)->get();

            foreach ($insertData as $row) {
                $values = array_map(function ($value, $index) {
                    $val = is_null($value) ? 'NULL' : DB::getPdo()->quote($value);
                    return $index === 'password' ? DB::getPdo()->quote('*****') : $val;
                }, (array) $row, array_keys((array)$row));

                $sql .= "INSERT INTO $tableName VALUES (" . implode(', ', $values) . ");\n";
            }

            $sql .= "\n\n";
        }

        $sql = Crypt::encryptString($sql);

        $ftpService = new FTPService();
        dd($ftpService);
        dump(Crypt::decryptString($sql));
    }
}

class FTPService
{
    protected $connection;

    public function __construct()
    {
        $this->connect();
    }

    public function connect()
    {
        // Připojení k FTP serveru
        $this->connection = ftp_connect(env('FTP_HOST'), env('FTP_PORT', 21));

        if (!$this->connection) {
            throw new \Exception('Could not connect to FTP server');
        }

        // Přihlášení k FTP serveru
        $login = ftp_login($this->connection, env('FTP_USERNAME'), env('FTP_PASSWORD'));

        if (!$login) {
            throw new \Exception('Could not login to FTP server');
        }

        // Nastavení pasivního režimu
        if (env('FTP_PASSIVE', true)) {
            ftp_pasv($this->connection, true);
        }
    }

    public function upload($localFile, $remoteFile)
    {
        $remoteFile = env('FTP_ROOT', '') . '/' . $remoteFile;

        // Nahrání souboru na FTP server
        if (!ftp_put($this->connection, $remoteFile, $localFile, FTP_BINARY)) {
            throw new \Exception("Could not upload file to FTP server");
        }
    }

    public function download($remoteFile, $localFile)
    {
        $remoteFile = env('FTP_ROOT', '') . '/' . $remoteFile;

        // Stažení souboru z FTP serveru
        if (!ftp_get($this->connection, $localFile, $remoteFile, FTP_BINARY)) {
            throw new \Exception("Could not download file from FTP server");
        }
    }

    public function __destruct()
    {
        // Zavření připojení k FTP serveru
        if ($this->connection) {
            ftp_close($this->connection);
        }
    }
}
