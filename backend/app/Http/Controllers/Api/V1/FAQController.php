<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index(Request $request)
    {
        $faqs = Faq::active()
            ->ordered()
            ->when($request->category, fn ($q, $c) => $q->where('category', $c))
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => $faqs->items(),
            'meta' => [
                'current_page' => $faqs->currentPage(),
                'last_page' => $faqs->lastPage(),
                'per_page' => $faqs->perPage(),
                'total' => $faqs->total(),
            ],
        ]);
    }
}
