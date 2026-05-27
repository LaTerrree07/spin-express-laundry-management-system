<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'service_category_id';

    protected $fillable = [
        'category_name',
        'description',
    ];

    public function serviceTypes(): HasMany
    {
        return $this->hasMany(ServiceType::class, 'service_category_id', 'service_category_id');
    }
}