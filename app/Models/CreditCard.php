<?php

namespace App\Models;

use Database\Factories\CreditCardFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property User $user
 * @property BankAccount $bankAccount
 * @property Collection<Transaction> $transactions
 */
class CreditCard extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_account_id',
        'number'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    protected static function newFactory(): CreditCardFactory
    {
        return CreditCardFactory::new();
    }
}
