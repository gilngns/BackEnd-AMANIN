<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ReportsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/laporan', [LaporanController::class,'index']);
    Route::post('/laporan/create', [LaporanController::class,'store']);
    Route::put('/laporan/update/{id}', [LaporanController::class, 'update']);
    Route::delete('/laporan/delete/{id}', [LaporanController::class, 'destroy']);
    Route::get('/reports', [ReportsController::class,'index']);
    Route::post('/reports/create', [ReportsController::class, 'store']);
    Route::put('/reports/update/{id}', [ReportsController::class, 'update']);
    Route::delete('/reports/delete/{id}', [ReportsController::class, 'destroy']);
    Route::post('/register', [AuthController::class,'register']);
    Route::get('/logout', [AuthController::class, 'logout']);
});