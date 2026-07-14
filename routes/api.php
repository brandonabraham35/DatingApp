<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\BillingController;
use App\Http\Controllers\Api\DiscoveryController;
use App\Http\Middleware\EnsureUserIsSubscribed;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/stripe/webhook', '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    Route::get('/matches', [MatchController::class, 'index']);
    Route::post('/matches', [MatchController::class, 'store']);

    Route::get('/messages/{userId}', [MessageController::class, 'index']);
    Route::post('/messages/{messageId}/read', [MessageController::class, 'markAsRead']);

    Route::post('/messages', [MessageController::class, 'store'])->middleware(EnsureUserIsSubscribed::class);

    Route::get('/discover', [DiscoveryController::class, 'index']);
    Route::get('/billing/checkout', [BillingController::class, 'checkout']);
    Route::get('/billing/portal', [BillingController::class, 'portal']);
});
