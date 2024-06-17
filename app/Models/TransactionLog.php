<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'source_card_id',
        'destination_card_id',
    ];


    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function sourceCard(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class, 'source_card_id');
    }

    public function destinationCard(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class, 'destination_card_id');
    }

}
