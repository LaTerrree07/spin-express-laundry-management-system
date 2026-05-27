<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtraItem extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'extra_item_id';

    protected $fillable = [
        'item_name',
        'price',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function transactionExtraItems(): HasMany
    {
        return $this->hasMany(TransactionExtraItem::class, 'extra_item_id', 'extra_item_id');
    }
}