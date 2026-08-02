<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreFleetInquiryRequest;
use App\Models\FleetInquiry;
use Illuminate\Http\JsonResponse;

class FleetInquiryController extends Controller
{
    public function store(StoreFleetInquiryRequest $request): JsonResponse
    {
        FleetInquiry::create($request->validated());

        return response()->json([
            'message' => 'Fleet inquiry submitted successfully. We will contact you within 24 hours.',
        ], 201);
    }
}
