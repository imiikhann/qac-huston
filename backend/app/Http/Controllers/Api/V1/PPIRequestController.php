<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StorePPIRequestRequest;
use App\Models\PpiRequest;
use Illuminate\Http\JsonResponse;

class PPIRequestController extends Controller
{
    public function store(StorePPIRequestRequest $request): JsonResponse
    {
        PpiRequest::create($request->validated());

        return response()->json([
            'message' => 'Pre-Purchase Inspection request submitted. Our team will reach out to confirm your appointment.',
        ], 201);
    }
}
