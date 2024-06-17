<?php

namespace App\Services\SMS\Contracts;

use App\Models\CreditCard;

interface SmsInterface
{
    public static function send(string $receiver, array $data);
}
