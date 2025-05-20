<?php

use App\Http\Controllers\Admin\PrizeCodeController;
use App\Http\Controllers\BoxGame\BoxController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScratchGame\ScratchController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Juegos
Route::get('/scratch-game', [ScratchController::class, 'index'])->name('scratch-game.index');
Route::post('/scratch-game/get-code', [ScratchController::class, 'getCode'])->name('scratch-game.get-code');

Route::get('/box-game', [BoxController::class, 'index'])->name('box-game.index');
Route::post('/box-game/get-code', [BoxController::class, 'getCode'])->name('box-game.get-code');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
