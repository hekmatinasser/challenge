<?php

namespace App\Repositories\Transactions;

interface TransactionRepositoryInterface
{
    public function transfer(array $data);

    public function topUsers();
}
