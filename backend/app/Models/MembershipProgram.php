<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipProgram extends Model
{
    protected $fillable = [
        'name',
        'price',
        'features',
        'is_popular',
        'description',
        'button_text',
        'button_link',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
