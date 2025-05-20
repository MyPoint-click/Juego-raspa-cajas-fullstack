<?php

use App\Http\Controllers\Admin\PrizeCodeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/prize-codes', [PrizeCodeController::class, 'index'])->name('prize-codes.index');
    Route::post('/prize-codes', [PrizeCodeController::class, 'store'])->name('prize-codes.store');
    Route::delete('/prize-codes/{prizeCode}', [PrizeCodeController::class, 'destroy'])->name('prize-codes.destroy');
    Route::post('/prize-codes/verify', [PrizeCodeController::class, 'verifyCode'])->name('prize-codes.verify');
});
