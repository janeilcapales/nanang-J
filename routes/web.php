<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// ── Public Routes ──────────────────────────────────────────────
Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
})->name('login.post');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// ── Shared Auth Routes (any logged-in role) ─────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reservations — accessible by BOTH admin and customer
    // (controller already filters by role internally)
    Route::resource('reservations', ReservationController::class);

    // Transactions — accessible by BOTH admin and customer
    Route::resource('transactions', TransactionController::class);
});

// ── Admin-only Routes ───────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('dishes', DishController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // AI routes — FIX: added missing POST route for ai.process
    Route::get('/ai', [AIController::class, 'index'])->name('ai.index');
    Route::post('/ai', [AIController::class, 'process'])->name('ai.process');
});
