<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BloodpressureController;
use App\Http\Controllers\Api\DiabetesController;

// Blood pressure data routes
// Store blood pressure data
Route::post('/bloodpressure-store/{user_id}', [BloodpressureController::class, 'store']);

// Fetch blood pressure data for the authenticated user
Route::get('/bloodpressure-show/{user_id}', [BloodpressureController::class, 'show']);

// Download blood pressure data as a PDF
Route::get('/bloodpressure-download-pdf/{user_id}', [BloodpressureController::class, 'downloadPDF']);

// Blood sugar data routes for API
// Store blood sugar data
Route::post('/diabetes-store/{user_id}', [DiabetesController::class, 'store']);
// Fetch blood sugar data for the authenticated user
Route::get('/diabetes-show/{user_id}', [DiabetesController::class, 'show']);
 // Download blood sugar data as a PDF
Route::get('/diabetes-download-pdf/{user_id}', [DiabetesController::class, 'downloadPDF']);