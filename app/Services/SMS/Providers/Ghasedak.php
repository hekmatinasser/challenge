<?php

namespace App\Services\SMS\Providers;

use App\Services\SMS\Contracts\SmsInterface;
use Illuminate\Support\Facades\Http;

class Ghasedak implements SmsInterface
{

    public static function send(string $receiver, array $data)
    {
        $prepare = [
            'receptor'   => $receiver,
            'linenumber' => '30005088',//free

        ];

        Http::withHeaders(['apikey' => env('GHASEDAK_API_KEY')])
            ->post(env('GHASEDAK_URL'), $prepare);

    }
}
