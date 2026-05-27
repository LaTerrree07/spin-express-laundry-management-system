<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'cf_name',
        'cm_name',
        'cl_name',
        'contact_number',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'customer_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim(collect([
            $this->cf_name,
            $this->cm_name,
            $this->cl_name,
        ])->filter()->implode(' '));
    }
}