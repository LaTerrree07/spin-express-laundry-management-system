<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceType extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'service_type_id';

    protected $fillable = [
        'service_category_id',
        'service_name',
        'price',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id', 'service_category_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'service_type_id', 'service_type_id');
    }
}