<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\ProgressReportController;
use App\Http\Controllers\Api\InspectionController;
use App\Http\Controllers\Api\MediaController;



Route::apiResource('projects', ProjectController::class);

Route::apiResource('contracts', ContractController::class);

Route::apiResource('documents', DocumentController::class);

Route::get(
    'documents/{document}/download',
    [DocumentController::class, 'download']
);

Route::apiResource('progress-reports', ProgressReportController::class);
Route::apiResource('inspections', InspectionController::class);
Route::apiResource('media', MediaController::class);






