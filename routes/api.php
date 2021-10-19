<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\AuthenticationRequest;
use App\Models\User;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TransactionController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('init', [AuthenticationController::class, 'login']);

Route::middleware('api')->group(function () {
    Route::post('wallet', [WalletController::class, 'store']);
    Route::get('wallet', [WalletController::class, 'show']);

    // Make sure to send using --data-urlencode, as Laravel doesn't recognize patch in form-data
    Route::patch('wallet', [WalletController::class, 'update']);

    Route::post('wallet/deposits', [TransactionController::class, 'storeDeposit']);
    Route::post('wallet/withdrawals', [TransactionController::class, 'storeWithdrawal']);
});
