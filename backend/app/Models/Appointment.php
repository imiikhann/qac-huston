<?php

namespace App\Models;

use App\Mail\AppointmentStatusMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'service_id',
        'requested_date',
        'requested_time',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'requested_date' => 'date',
            'requested_time' => 'datetime:H:i',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Appointment $appointment) {
            $appointment->notifyStatus($appointment->status ?: 'pending');
        });

        static::updated(function (Appointment $appointment) {
            $oldStatus = $appointment->getOriginal('status');
            if ($oldStatus !== $appointment->status) {
                $appointment->notifyStatus($appointment->status);
            }
        });
    }

    public function notifyStatus(string $status): void
    {
        if (!$this->customer || !$this->customer->email) {
            return;
        }

        try {
            Mail::to($this->customer->email)->send(new AppointmentStatusMail($this, $status));
        } catch (\Throwable $e) {
            Log::warning('Appointment status email failed', [
                'appointment_id' => $this->id,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('requested_date', '>=', now())->where('status', '!=', 'cancelled');
    }
}
