<?php

use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\CmsController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\FAQController;
use App\Http\Controllers\Api\V1\FleetInquiryController;
use App\Http\Controllers\Api\V1\LeadController;
use App\Http\Controllers\Api\V1\MembershipController;
use App\Http\Controllers\Api\V1\PPIRequestController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\TestimonialController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\VehicleController;
use App\Http\Controllers\Api\V1\VendorController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public endpoints (rate-limited, no auth required)
    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{service}', [ServiceController::class, 'show']);
    Route::get('reviews', [ReviewController::class, 'index']);
    Route::get('reviews/{review}', [ReviewController::class, 'show']);
    Route::get('faqs', [FAQController::class, 'index']);
    Route::get('vendors', [VendorController::class, 'index']);
    Route::get('testimonials', [TestimonialController::class, 'index']);

    // CMS endpoints
    Route::get('settings', [CmsController::class, 'settings']);
    Route::get('maintenance-status', [CmsController::class, 'maintenanceStatus'])->middleware('web');
    Route::get('hero-banners', [CmsController::class, 'heroBanners']);
    Route::get('navigation/{location?}', [CmsController::class, 'navigation'])->defaults('location', 'header');
    Route::get('pages', [CmsController::class, 'pages']);
    Route::get('pages/{slug}', [CmsController::class, 'page']);
    Route::get('sections', [CmsController::class, 'sections']);
    Route::get('membership-programs', [CmsController::class, 'membershipPrograms']);
    Route::get('service-categories', [CmsController::class, 'serviceCategories']);
    Route::post('track', [CmsController::class, 'track']);

    Route::post('appointments', [AppointmentController::class, 'store']);
    Route::post('memberships', [MembershipController::class, 'store']);
    Route::post('fleet-inquiries', [FleetInquiryController::class, 'store']);
    Route::post('ppi-requests', [PPIRequestController::class, 'store']);
    Route::post('leads', [LeadController::class, 'store']);
    Route::post('contact', [ContactController::class, 'store']);

    // Support ticket endpoints (website chat widget)
    Route::post('tickets', [TicketController::class, 'store'])->middleware('throttle:30,1');
    Route::get('tickets/{uuid}', [TicketController::class, 'show'])->middleware('throttle:120,1');
    Route::post('tickets/{uuid}/messages', [TicketController::class, 'reply'])->middleware('throttle:60,1');

    // Auth routes
    Route::post('auth/login', [AuthController::class, 'login']);

    // Authenticated endpoints (require Sanctum token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('vehicles', VehicleController::class);
        Route::get('appointments', [AppointmentController::class, 'index']);
        Route::get('appointments/{appointment}', [AppointmentController::class, 'show']);
        Route::put('appointments/{appointment}', [AppointmentController::class, 'update']);
        Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy']);

        Route::get('memberships', [MembershipController::class, 'index']);
        Route::get('memberships/{membership}', [MembershipController::class, 'show']);
    });
});
