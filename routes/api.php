<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\SurveyController;
use App\Models\User;
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

Route::post('/auth/register', [AuthController::class, 'create']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/surveys/{id}', [SurveyController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/surveys/{id}', [SurveyController::class, 'saveAnswers']);
});
Route::get('/surveys/{id}/statistics/{slug}', [StatisticsController::class, 'getStatistics']);
