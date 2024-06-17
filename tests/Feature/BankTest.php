<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CreditCard;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BankTest extends TestCase
{
    use DatabaseTransactions;

    const URL = "/api/transfer";

    public function test_post_empty(): void
    {
        $this->postJson(self::URL)->assertStatus(422);
    }

    public function test_transfer_money()
    {

        //---------------------
        //      prepare Data
        //---------------------
        /** @var CreditCard $creditCard */
        $sourceCreditCard = CreditCard::factory()->create();
        $sourceBalance = $sourceCreditCard->bankAccount->balance;


        /** @var CreditCard $destinationCreditCard */
        $destinationCreditCard = CreditCard::factory()->create();
        $destinationBalance = $destinationCreditCard->bankAccount->balance;

        //---------------------
        //      perform Action
        //---------------------

        $transferMoney = rand(2, 9) * 1000;
        $response = $this->postJson(self::URL, [
            "source_card_number"      => $sourceCreditCard->number,
            "destination_card_number" => $destinationCreditCard->number,
            "amount"                  => $transferMoney
        ])
            ->assertStatus(201)
            ->json();


        //---------------------
        //      database Assertion
        //---------------------

        $this->assertDatabaseHas('credit_cards',
            ['bank_account_id' => $sourceCreditCard->bank_account_id, 'number' => $sourceCreditCard->number]
        );

        //*** check balance after withdraw + fee
        $this->assertDatabaseHas('bank_accounts',
            [
                'user_id' => $sourceCreditCard->bankAccount->user_id,
                'balance' => $sourceBalance - (config('bank.transaction.fee') + $transferMoney),
                'number'  => (string)$sourceCreditCard->bankAccount->number
            ]
        );

        //*** check balance deposit
        $this->assertDatabaseHas('bank_accounts',
            [
                'user_id' => $destinationCreditCard->bankAccount->user_id,
                'balance' => $destinationBalance + $transferMoney,
                'number'  => (string)$destinationCreditCard->bankAccount->number
            ]
        );

    }

    public function test_balance_is_not_enough()
    {

        //---------------------
        //      prepare Data
        //---------------------
        /** @var CreditCard $creditCard */
        $sourceCreditCard = CreditCard::factory()->create();
        $sourceBalance = $sourceCreditCard->bankAccount->balance;

        $sourceCreditCard->bankAccount()->update(['balance' => 200]);

        /** @var CreditCard $destinationCreditCard */
        $destinationCreditCard = CreditCard::factory()->create();
        $destinationBalance = $destinationCreditCard->bankAccount->balance;

        //---------------------
        //      perform Action
        //---------------------

        $transferMoney = rand(111, 999) * 1000;
        $response = $this->postJson(self::URL,
            [
                "source_card_number"      => $sourceCreditCard->number,
                "destination_card_number" => $destinationCreditCard->number,
                "amount"                  => $transferMoney
            ]
        )
            ->assertStatus(422)
            ->assertJson([
                "message" => "balance is not enough"
            ])
            ->json();

    }

    public function test_check_max_amount_validations()
    {

        //---------------------
        //      prepare Data
        //---------------------
        /** @var CreditCard $creditCard */
        $sourceCreditCard = CreditCard::factory()->create();
        $sourceBalance = $sourceCreditCard->bankAccount->balance;


        /** @var CreditCard $destinationCreditCard */
        $destinationCreditCard = CreditCard::factory()->create();
        $destinationBalance = $destinationCreditCard->bankAccount->balance;

        //---------------------
        //      perform Action
        //---------------------

        $transferMoney = rand(111, 999) * 1000;
        $response = $this->postJson(self::URL,
            [
                "source_card_number"      => $sourceCreditCard->number,
                "destination_card_number" => $destinationCreditCard->number,
                "amount"                  => 500000000
            ]
        )
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The amount field must not be greater than 50000000.'
            ])
            ->json();

    }

    public function test_check_min_amount_validations()
    {

        //---------------------
        //      prepare Data
        //---------------------
        /** @var CreditCard $creditCard */
        $sourceCreditCard = CreditCard::factory()->create();
        $sourceBalance = $sourceCreditCard->bankAccount->balance;


        /** @var CreditCard $destinationCreditCard */
        $destinationCreditCard = CreditCard::factory()->create();
        $destinationBalance = $destinationCreditCard->bankAccount->balance;

        //---------------------
        //      perform Action
        //---------------------

        $transferMoney = rand(111, 999) * 1000;
        $response = $this->postJson(self::URL,
            [
                "source_card_number"      => $sourceCreditCard->number,
                "destination_card_number" => $destinationCreditCard->number,
                "amount"                  => 10
            ]
        )
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The amount field must be at least 1000.'
            ])
            ->json();

    }

}
