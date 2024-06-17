<?php

namespace App\Models;

use Database\Factories\BankAccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'number'
    ];

    protected static function newFactory(): BankAccountFactory
    {
        return BankAccountFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creditCards(): HasMany
    {
        return $this->hasMany(CreditCard::class, 'bank_account_id');
    }
}
