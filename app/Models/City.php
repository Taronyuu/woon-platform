<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'province',
    ];

    public function propertyUnits(): HasMany
    {
        return $this->hasMany(PropertyUnit::class, 'address_city', 'name');
    }
}
