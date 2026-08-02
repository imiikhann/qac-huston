<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with(['customer', 'vehicle', 'service'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->customer_id, fn ($q, $id) => $q->where('customer_id', $id))
            ->when($request->from_date, fn ($q, $d) => $q->where('requested_date', '>=', $d))
            ->when($request->to_date, fn ($q, $d) => $q->where('requested_date', '<=', $d))
            ->latest('requested_date')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $appointments->items(),
            'meta' => [
                'current_page' => $appointments->currentPage(),
                'last_page' => $appointments->lastPage(),
                'per_page' => $appointments->perPage(),
                'total' => $appointments->total(),
            ],
        ]);
    }

    public function store(\App\Http\Requests\V1\StoreAppointmentRequest $request)
    {
        $data = $request->validated();

        if (!empty($data['customer_id'])) {
            $customer = Customer::findOrFail($data['customer_id']);
        } else {
            $customer = Customer::firstOrCreate(
                ['email' => $data['customer']['email']],
                [
                    'name' => $data['customer']['name'],
                    'phone' => $data['customer']['phone'] ?? null,
                ]
            );
        }

        $vehicle = null;
        if (!empty($data['vehicle']['make']) || !empty($data['vehicle']['vin'])) {
            $vehicle = !empty($data['vehicle']['vin'])
                ? Vehicle::where('vin', $data['vehicle']['vin'])->first()
                : null;

            if (!$vehicle) {
                $vehicle = Vehicle::create([
                    'customer_id' => $customer->id,
                    'make' => $data['vehicle']['make'] ?? null,
                    'model' => $data['vehicle']['model'] ?? null,
                    'year' => $data['vehicle']['year'] ?? null,
                    'vin' => $data['vehicle']['vin'] ?? null,
                ]);
            }
        }

        $appointment = Appointment::create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle?->id,
            'service_id' => $data['service_id'] ?? null,
            'requested_date' => $data['requested_date'],
            'requested_time' => $data['requested_time'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);
        $appointment->load(['customer', 'vehicle', 'service']);

        return response()->json([
            'data' => $appointment,
            'message' => 'Appointment booked successfully.',
        ], 201);
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['customer', 'vehicle', 'service']);

        return response()->json([
            'data' => $appointment,
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|required|exists:customers,id',
            'vehicle_id' => 'sometimes|nullable|exists:vehicles,id',
            'service_id' => 'sometimes|nullable|exists:services,id',
            'requested_date' => 'sometimes|required|date',
            'requested_time' => 'sometimes|nullable|date_format:H:i',
            'status' => 'sometimes|required|in:pending,confirmed,completed,cancelled',
            'notes' => 'sometimes|nullable|string',
        ]);

        $appointment->update($validated);

        return response()->json([
            'data' => $appointment->fresh()->load(['customer', 'vehicle', 'service']),
            'message' => 'Appointment updated successfully.',
        ]);
    }

    public function destroy(Appointment $appointment): JsonResponse
    {
        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully.',
        ]);
    }
}
