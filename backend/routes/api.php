<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\ProgressReportController;



Route::apiResource('projects', ProjectController::class);

Route::apiResource('contracts', ContractController::class);

Route::apiResource('documents', DocumentController::class);

Route::get(
    'documents/{document}/download',
    [DocumentController::class, 'download']
);

Route::apiResource('progress-reports', ProgressReportController::class);



