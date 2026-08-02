<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreContactRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request): JsonResponse
    {
        Lead::create([
            'source' => 'website',
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'service_interest' => $request->service_interest,
            'message' => $request->message,
            'status' => 'new',
        ]);

        return response()->json([
            'message' => 'Thank you for contacting QAC Houston! We will get back to you within 24 hours.',
        ], 201);
    }
}
