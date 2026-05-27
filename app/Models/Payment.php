<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'transaction_id',
        'payment_amount',
        'payment_method',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    public function isPaid(): bool
    {
        return strtolower($this->payment_status) === 'paid';
    }

    public function isUnpaid(): bool
    {
        return strtolower($this->payment_status) === 'unpaid';
    }

    public function canBeEditedBy(?\App\Models\User $user): bool
    {
        if (! $user) {
            return false;
        }

        if ($this->isUnpaid()) {
            return in_array($user->role, ['admin', 'staff'], true);
        }

        return $this->isPaid() && $user->role === 'admin';
    }

    public function allowsFullEdit(?\App\Models\User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->isUnpaid() && in_array($user->role, ['admin', 'staff'], true);
    }

    public function allowsLimitedCorrection(?\App\Models\User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->isPaid() && $user->role === 'admin';
    }

    public function canBeDeletedBy(?\App\Models\User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $user->role === 'admin' && $this->isUnpaid();
    }
}