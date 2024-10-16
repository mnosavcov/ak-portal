<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;

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
}
