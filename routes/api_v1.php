<?php
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RequestController;
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
Route::post('auth/login', [AuthController::class, 'login'])
    ->name('login')
    ->middleware('throttle:5');
Route::post('requests', [RequestController::class, 'store']);

Route::group(
    ['middleware' => 'auth:api'],
    static function () {
        Route::get('auth/logout', [AuthController::class, 'logout']);
        Route::apiResource('requests', RequestController::class)
            ->except('store');
    }
);
