<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// OpenRouter Chatbot Route
Route::post('/chatbot', [\App\Http\Controllers\ChatbotController::class, 'chat'])->middleware('auth');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'super_admin') return redirect()->route('super-admin.dashboard');
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'karyawan') return redirect()->route('karyawan.dashboard');
        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    // Super Admin Routes
    Route::middleware(['role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
        Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [SuperAdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [SuperAdminController::class, 'deleteUser'])->name('users.destroy');
        Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [SuperAdminController::class, 'updateSettings'])->name('settings.update');
    });

    // Admin Routes (Admin & Super Admin)
    Route::middleware(['role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/graves', [AdminController::class, 'graves'])->name('graves');
        Route::post('/graves', [AdminController::class, 'storeGrave'])->name('graves.store');
        Route::put('/graves/{grave}', [AdminController::class, 'updateGrave'])->name('graves.update');
        Route::delete('/graves/{grave}', [AdminController::class, 'deleteGrave'])->name('graves.destroy');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::post('/payments/{payment}/confirm', [AdminController::class, 'confirmPayment'])->name('payments.confirm');
        Route::put('/payments/{payment}', [AdminController::class, 'updatePayment'])->name('payments.update');
        Route::delete('/payments/{payment}', [AdminController::class, 'deletePayment'])->name('payments.destroy');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/reports/excel', [AdminController::class, 'exportExcel'])->name('reports.excel');
    });

    // Shared Routes (Admin, Super Admin, Karyawan)
    Route::middleware(['role:admin,super_admin,karyawan'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/reservations', [AdminController::class, 'reservations'])->name('reservations');
        Route::put('/reservations/{grave}', [AdminController::class, 'updateReservation'])->name('reservations.update');
        Route::get('/maintenance', [AdminController::class, 'maintenance'])->name('maintenance');
        Route::post('/maintenance', [AdminController::class, 'storeMaintenance'])->name('maintenance.store');
        Route::patch('/maintenance/{maintenance}/complete', [AdminController::class, 'completeMaintenance'])->name('maintenance.complete');
        Route::patch('/maintenance/{maintenance}/progress', [AdminController::class, 'progressMaintenance'])->name('maintenance.progress');
        Route::put('/maintenance/{maintenance}', [AdminController::class, 'updateMaintenance'])->name('maintenance.update');
        Route::delete('/maintenance/{maintenance}', [AdminController::class, 'deleteMaintenance'])->name('maintenance.destroy');
        Route::get('/heirs', [AdminController::class, 'heirs'])->name('heirs');
    });

    // Karyawan Routes
    Route::middleware(['role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/dashboard', [KaryawanController::class, 'index'])->name('dashboard');
        Route::get('/graves', [KaryawanController::class, 'graves'])->name('graves');
        Route::post('/graves', [KaryawanController::class, 'storeGrave'])->name('graves.store');
        Route::post('/graves/{grave}/update-status', [KaryawanController::class, 'updateStatus'])->name('graves.update-status');
    });

    // Customer Routes
    Route::middleware(['role:customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'index'])->name('dashboard');
        Route::get('/graves/create', [CustomerController::class, 'createGrave'])->name('graves.create');
        Route::post('/graves', [CustomerController::class, 'storeGrave'])->name('graves.store');
        Route::get('/order/{grave}/detail', [CustomerController::class, 'orderDetail'])->name('order.detail');
        Route::post('/order/{grave}/upload-proof', [CustomerController::class, 'uploadProof'])->name('order.upload_proof');
        Route::get('/order/thank-you', [CustomerController::class, 'thankYou'])->name('order.thank_you');
        Route::get('/payments', [CustomerController::class, 'payments'])->name('payments');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
