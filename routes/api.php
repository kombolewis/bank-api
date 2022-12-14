<?php

use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\AccountsController;
use App\Http\Controllers\User\AccountActionsController;
use App\Http\Controllers\User\AccountTransfersController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::prefix('admin')->middleware(['auth:api','admin'])->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::resource('users', AdminController::class)->only(['index', 'store']);
});

Route::prefix('user')->middleware('auth:api')->group(function () {
    Route::middleware('creator')->group(function () {
        Route::post('/actions', [AccountActionsController::class, 'store']);
        Route::post('/transfer', [AccountTransfersController::class, 'store']);
    });

    Route::middleware('viewer')->group(function () {
        Route::get('/actions/{account}', [AccountActionsController::class, 'show']);
        Route::get('/transfer/{account}', [AccountTransfersController::class, 'show']);
    });
});
