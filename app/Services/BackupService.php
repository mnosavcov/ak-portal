<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class BackupService
{
    public function backup2Table($model, $crypt = false, $anonymizeColumns = ['password'])
    {
        $data = $model->getOriginal();
        foreach($anonymizeColumns as $anonymizeColumn) {
            if(array_key_exists($anonymizeColumn, $data)) {
                $data[$anonymizeColumn] = '***';
            }
        }

        $data = json_encode($data);

        if($crypt) {
            $data = Crypt::encryptString($data);
        }

        // crypt
        DB::table('backups')->insert([
            'table' => $model->getTable(),
            'column_id' => $model->id,
            'data' => $data,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
