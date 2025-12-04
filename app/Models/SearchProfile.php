<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'transaction_type',
        'cities',
        'min_price',
        'max_price',
        'min_surface',
        'max_surface',
        'min_bedrooms',
        'property_type',
        'energy_label',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'cities' => 'array',
            'min_price' => 'integer',
            'max_price' => 'integer',
            'min_surface' => 'integer',
            'max_surface' => 'integer',
            'min_bedrooms' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTransactionTypeLabelAttribute(): string
    {
        return match ($this->transaction_type) {
            'sale' => 'Koop',
            'rent' => 'Huur',
            default => 'Alle',
        };
    }

    public function getCriteriaCountAttribute(): int
    {
        $count = 0;
        if ($this->transaction_type) $count++;
        if ($this->cities && count($this->cities) > 0) $count++;
        if ($this->min_price || $this->max_price) $count++;
        if ($this->min_surface || $this->max_surface) $count++;
        if ($this->min_bedrooms) $count++;
        if ($this->property_type) $count++;
        if ($this->energy_label) $count++;
        return $count;
    }
}
