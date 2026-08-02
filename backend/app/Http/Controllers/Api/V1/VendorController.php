<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $vendors = Vendor::active()
            ->ordered()
            ->when($request->category, fn ($q, $c) => $q->where('category', $c))
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => $vendors->items(),
            'meta' => [
                'current_page' => $vendors->currentPage(),
                'last_page' => $vendors->lastPage(),
                'per_page' => $vendors->perPage(),
                'total' => $vendors->total(),
            ],
        ]);
    }
}
