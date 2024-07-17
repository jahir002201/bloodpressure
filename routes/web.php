<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BloodPressureController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route for displaying blood pressure submission form
Route::get('/blood-pressure', [BloodPressureController::class, 'index']);

// Route for storing blood pressure data
Route::post('/blood-pressure', [BloodPressureController::class, 'store']);

// Route for displaying blood pressure results
Route::get('/blood-pressure/results', [BloodPressureController::class, 'show']);

// Route for downloading blood pressure results as PDF
Route::get('/blood-pressure/results/download', [BloodPressureController::class, 'downloadPDF'])->name('blood-pressure.downloadPDF');