<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'status_id';

    protected $fillable = [
        'status_name',
        'description',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'status_id', 'status_id');
    }
}