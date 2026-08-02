<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpiRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'email',
        'phone',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'preferred_date',
        'preferred_time',
        'tier',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'vehicle_year' => 'integer',
            'preferred_date' => 'date',
            'preferred_time' => 'datetime:H:i',
        ];
    }
}
