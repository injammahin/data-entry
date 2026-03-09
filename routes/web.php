<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\RecordController;
use App\Http\Controllers\Admin\DashboardController;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/imports', [ImportController::class, 'index'])->name('imports.index');
    Route::get('/imports/create', [ImportController::class, 'create'])->name('imports.create');
    Route::post('/imports', [ImportController::class, 'store'])->name('imports.store');
    Route::get('/records', [RecordController::class, 'index'])->name('records.index');
    Route::get('/imports/{import}', [ImportController::class, 'show'])->name('imports.show');
    Route::post('/imports/{import}/retry', [ImportController::class, 'retry'])->name('imports.retry');
    Route::get('/imports/{import}/records', [ImportController::class, 'records'])->name('imports.records');
    Route::resource('states', StateController::class);
});

require __DIR__.'/auth.php';