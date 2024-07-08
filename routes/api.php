<?php

use App\Http\Controllers\BillingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Rotas BillingController
Route::post('/billing/process-file', [BillingController::class, 'processFile']);
Route::get('/billings', [BillingController::class, 'listBillings']);
