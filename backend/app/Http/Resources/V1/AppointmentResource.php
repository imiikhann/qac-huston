<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'vehicle_id' => $this->vehicle_id,
            'service_id' => $this->service_id,
            'requested_date' => $this->requested_date?->format('Y-m-d'),
            'requested_time' => $this->requested_time,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'service' => new ServiceResource($this->whenLoaded('service')),
        ];
    }
}
