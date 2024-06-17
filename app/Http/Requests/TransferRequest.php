<?php

namespace App\Http\Requests;

use App\Rules\CreditCardNumber;
use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'source_card_number'      => ['required', 'integer', 'digits:16', 'exists:credit_cards,number', new CreditCardNumber()],
            'destination_card_number' => ['required', 'integer', 'digits:16', 'exists:credit_cards,number', new CreditCardNumber()],
            'amount'                  => ['required', 'integer', 'min:10000', 'max:500000000'],
        ];
    }
}
