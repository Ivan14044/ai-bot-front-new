<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CookieConsentController;
use App\Http\Controllers\CryptomusController;
use App\Http\Controllers\MonoController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\ServiceAccount;
use App\Http\Controllers\ExtensionController;
use App\Http\Controllers\Api\PromocodeController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
Route::post('/user', [AuthController::class, 'update'])->middleware('auth:sanctum');

Route::get('/notifications', [NotificationController::class, 'index'])->middleware('auth:sanctum');
Route::post('/notifications/read', [NotificationController::class, 'markNotificationsAsRead'])
    ->middleware('auth:sanctum');

Route::get('/services', [ServiceController::class, 'index']);

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{article}', [ArticleController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/pages', [PageController::class, 'index']);
Route::get('/options', [OptionController::class, 'index']);
Route::post('/cart', [CartController::class, 'store'])->middleware('auth:sanctum');

Route::post('/toggle-auto-renew', [SubscriptionController::class, 'toggleAutoRenew'])->middleware('auth:sanctum');
Route::post('/cancel-subscription', [SubscriptionController::class, 'cancelSubscription'])->middleware('auth:sanctum');

Route::get('/cookie/check', [CookieConsentController::class, 'check']);

Route::post('/cryptomus/create-payment', [CryptomusController::class, 'createPayment'])->middleware('auth:sanctum');
Route::post('/cryptomus/webhook', [CryptomusController::class, 'webhook']); //->middleware('cryptomus');
Route::post('/mono/create-payment', [MonoController::class, 'createPayment'])->middleware('auth:sanctum');
Route::post('/mono/webhook', [MonoController::class, 'webhook']); //->middleware('cryptomus');

Route::get('/contents/{code}', [ContentController::class, 'show']);

Route::get('/browser/new', function (Request $request) {
    $service = Service::findOrFail($request->service_id);

    $base = rtrim(config('services.browser_api.url'), '/');
    $appUrl = $service->params['link'] ?? null;

    if ($request->has('profile')) {
        $profile = $request->profile;
    } else {
        $account = ServiceAccount::where('service_id', $service->id)
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expiring_at')->orWhere('expiring_at', '>', now());
            })
            ->orderBy('id', 'asc')
            ->first();
        $profile = $account->profile_id ?? null;
    }

    if ($appUrl && !Str::startsWith($appUrl, ['http://', 'https://'])) {
        $appUrl = 'https://' . ltrim($appUrl, '/');
    }

    if (!filter_var($appUrl, FILTER_VALIDATE_URL)) {
        $appUrl = 'https://google.com';
    }

    $resp = Http::timeout(60)->get($base . '/new', [
        'app' => $appUrl,
        'profile' => $profile,
        'lang' => $request->uiLanguage ?? 'en',
    ]);

    return response($resp->body(), $resp->status())
        ->withHeaders(['Content-Type' => 'application/json']);
});

Route::post('/browser/stop', function (Request $request) {
    $base = rtrim(config('services.browser_api.url'), '/');
    $resp = Http::timeout(60)->asJson()->post($base . '/stop', $request->all());

    return response($resp->body(), $resp->status())
        ->withHeaders(['Content-Type' => 'application/json']);
});

Route::post('/browser/stop_all', function (Request $request) {
    $base = rtrim(config('services.browser_api.url'), '/');
    $resp = Http::timeout(60)->asJson()->post($base . '/stop_all', $request->all());

    return response($resp->body(), $resp->status())
        ->withHeaders(['Content-Type' => 'application/json']);
});

Route::get('/browser/list', function () {
    $base = rtrim(config('services.browser_api.url'), '/');
    $resp = Http::timeout(60)->get($base . '/list');

    return response($resp->body(), $resp->status())
        ->withHeaders(['Content-Type' => 'application/json']);
});

Route::middleware('ext.auth')->group(function () {
    Route::post('/extension/settings', [ExtensionController::class, 'saveSettings']);
    Route::get('/extension/auth', [ExtensionController::class, 'authStatus']);
});

Route::post('/promocodes/validate', [PromocodeController::class, 'validateCode']);
