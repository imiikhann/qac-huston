<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
        'fleet_size',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'fleet_size' => 'integer',
        ];
    }
}
