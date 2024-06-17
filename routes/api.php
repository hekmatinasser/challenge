<?php

use App\Http\Controllers\TransactionController;
use App\Http\Middleware\ToEnNumber;
use Illuminate\Support\Facades\Route;


Route::post("transactions/transfer", [TransactionController::class, 'transfer'])->middleware(ToEnNumber::class);

Route::get('transactions/top-users', [TransactionController::class, 'topUsers']);
