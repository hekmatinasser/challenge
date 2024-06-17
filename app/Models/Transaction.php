<?php

namespace App\Models;

use App\Enumerations\Treansaction\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_card_id',
        'type',
        'amount'
    ];

    protected function casts()
    {
        return [
            'type' => Type::class
        ];
    }

    public function creditCard(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TransactionLog::class);
    }
}
