<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealtorAppointment extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'status',
        'contacted_at',
    ];

    protected $casts = [
        'contacted_at' => 'datetime',
    ];
}
