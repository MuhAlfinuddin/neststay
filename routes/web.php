<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SuperAdminController;

// 1. Guest Public Routes
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 2. Scoped Authenticated Routes (for Owners & Staffs)
Route::middleware(['auth', 'role:owner,staff'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rooms
    Route::resource('rooms', RoomController::class)->except(['show']);
    
    // Guests
    Route::resource('guests', GuestController::class)->except(['show']);
    
    // Reservations & Operations
    Route::post('/reservations/{reservation}/check-in', [ReservationController::class, 'checkIn'])->name('reservations.check-in');
    Route::post('/reservations/{reservation}/check-out', [ReservationController::class, 'checkOut'])->name('reservations.check-out');
    Route::resource('reservations', ReservationController::class);
    
    // Payments
    Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

// 3. Scoped Authenticated Routes (Owner Only)
Route::middleware(['auth', 'role:owner'])->group(function () {
    // Staff Management
    Route::resource('staff', StaffController::class)->only(['index', 'create', 'store', 'destroy']);
});

// 4. Scoped Authenticated Routes (Super Admin Only)
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/homestays', [SuperAdminController::class, 'dashboard'])->name('homestays.index');
    Route::post('/homestays/{homestay}/toggle-status', [SuperAdminController::class, 'toggleStatus'])->name('homestays.toggle-status');
});
