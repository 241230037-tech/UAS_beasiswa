<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'mahasiswa'])->group(function () {
    Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])
        ->name('dashboard');
});

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';