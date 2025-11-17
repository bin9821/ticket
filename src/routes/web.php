<?php

use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::post('/profile/purchase/{ticket}', [ProfileController::class, 'purchase'])->name('profile.purchase');
//     //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


Route::middleware('auth')->group(function () {
    Route::get('/ticket/buy', [TicketController::class, 'buy'])->name('ticket.buy');
    Route::get('/ticket/manage', [TicketController::class, 'manage'])->name('ticket.manage');
    Route::post('/ticket/purchase/{ticket_id}', [TicketController::class, 'purchase'])->name('ticket.purchase');
    // Route::post('/ticket/resetAllTickets', [TicketController::class, 'resetAllTickets'])->name('ticket.resetAllTickets');
});

Route::get('/csv/download', [UserInfoController::class, 'csvDownload'])->name('csvDownload');

require __DIR__.'/auth.php';

Route::fallback(function () {
    return redirect()->route('login');
});