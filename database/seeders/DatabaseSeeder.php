<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\CreditCard;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
       CreditCard::factory(2)->create();
    }

}

