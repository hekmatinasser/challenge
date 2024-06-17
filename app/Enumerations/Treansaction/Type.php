<?php

namespace App\Enumerations\Treansaction;

enum Type: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
    case FEE = 'fee';
}
