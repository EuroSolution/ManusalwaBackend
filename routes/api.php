<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('banners', [HomeController::class, 'banners']);
Route::get('menu', [HomeController::class, 'menu']);
Route::get('deals', [HomeController::class, 'deals']);
Route::get('products', [HomeController::class, 'products']);
Route::get('product/{id}', [HomeController::class, 'productById']);
Route::get('area-code-charges', [HomeController::class, 'areaCodeCharges']);
Route::get('setting', [HomeController::class, 'siteSetting']);
Route::post('contact-us', [HomeController::class, 'contactUs']);

Route::prefix('auth')->group(function(){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('verify-token', [AuthController::class, 'verifyToken']);
    Route::post('resend-otp-token', [AuthController::class, 'resendOtpToken']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('forgot-password-web', [AuthController::class, 'forgotPasswordWeb']);
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('update-fcm-token', [AuthController::class, 'updateFcmToken']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
    Route::get('unauthenticated', [AuthController::class, 'unauthenticatedUser']);
});

Route::prefix('user')->middleware(['auth:sanctum'])->group(function(){
    Route::get('profile', [ProfileController::class, 'index']);
    Route::get('vouchers', [ProfileController::class, 'vouchers']);
    Route::get('orders', [ProfileController::class, 'orders']);
    Route::get('orders/{id}', [ProfileController::class, 'getOrderById']);
    Route::post('edit', [ProfileController::class, 'edit']);
});

Route::middleware(['auth:sanctum'])->group(function (){
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/add', [CartController::class, 'add']);
    Route::post('validate-voucher', [OrderController::class, 'validateVoucher']);
    Route::post('checkout', [OrderController::class, 'checkout']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
