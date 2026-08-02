<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorTracking extends Model
{
    protected $table = 'visitor_tracking';

    protected $fillable = [
        'url',
        'ip_address',
        'country',
        'region',
        'city',
        'zip_code',
        'user_agent',
        'referrer_url',
        'visited_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }
}
