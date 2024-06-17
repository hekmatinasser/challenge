<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TopUserTransactions extends ResourceCollection
{

    public function toArray(Request $request): array
    {
        return [
            'user'         => new UserResource($this['user']),
            'transactions' => TransactionResource::collection($this['transactions'])
        ];
    }
}
