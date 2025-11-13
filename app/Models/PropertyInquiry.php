<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyInquiry extends Model
{
    protected $fillable = [
        'property_unit_id',
        'user_id',
        'name',
        'email',
        'phone',
        'message',
        'agent_email',
        'sent_to_agent',
        'sent_at',
    ];

    protected $casts = [
        'sent_to_agent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(PropertyUnit::class, 'property_unit_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
