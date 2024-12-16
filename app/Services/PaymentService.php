<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectShow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentService
{
    public function nextTryInSeconds($checkOnly = false)
    {
        $now = Carbon::now();

        // 1 x za minutu
        $after = 0;
        $uuid = 'check-payment-1-1-' . Str::uuid()->toString();
        $data = Cache::get('check-payment-1-1', []);
        $newData = [];
        foreach (array_keys($data) as $item) {
            if (Cache::has($item)) {
                $newData[$item] = true;
                $after = max($after, (60 - $now->diffInSeconds(Cache::get($item, Carbon::now()))));
            }
        }

        // 5 x za hodinu
        $after5 = 60 * 60;
        $uuid5 = 'check-payment-5-60-' . Str::uuid()->toString();
        $data = Cache::get('check-payment-5-60', []);
        $newData5 = [];
        foreach (array_keys($data) as $item) {
            if (Cache::has($item)) {
                $newData5[$item] = true;
                $after5 = min($after5, ((60 * 60) - $now->diffInSeconds(Cache::get($item, Carbon::now()))));
            }
        }

        //
        if (count($newData5) >= 5) {
            return $after5;
        } else if (count($newData) >= 1) {
            return $after;
        }

        if (!$checkOnly) {
            $newData[$uuid] = true;
            Cache::put('check-payment-1-1', $newData, 60);
            Cache::put($uuid, Carbon::now(), 60);

            $newData5[$uuid5] = true;
            Cache::put('check-payment-5-60', $newData5, (60 * 60));
            Cache::put($uuid5, Carbon::now(), (60 * 60));
        }

        return 0;
    }

    public function checkPrincipal($waitForPayment = false)
    {
        $tryingCount = 0;
        if ($waitForPayment) {
            $after = $this->nextTryInSeconds();
            if ($after > 0) {
                return $after;
            }
            $tryingCount = 3;
        }

        $dateFrom = Carbon::yesterday()->format('Y-m-d');
        $dateTo = Carbon::today()->format('Y-m-d');
        do {
            @$data = file_get_contents(
                sprintf(
                    'https://www.fio.cz/ib_api/rest/periods/%s/%s/%s/transactions.json',
                    env('FIO_TOKEN'),
                    $dateFrom,
                    $dateTo,
                )
            );

            $data = json_decode($data);

            if (!isset($data->accountStatement)) {
                $this->sendErrorEmail(1, serialize($data->accountStatement ?? '---'));
                return;
            }

            if (!isset($data->accountStatement->transactionList->transaction)) {
                $this->sendErrorEmail(2);
                return;
            }

            $transactions = $data->accountStatement->transactionList->transaction;
            if ($waitForPayment) {
                foreach ($transactions as $transaction) {
                    if (
                        ($transaction->column5?->value ?? null) === $waitForPayment
                        && Payment::where('pohyb_id', $transaction->column22->value)->count() === 0
                    ) {
                        $tryingCount = 0;
                        break;
                    }
                }
            }
            if ($tryingCount > 0) {
                sleep(5);
            }
            $tryingCount--;
        } while ($tryingCount > 0);

        foreach ($transactions as $transaction) {
            $data = [
                'pohyb_id' => [
                    'name' => 'ID pohybu',
                    'column' => 'column22',
                    'value' => null,
                ],
                'pokyn_id' => [
                    'name' => 'ID pokynu',
                    'column' => 'column17',
                    'value' => null,
                ],
                'datum' => [
                    'name' => 'Datum',
                    'column' => 'column0',
                    'value' => null,
                ],
                'castka' => [
                    'name' => 'Objem',
                    'column' => 'column1',
                    'value' => null,
                ],
                'mena' => [
                    'name' => 'Měna',
                    'column' => 'column14',
                    'value' => null,
                ],
                'protiucet' => [
                    'name' => 'Protiúčet',
                    'column' => 'column2',
                    'value' => null,
                ],
                'protiucet_kodbanky' => [
                    'name' => 'Kód banky',
                    'column' => 'column3',
                    'value' => null,
                ],
                'protiucet_nazevbanky' => [
                    'name' => 'Název banky',
                    'column' => 'column12',
                    'value' => null,
                ],
                'protiucet_nazevprotiuctu' => [
                    'name' => 'Název protiúčtu',
                    'column' => 'column10',
                    'value' => null,
                ],
                'protiucet_uzivatelska_identifikace' => [
                    'name' => 'Uživatelská identifikace',
                    'column' => 'column7',
                    'value' => null,
                ],
                'vs' => [
                    'name' => 'VS',
                    'column' => 'column5',
                    'value' => null,
                ],
                'zprava_pro_prijemce' => [
                    'name' => 'Zpráva pro příjemce',
                    'column' => 'column16',
                    'value' => null,
                ],
            ];

            $insert = [];
            $insert['user_id'] = null;
            $insert['project_id'] = null;

            if ($transaction->column1->value < 0) {
                continue;
            }

            foreach ($data as $column => $item) {
                if (!property_exists($transaction, $item['column'])) {
                    $this->sendErrorEmail(3, $item['column'] . "\n\n" . serialize($transaction));
                    return;
                }

                $insert[$column] = null;
                if ($column === 'vs') {
                    $insert[$column] = '-----';
                }

                if ($transaction->{$item['column']} === null) {
                    continue;
                }

                if ($transaction->{$item['column']}->name !== $item['name']) {
                    $this->sendErrorEmail(4, sprintf(
                        '%s - %s - %s',
                        $item['column'],
                        $item['name'],
                        $transaction->{$item['column']}->name
                    ));
                    return;
                }

                $data[$column]['value'] = $transaction->{$item['column']}->value;
                $insert[$column] = $transaction->{$item['column']}->value;
            }

            $insert['datum'] = substr($insert['datum'], 0, 10);

            $projectShow = ProjectShow::where('variable_symbol', $insert['vs'])->first();
            if ($projectShow) {
                $insert['user_id'] = $projectShow->user_id;
                $insert['project_id'] = $projectShow->project_id;
            }

            $exists = Payment::where('pohyb_id', $insert['pohyb_id'])->count();
            if ($exists) {
                continue;
            }

            Payment::create($insert);

            if ($projectShow) {
                $paymentSum = Payment::where('vs', $insert['vs'])->sum('castka');
                $project = Project::find($projectShow->project_id);
                $minimumPrincipal = (int)$project->minimum_principal;

                $projectShow->update([
                    'principal_sum' => $paymentSum,
                    'principal_paid' => ($paymentSum >= $minimumPrincipal),
                ]);

                (new ProjectService)->afterProncipalPayment($projectShow);
            }
        }
    }

    private function sendErrorEmail($id, $message = null)
    {
        $errorSubject = sprintf('Chyba platby (%s)', $id);
        $errorMessage = sprintf('Chyba platby (%s)', $id);
        if ($message) {
            $errorMessage = $message;
        }

        Mail::raw(
            $errorMessage,
            function ($mail) use ($errorSubject) {
                $mail->to(env('MAIL_TO_INFO2'))
                    ->subject($errorSubject);
            });
    }
}
