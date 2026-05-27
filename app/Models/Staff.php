<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    protected $table = 'staff';
    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'sf_name',
        'sm_name',
        'sl_name',
        'date_of_birth',
        'age',
        'contact_number',
        'address',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'staff_id', 'staff_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim(collect([
            $this->sf_name,
            $this->sm_name,
            $this->sl_name,
        ])->filter()->implode(' '));
    }
}