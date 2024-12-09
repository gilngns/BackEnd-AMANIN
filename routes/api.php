<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class,'register']);


    Route::get('/user', [AuthController::class, 'getAllUsers']); // Endpoint untuk mendapatkan data user
    Route::get('/users', [AuthController::class, 'getUser']);
    Route::get('/laporan', [LaporanController::class,'index']);
    Route::get('/laporan/image/{id}', [LaporanController::class, 'getImage']);
    Route::post('/laporan/create', [LaporanController::class,'store']);
    Route::put('/laporan/update/{id}', [LaporanController::class, 'update']);
    Route::delete('/laporan/delete/{id}', [LaporanController::class, 'destroy']);
    Route::get('/reports', [ReportsController::class,'index']);
    Route::post('/reports/create', [ReportsController::class, 'store']);
    Route::put('/reports/update/{id}', [ReportsController::class, 'update']);
    Route::delete('/reports/delete/{id}', [ReportsController::class, 'destroy']);
    Route::get('/logout', [AuthController::class, 'logout']);
