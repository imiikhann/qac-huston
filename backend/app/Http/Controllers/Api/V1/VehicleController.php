<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $vehicles = Vehicle::with('customer')
            ->when($request->customer_id, fn ($q, $id) => $q->where('customer_id', $id))
            ->when($request->search, function ($q, $s) {
                $q->where('make', 'like', "%{$s}%")
                  ->orWhere('model', 'like', "%{$s}%")
                  ->orWhere('vin', 'like', "%{$s}%");
            })
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $vehicles->items(),
            'meta' => [
                'current_page' => $vehicles->currentPage(),
                'last_page' => $vehicles->lastPage(),
                'per_page' => $vehicles->perPage(),
                'total' => $vehicles->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'vin' => 'nullable|string|max:17|unique:vehicles,vin',
        ]);

        $vehicle = Vehicle::create($validated);

        return response()->json([
            'data' => $vehicle->load('customer'),
            'message' => 'Vehicle created successfully.',
        ], 201);
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['customer', 'appointments.service']);

        return response()->json([
            'data' => $vehicle,
        ]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|required|exists:customers,id',
            'make' => 'sometimes|required|string|max:255',
            'model' => 'sometimes|required|string|max:255',
            'year' => 'sometimes|nullable|integer|min:1900|max:' . (date('Y') + 1),
            'vin' => 'sometimes|nullable|string|max:17|unique:vehicles,vin,' . $vehicle->id,
        ]);

        $vehicle->update($validated);

        return response()->json([
            'data' => $vehicle->fresh()->load('customer'),
            'message' => 'Vehicle updated successfully.',
        ]);
    }

    public function destroy(Vehicle $vehicle): JsonResponse
    {
        $vehicle->delete();

        return response()->json([
            'message' => 'Vehicle deleted successfully.',
        ]);
    }
}
