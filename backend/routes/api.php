<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\DocumentController;


Route::apiResource('projects', ProjectController::class);

Route::apiResource('contracts', ContractController::class);

Route::apiResource('documents', DocumentController::class);

Route::get(
    'documents/{document}/download',
    [DocumentController::class, 'download']
);

