<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route Stripe checkout accessible publiquement
Route::get('/payment/from-checkout', [PaymentController::class, 'stripeFromCheckout'])
    ->name('payment.stripe.from-checkout');

// Debug route
Route::get('/_routes-debug', function() {
    return response('routes-web-ok', 200);
})->name('zzzz.debug');
