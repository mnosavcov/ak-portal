<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    /**
     * Display the user's profile form.
     */
    public function testBankidVerify($id = null)
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $y = \App\Models\UserVerifyService::orderBy('id', 'desc');
        if ($id > 0) {
            $y = $y->where('id', $id);
        }
        $y = $y->first();
        if (!$y) {
            dd('záznam nenalezen');
        }

        dump($y->id);

        $d = json_decode(Crypt::decryptString($y->data));
        dump([
            '$d->hashParsed->access_token' => $d->hashParsed->access_token ?? '-',
            '$d->hashParsed->id_token' => $d->hashParsed->id_token ?? '-',
        ]);

        $x = explode('.', $d->hashParsed->access_token ?? '..');
        dump($x);

        dump(json_decode(base64_decode($x[0])));
        dump(json_decode(base64_decode($x[1])));
        dump($x[2]);


        $x = explode('.', $d->hashParsed->id_token ?? '..');
        dump($x);

        dump(json_decode(base64_decode($x[0])));
        dump(json_decode(base64_decode($x[1])));
        dump($x[2]);
    }

    public function testUserBackup($id)
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $uzivatelBkp = DB::table('backups')
            ->where('table', 'users')
            ->find($id);

        if (!$uzivatelBkp) {
            dd('záznam nenalezen');
        }

        $data = Crypt::decryptString($uzivatelBkp->data);

        dd([
            'uživatel ID' => $uzivatelBkp->column_id,
            'změnil' => $uzivatelBkp->user_id,
            'data' => json_decode($data),
        ]);
    }
}
