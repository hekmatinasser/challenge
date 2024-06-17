<?php

namespace App\Repositories\Transactions;

use App\Enumerations\Treansaction\Type;
use App\Jobs\DepositSMSJob;
use App\Jobs\WithdrawSMSJob;
use App\Models\CreditCard;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TransactionRepository implements TransactionRepositoryInterface
{

    /**
     * @throws ValidationException
     */
    public function transfer(array $data)
    {
        $result = $this->updateBalance($data);

        WithdrawSMSJob::dispatch($result['source'], $data);
        DepositSMSJob::dispatch($result['destination'], $data);
    }

    private function getCreditCard($creditCardNumber): ?CreditCard
    {
        /** @var CreditCard $card */
        $card = CreditCard::query()
            ->with('bankAccount')
            ->where('number', $creditCardNumber)
            ->first();

        return $card;
    }

    /**
     * @throws ValidationException
     */
    private function updateBalance(array $data): array
    {

        DB::beginTransaction();
        try {
            /** @var CreditCard $source */
            $source = $this->getCreditCard($data['source_card_number']);

            /** @var CreditCard $destination */
            $destination = $this->getCreditCard($data['destination_card_number']);

            $balance = $source->bankAccount->balance;

            if ($balance < ($data['amount'] + config('bank.transaction.fee'))) {
                throw ValidationException::withMessages(['amount' => 'balance is not enough']);
            }

            $sourceNewBalance = $source->bankAccount->balance - ($data['amount'] + config('bank.transaction.fee'));
            $source->bankAccount()->lockForUpdate()->update(['balance' => $sourceNewBalance]);

            $sourceTransaction = Transaction::query()->create([
                'credit_card_id' => $source->id,
                'type'           => Type::WITHDRAW,
                'amount'         => -$data['amount']
            ]);
            TransactionLog::query()->create([
                'transaction_id'      => $sourceTransaction->id,
                'source_card_id'      => $source->id,
                'destination_card_id' => $destination->id
            ]);
            //fee
            Transaction::query()->create([
                'credit_card_id' => $source->id,
                'type'           => Type::FEE,
                'amount'         => -config('bank.transaction.fee')
            ]);


            $destinationNewBalance = $destination->bankAccount->balance + ($data['amount']);
            $destination->bankAccount()->lockForUpdate()->update(['balance' => $destinationNewBalance]);
            $destinationTransaction = Transaction::query()->create([
                'credit_card_id' => $destination->id,
                'type'           => Type::DEPOSIT,
                'amount'         => $data['amount']
            ]);
            TransactionLog::query()->create([
                'transaction_id'      => $destinationTransaction->id,
                'source_card_id'      => $source->id,
                'destination_card_id' => $destination->id
            ]);


            DB::commit();

            Log::info('Transaction was success', [
                'source'      => $source->refresh()->toArray(),
                'destination' => $destination->refresh()->toArray()
            ]);

            return [
                'source'      => $source->refresh(),
                'destination' => $destination->refresh()
            ];

        } catch (Exception $e) {
            Log::critical('Transaction Failed', ['message' => $e->getMessage(), 'code' => $e->getCode(), 'data' => $data]);
            DB::rollBack();

            throw ValidationException::withMessages(['transfer' => 'Transaction failed']);
        }
    }

    public function topUsers()
    {
        $transaction = Transaction::query()->with('creditCard.user')
            ->where('type', '!=', Type::FEE)
            ->where('transactions.created_at', '>=', now()->subMinutes(100));


        $users = User::query()
            ->join('credit_cards', 'credit_cards.user_id', '=', 'users.id')
            ->joinSub( $transaction, 'transactions', 'transactions.credit_card_id', '=', 'credit_cards.id')
            ->groupBy('users.id')
            ->selectRaw('users.id, count(*) as count')
            ->orderBy('count', 'desc')
            ->take(3)
            ->get();

        return $users->map(function ($user) {
            $transactions = $user->transactions()
                ->where('type', '!=', Type::FEE)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return [
                'user'         => $user,
                'transactions' => $transactions
            ];
        });
    }

}

