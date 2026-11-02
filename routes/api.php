<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RepairApiController;

// Public route to check API status
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Repair REST API endpoints
Route::apiResource('repairs', RepairApiController::class);


