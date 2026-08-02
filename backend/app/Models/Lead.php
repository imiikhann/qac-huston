<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'name',
        'email',
        'phone',
        'service_interest',
        'message',
        'status',
    ];

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }
}
