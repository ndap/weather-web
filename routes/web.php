<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;



Route::get('/logout', [SessionController::class, 'destroy'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [WeatherController::class, 'index'])->name('dashboard');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('landingpage');
})->name('landingPage');
Route::get('/login', [SessionController::class, 'index'])->name('login');
Route::post('/login', [SessionController::class,'store'])->name('login');
Route::get('/register', [RegisterController::class,'showRegister'])->name('register');
Route::post('/register', [RegisterController::class,'register'])->name('register');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('locations', LocationController::class);
    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
});