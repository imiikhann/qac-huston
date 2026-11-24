<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'access_key',
        'name',
        'email',
        'phone',
        'subject',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket) {
            $ticket->uuid ??= (string) Str::uuid();
            $ticket->access_key ??= Str::random(40);
            $ticket->last_message_at ??= now();
        });
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class)->orderBy('created_at');
    }

    public function scopeUnresolved($query)
    {
        return $query->whereIn('status', ['open', 'pending']);
    }
}
