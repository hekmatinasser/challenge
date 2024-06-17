<?php

namespace App\Jobs;

use App\Models\CreditCard;
use App\Services\SMS\SMS;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DepositSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly CreditCard $creditCard, private readonly array $data)
    {
    }


    public function handle(): void
    {
        $mobile = $this->creditCard->bankAccount->user->mobile;
        $message = sprintf("Deposit : +%s , current balance is: %s", $this->data['amount'], $this->creditCard->bankAccount->balance);

        SMS::send($mobile, $message);
    }
}
