<?php

namespace App\Models;

use App\Mail\MembershipStatusMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'program_id',
        'tier',
        'start_date',
        'end_date',
        'status',
        'payment_proof',
    ];

    protected $appends = ['payment_proof_url'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        return $this->payment_proof ? Storage::disk('public')->url($this->payment_proof) : null;
    }

    public function getMembershipNumberAttribute(): string
    {
        return 'QAC-' . str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    protected static function booted(): void
    {
        static::created(function (Membership $membership) {
            $membership->notifyStatus($membership->status ?: 'pending');
        });

        static::updated(function (Membership $membership) {
            $oldStatus = $membership->getOriginal('status');
            if ($oldStatus !== $membership->status) {
                $membership->notifyStatus($membership->status);
            }
        });
    }

    public function notifyStatus(string $status): void
    {
        if (!$this->customer || !$this->customer->email) {
            return;
        }

        try {
            Mail::to($this->customer->email)->send(new MembershipStatusMail($this, $status));
        } catch (\Throwable $e) {
            Log::warning('Membership status email failed', [
                'membership_id' => $this->id,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(MembershipProgram::class, 'program_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
