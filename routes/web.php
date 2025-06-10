<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products/datatables', [ProductController::class, 'useDatatables'])->name('products.datatables');
    Route::resource('products', ProductController::class);

    Route::get('/categories/datatables', [CategoryController::class, 'useDatatables'])->name('categories.datatables');
    Route::resource('categories', CategoryController::class);

    Route::resource('settings', SettingController::class)->except(['create', 'store', 'show']);
    Route::get('/settings/datatables', [SettingController::class, 'useDatatables'])->name('settings.datatables');

    Route::get('/roles/datatables', [RoleController::class, 'useDatatables'])->name('roles.datatables');
    Route::resource('roles', RoleController::class);

    Route::get('/users/datatables', [UserController::class, 'useDatatables'])->name('users.datatables');
    Route::resource('users', UserController::class);

    Route::get('/reports/datatables', [ReportsController::class, 'useDatatables'])->name('reports.datatables');
    Route::resource('reports', ReportsController::class);

    Route::get('/suppliers/datatables', [SupplierController::class, 'useDatatables'])->name('suppliers.datatables');
    Route::resource('suppliers', SupplierController::class);
});

require __DIR__.'/auth.php';
