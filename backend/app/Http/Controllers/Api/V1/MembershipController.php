<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreMembershipRequest;
use App\Models\Customer;
use App\Models\Membership;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $memberships = Membership::with(['customer', 'program'])
            ->when($request->program_id, fn ($q, $id) => $q->where('program_id', $id))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $memberships->items(),
            'meta' => [
                'current_page' => $memberships->currentPage(),
                'last_page' => $memberships->lastPage(),
                'per_page' => $memberships->perPage(),
                'total' => $memberships->total(),
            ],
        ]);
    }

    public function store(StoreMembershipRequest $request)
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

        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('membership-proofs', 'public');
        }

        $membership = Membership::create([
            'customer_id' => $customer->id,
            'program_id' => $data['program_id'],
            'tier' => $vehicle ? null : ($data['tier'] ?? null),
            'start_date' => $data['start_date'] ?? now()->toDateString(),
            'end_date' => $data['end_date'] ?? null,
            'status' => 'pending',
            'payment_proof' => $paymentProofPath,
        ]);

        $membership->load(['customer', 'program']);

        return response()->json([
            'data' => $membership,
            'message' => 'Membership enrollment request submitted successfully. We will confirm shortly.',
        ], 201);
    }

    public function show(Membership $membership)
    {
        $membership->load(['customer', 'program']);

        return response()->json([
            'data' => $membership,
        ]);
    }
}
