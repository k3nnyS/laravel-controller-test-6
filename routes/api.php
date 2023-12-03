<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VoiceController;

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

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('voices', [VoiceController::class, 'voice']);
});
