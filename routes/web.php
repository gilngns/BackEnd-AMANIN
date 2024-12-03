<?php

use Illuminate\Support\Facades\Route;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/laporan/export', function () {
    return Excel::download(new LaporanExport, 'laporan_all.xlsx');
})->name('laporan.export');
