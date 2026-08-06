<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\ProgressReportController;
use App\Http\Controllers\Api\InspectionController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\AuthController;


// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('contracts', ContractController::class);
    Route::apiResource('documents', DocumentController::class);
    Route::apiResource('progress-reports', ProgressReportController::class);
    Route::apiResource('inspections', InspectionController::class);
    Route::apiResource('media', MediaController::class);

    Route::get(
    'documents/{document}/download',
    [DocumentController::class, 'download']
);



});















