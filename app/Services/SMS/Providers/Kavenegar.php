<?php

namespace App\Services\SMS\Providers;

use App\Services\SMS\Contracts\SmsInterface;
use Illuminate\Support\Facades\Http;

class Kavenegar implements SmsInterface
{
    public static function send(string $receiver, array $data)
    {

        Http::get(env('KAVENEGAR_URL'), [
            'receptor' => $receiver,
            'token' => $data['amount'],
            'token2' => $data['balance'],
            'template' => 'changeBalance',
        ]);

    }
}
