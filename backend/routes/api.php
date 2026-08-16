<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\DocumentController;

use App\Http\Controllers\Api\ProgressReportController;
use App\Http\Controllers\Api\ProgressPlanController;
use App\Http\Controllers\Api\ProgressPlanItemController;
use App\Http\Controllers\Api\InspectionController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MilestoneController;
use App\Http\Controllers\Api\WorkPackageController;
use App\Http\Controllers\Api\ActivityController;

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('permission:dashboard.view');

Route::get(
    '/dashboard/projects/{project}/s-curve',
    [DashboardController::class, 'sCurve']
)->middleware('permission:dashboard.view');

Route::apiResource('projects', ProjectController::class)
    ->middlewareFor(['index', 'show'], 'permission:project.view')
    ->middlewareFor('store', 'permission:project.create')
    ->middlewareFor('update', 'permission:project.update')
    ->middlewareFor('destroy', 'permission:project.delete');
Route::apiResource('contracts', ContractController::class)
    ->middleware('permission:contract.manage');
Route::apiResource('documents', DocumentController::class)
    ->middleware('permission:document.manage');
Route::apiResource('progress-reports', ProgressReportController::class)
    ->middleware('permission:progress.manage');
Route::post(
    'progress-reports/{progressReport}/approve',
    [ProgressReportController::class, 'approve']
)->middleware('permission:progress.manage');

Route::apiResource('progress-plans', ProgressPlanController::class)
    ->middleware('permission:progress.manage');
Route::apiResource('milestones', MilestoneController::class)
    ->middleware('permission:progress.manage');
Route::apiResource('work-packages', WorkPackageController::class)
    ->middleware('permission:progress.manage');
Route::apiResource('activities', ActivityController::class)
    ->middleware('permission:progress.manage');
Route::get(
    'progress-plans/{progressPlan}/items',
    [ProgressPlanItemController::class, 'index']
)->middleware('permission:progress.manage');

Route::post(
    'progress-plans/{progressPlan}/items',
    [ProgressPlanItemController::class, 'store']
)->middleware('permission:progress.manage');

Route::get(
    'progress-plans/{progressPlan}/items/{progressPlanItem}',
    [ProgressPlanItemController::class, 'show']
)->middleware('permission:progress.manage');

Route::put(
    'progress-plans/{progressPlan}/items/{progressPlanItem}',
    [ProgressPlanItemController::class, 'update']
)->middleware('permission:progress.manage');

Route::delete(
    'progress-plans/{progressPlan}/items/{progressPlanItem}',
    [ProgressPlanItemController::class, 'destroy']
)->middleware('permission:progress.manage');

Route::apiResource('inspections', InspectionController::class)
    ->middleware('permission:inspection.manage');
    Route::apiResource('media', MediaController::class);
    

Route::get(
    'documents/{document}/download',
    [DocumentController::class, 'download']
)->middleware('permission:document.manage');


});















