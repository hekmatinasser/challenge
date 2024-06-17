<?php

namespace App\Services\SMS;

use App\Services\SMS\Providers\Ghasedak;
use App\Services\SMS\Providers\Kavenegar;

class SMS
{

    public static function send(string $receiver, array $data): void
    {
        match (env('SMS_PROVIDER')) {
            'kavenegar' => Kavenegar::send($receiver, $data),
            'ghasedak' => Ghasedak::send($receiver, $data),
        };
    }
}
