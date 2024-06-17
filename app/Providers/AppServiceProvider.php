<?php

namespace App\Providers;


use App\Repositories\Transactions\TransactionRepository;
use App\Repositories\Transactions\TransactionRepositoryInterface;
use App\Services\Bank\Services\BankService;
use App\Services\Bank\Services\BankServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(BankServiceInterface::class, BankService::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
    }

    /**
     * Bootstrap any application Modules.
     */
    public function boot(): void
    {
        //
    }
}
