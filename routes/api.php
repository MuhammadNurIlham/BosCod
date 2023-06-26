<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\RekeningAdminController;
use App\Http\Controllers\TransaksiTransferController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Route For Auth
 */
Route::post('v1/auth/login', [AuthController::class, 'login']);
Route::post('v1/auth/register', [AuthController::class, 'register']);


/**
 * General Route without Authentication
 * Get All Users
 * Get All Banks
 * Get All Transactions
 * Get All Rekening Admins
 */
Route::get('v1/users', [UserController::class, 'index']);
Route::get('v1/banks', [BankController::class, 'index']);
Route::get('v1/transaksi/transfers', [TransaksiTransferController::class, 'index']);
Route::get('v1/transaksi/transfer/{id}', [TransaksiTransferController::class, 'show']);
Route::get('v1/rekening-admins', [RekeningAdminController::class, 'index']);

/**
 * Route with Middleware Auth for Access Authentication
 */
Route::middleware(['auth:sanctum'])->group(function () {
    /**
     * Authentication with Middleware Auth Route for Auth
     */
    Route::get('v1/auth/logout', [AuthController::class, 'logout']);
    Route::post('v1/auth/update-token', [AuthController::class, 'updateToken']);

    /**
     * Route with Middleware Auth for Transferred
     */
    Route::post('/v1/transfer', [TransaksiTransferController::class, 'store']);

    /**
     * Route with Middleware Auth for Add Rekening Admin for Admin
     */
    Route::post('v1/rekening-admin', [RekeningAdminController::class, 'store']);

    /**
     * Route with Middleware Auth for Bank
     */
    Route::get('v1/bank/{id}', [BankController::class, 'show']);
    Route::post('v1/bank', [BankController::class, 'store']);
    Route::patch('v1/bank/{id}', [BankController::class, 'update']);
    Route::delete('v1/bank/{id}', [BankController::class, 'destroy']);
});
