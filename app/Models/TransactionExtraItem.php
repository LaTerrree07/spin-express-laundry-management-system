<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionExtraItem extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'transaction_extra_item_id';

    protected $fillable = [
        'transaction_id',
        'extra_item_id',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    public function extraItem(): BelongsTo
    {
        return $this->belongsTo(ExtraItem::class, 'extra_item_id', 'extra_item_id');
    }
}