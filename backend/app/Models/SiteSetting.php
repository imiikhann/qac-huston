<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'logo',
        'logo_light',
        'logo_dark',
        'logo_max_width',
        'logo_max_height',
        'favicon',
        'contact_email',
        'contact_phone',
        'address',
        'social_links',
        'footer_text',
        'is_maintenance_mode',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_maintenance_mode' => 'boolean',
    ];
}
