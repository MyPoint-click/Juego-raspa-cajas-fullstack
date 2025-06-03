<?php

use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\PrizeCodeController;
use App\Models\Campaign;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/prize-codes', [PrizeCodeController::class, 'index'])->name('prize-codes.index');
    Route::post('/prize-codes', [PrizeCodeController::class, 'store'])->name('prize-codes.store');
    Route::delete('/prize-codes/{prizeCode}', [PrizeCodeController::class, 'destroy'])->name('prize-codes.destroy');
    Route::post('/prize-codes/verify', [PrizeCodeController::class, 'verifyCode'])->name('prize-codes.verify');


    Route::post('/prize-codes/bulk-delete', [PrizeCodeController::class, 'bulkDelete'])
        ->name('prize-codes.bulk-delete');

    Route::resource('/campaigns', CampaignController::class);
    Route::post('/campaigns/{campaign}/set-current', [CampaignController::class, 'setCurrent'])
        ->name('campaigns.set-current');
});
