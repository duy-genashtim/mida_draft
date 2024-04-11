<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [RegisterController::class, 'save']);
Route::get('/fetchGeneralIndustry', [RegisterController::class, 'fetchGeneralIndustry']);
Route::get('/fetchSubIndustry/{id}', [RegisterController::class, 'fetchSubIndustry']);