<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// --- GUEST AREA ---

// 1. Tampilkan Halaman Login (GET)
Route::get('/', function () { 
    return view('auth.login'); 
}); // Tidak perlu ->name('login') di sini

// 2. Proses Login (POST) 
// Kita beri nama 'login' di sini agar sesuai dengan action di form
Route::post('/', function () {
    $credentials = request()->only('email', 'password');

    if (Auth::attempt($credentials)) {
        request()->session()->regenerate();
        
        // Logika pengalihan otomatis
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard'); // Ke Dashboard AdminLTE
        }
        return redirect()->route('user.dashboard'); // Ke Dashboard Biru
    }

    return back()->withErrors(['email' => 'Email atau password salah!']);
})->name('login');

// 3. Proses Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// --- AUTH AREA (Middleware) ---
Route::middleware(['auth'])->group(function () {
    
    // Group Admin (Pastikan sudah buat Middleware 'admin' di langkah sebelumnya)
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/event', [AdminController::class, 'storeEvent'])->name('event.store');
        Route::get('/event/{event}/edit', [AdminController::class, 'editEvent'])->name('event.edit');
        Route::put('/event/{event}', [AdminController::class, 'updateEvent'])->name('event.update');
        Route::delete('/event/{event}', [AdminController::class, 'deleteEvent'])->name('event.delete');
        Route::post('/ticket-order/{ticketOrder}/approve', [AdminController::class, 'approveTicketOrder'])->name('ticket-order.approve');
        Route::post('/ticket-order/{ticketOrder}/reject', [AdminController::class, 'rejectTicketOrder'])->name('ticket-order.reject');
    });

    // Group User
    // Group User
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
        Route::post('/book/{id}', [UserController::class, 'bookTicket'])->name('book'); 
    });
});