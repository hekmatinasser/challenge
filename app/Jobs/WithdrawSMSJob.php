<?php

namespace App\Jobs;

use App\Models\CreditCard;
use App\Services\SMS\SMS;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WithdrawSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly CreditCard $creditCard, private readonly array $data)
    {
    }


    public function handle(): void
    {
        $mobile = $this->creditCard->bankAccount->user->mobile;
        $message = sprintf("Withdraw+transaction fee: %s + %s , current balance is: %s", $this->data['amount'], config('bank.transaction.fee'), $this->creditCard->bankAccount->balance);

        SMS::send($mobile, $message);
    }
}
