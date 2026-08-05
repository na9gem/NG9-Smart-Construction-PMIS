<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ContractController;

Route::get('/', function () {
    return view('welcome');
});

