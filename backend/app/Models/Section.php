<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'type',
        'title',
        'subtitle',
        'content',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}
