<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreLeadRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request): JsonResponse
    {
        Lead::create($request->validated());

        return response()->json([
            'message' => 'Thank you for your interest! We will be in touch shortly.',
        ], 201);
    }
}
