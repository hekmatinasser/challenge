<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'     => $this['id'],
            'type'   => $this['type'],
            'amount' => $this['amount'],
        ];
    }
}
