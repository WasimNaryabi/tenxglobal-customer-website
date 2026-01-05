<?php

use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\DemoRequestController;

use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuSyncController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Inertia\Inertia;
use App\Http\Controllers\CheckoutController;

require __DIR__.'/auth.php';

// Redirect base 'dashboard' name to avoid RouteNotFoundException
Route::get('/dashboard', function () {
    if (Auth::guard('customer')->check()) {
        return redirect()->route('portal.dashboard');
    }
    return redirect()->route('admin.dashboard');
})->name('dashboard');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/checkout/create-payment-intent', [CheckoutController::class, 'createPaymentIntent'])->name('checkout.payment-intent');
Route::get('/order/{orderNumber}/confirmation', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

// Order Tracking Routes
Route::get('/track-order', [\App\Http\Controllers\OrderTrackingController::class, 'index'])->name('order.track');
Route::get('/api/track-order/{orderNumber}', [\App\Http\Controllers\OrderTrackingController::class, 'track'])->name('api.order.track');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']); 
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:customer')->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// OTP Routes (Deprecated/Disabled)
// Route::post('/auth/send-otp', [AuthController::class, 'sendOTP']);
// Route::post('/auth/verify-otp', [AuthController::class, 'verifyOTP']);
// Route::post('/auth/complete-profile', [AuthController::class, 'completeProfile']);


// Profile Routes
Route::middleware(['auth:customer'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\CustomerPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\CustomerPortalController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\CustomerPortalController::class, 'updateProfile'])->name('profile.update');
    Route::put('/update-password', [App\Http\Controllers\CustomerPortalController::class, 'updatePassword'])->name('password.update');
    Route::get('/orders', [App\Http\Controllers\CustomerPortalController::class, 'orders'])->name('orders');
    
    // Future routes
    // Route::get('/subscription', [App\Http\Controllers\CustomerPortalController::class, 'subscription'])->name('subscription');
    // Route::get('/invoices', [App\Http\Controllers\CustomerPortalController::class, 'invoices'])->name('invoices');
    // Route::get('/support', [App\Http\Controllers\CustomerPortalController::class, 'support'])->name('support');
});

/*
|--------------------------------------------------------------------------
| Public Website Routes
|--------------------------------------------------------------------------
*/

// API endpoint for webhook/external sync (optional)
Route::post('/api/menu/sync', [MenuSyncController::class, 'apiSync']);
Route::get('/api/menu/sync/status', [MenuSyncController::class, 'status']);


Route::prefix('admin/sync')->middleware(['auth'])->group(function () {
    Route::post('/all', [MenuSyncController::class, 'syncAll']);
    Route::post('/menu', [MenuSyncController::class, 'syncMenu']);
    Route::post('/addons', [MenuSyncController::class, 'syncAddons']);
    Route::get('/stats', [MenuSyncController::class, 'stats']);
    Route::get('/last-sync', [MenuSyncController::class, 'lastSync']);
});

Route::get('/admin/menu/sync', [MenuSyncController::class, 'index'])->name('admin.menu.sync');
    Route::post('/admin/menu/sync', [MenuSyncController::class, 'sync'])->name('admin.menu.sync.trigger');

// Public menu route (uses database)
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

Route::get('/', [HomeController::class, 'index'])->name('home');

// About Us
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

// Menu
//Route::get('/menu', [MenuController::class, 'index'])->name('menu');

// Newsletter subscription
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Optional: Add these routes later


Route::get('/features', [WebsiteController::class, 'features'])->name('features');
Route::get('/pricing', [WebsiteController::class, 'pricing'])->name('pricing');

// Contact & Demo Forms
Route::post('/contact', [WebsiteController::class, 'submitContact'])->name('contact.submit');
Route::post('/demo-request', [WebsiteController::class, 'submitDemoRequest'])->name('demo.submit');


// routes/web.php
Route::get('/icons-test', function () {
    return Inertia::render('Test/IconsPreview');
});

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Customers
    Route::resource('customers', CustomerController::class);
    Route::post('customers/export', [CustomerController::class, 'export'])->name('customers.export');
    
    // Leads
    Route::resource('leads', LeadController::class);
    Route::post('leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');
    Route::post('leads/{lead}/assign', [LeadController::class, 'assign'])->name('leads.assign');
    Route::post('leads/export', [LeadController::class, 'export'])->name('leads.export');
    
    // Demo Requests
    Route::resource('demo-requests', DemoRequestController::class);
    Route::post('demo-requests/{demoRequest}/schedule', [DemoRequestController::class, 'schedule'])->name('demo-requests.schedule');
    Route::post('demo-requests/{demoRequest}/complete', [DemoRequestController::class, 'complete'])->name('demo-requests.complete');
    Route::post('demo-requests/{demoRequest}/cancel', [DemoRequestController::class, 'cancel'])->name('demo-requests.cancel');
    
});

/*
|--------------------------------------------------------------------------
| Customer Portal Routes (Moved above to group with middleware)
|--------------------------------------------------------------------------
*/


