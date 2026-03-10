<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\RecordController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\BusinessController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
    Route::get('/saved-lists/{searchList}', [UserDashboardController::class, 'openSavedList'])
    ->name('saved-lists.open');
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('states', StateController::class);

    Route::get('/imports', [ImportController::class, 'index'])->name('imports.index');
    Route::get('/imports/create', [ImportController::class, 'create'])->name('imports.create');
    Route::post('/imports', [ImportController::class, 'store'])->name('imports.store');
    Route::get('/imports/{import}', [ImportController::class, 'show'])->name('imports.show');
    Route::post('/imports/{import}/retry', [ImportController::class, 'retry'])->name('imports.retry');
    Route::get('/imports/{import}/records', [ImportController::class, 'records'])->name('imports.records');

    Route::get('/records', [RecordController::class, 'index'])->name('records.index');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::get('/us-business', [BusinessController::class, 'index'])->name('us-business.index');

    Route::get('/us-business/results', [BusinessController::class, 'results'])->name('us-business.results');
    Route::get('/us-business/results/insights', [BusinessController::class, 'insights'])->name('us-business.insights');
    Route::get('/us-business/results/details', [BusinessController::class, 'details'])->name('us-business.details');
    Route::get('/us-business/results/map', [BusinessController::class, 'map'])->name('us-business.map');

    Route::post('/us-business/save-list', [BusinessController::class, 'saveList'])->name('us-business.save-list');

    Route::get('/us-business/export/csv', [BusinessController::class, 'exportCsv'])->name('us-business.export.csv');
    Route::get('/us-business/export/xlsx', [BusinessController::class, 'exportXlsx'])->name('us-business.export.xlsx');
    Route::get('/saved-lists/{searchList}', [DashboardController::class, 'openSavedList'])
    ->name('saved-lists.open');
});

require __DIR__.'/auth.php';