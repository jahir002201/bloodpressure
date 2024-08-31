<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BloodpressureController;
use App\Http\Controllers\DiabetesController;

Route::get('/home', function () {
    return view('dashboard.layouts.app');
})->name('home');

Auth::routes();

Route::get('/blood',[BloodpressureController::class,'index'])->name('blood');
Route::post('/blood-store',[BloodpressureController::class,'store'])->name('blood-store');
Route::get('/blood/results',[BloodpressureController::class,'show'])->name('blood-result');
Route::get('/blood-pressure/download-pdf', [BloodPressureController::class, 'downloadPDF'])->name('blood-pressure.downloadPDF');

Route::get('/diabetes', [DiabetesController::class, 'index'])->name('diabetes.index');
Route::post('/diabetes/store', [DiabetesController::class, 'store'])->name('diabetes.store');
Route::get('/diabetes/show', [DiabetesController::class, 'show'])->name('diabetes.show');
Route::get('/diabetes/download-pdf', [DiabetesController::class, 'downloadPDF'])->name('diabetes.downloadPDF');