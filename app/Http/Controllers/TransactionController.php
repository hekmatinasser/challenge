<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Http\Resources\TopUserTransactions;
use App\Repositories\Transactions\TransactionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class TransactionController extends Controller
{

    public function __construct(public TransactionRepositoryInterface $repository)
    {
    }

    public function transfer(TransferRequest $request): JsonResponse
    {
        $this->repository->transfer($request->validated());

        return response()->json(['message' => 'transfer was successful']);
    }

    public function topUsers(): AnonymousResourceCollection
    {
        $result = $this->repository->topUsers();

        return TopUserTransactions::collection($result);
    }
}
