<?php

namespace App\Http\Controllers;

use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;

class EmailController extends Controller
{
    public function sendFromQueue(EmailService $emailService)
    {
        $connection = Queue::connection();

        $i = 3;
        while($i > 0) {
            $i--;
            $job = $connection->pop('default');

            if ($job) {
                $job->fire();
                sleep(10);
            }
        }
    }

}
