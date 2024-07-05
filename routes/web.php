<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RuasJalanController;
use App\Http\Controllers\RegionController;

// Dashboard dan Home bisa diakses tanpa login
Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('Dashboard.dashboard');
})->name('dashboard');


// Rute untuk RuasJalan
Route::get('/RuasJalan', [RuasJalanController::class, 'index'])->name('RuasJalan.index');

Route::get('/RuasJalan/create', [RuasJalanController::class, 'create'])->name('RuasJalan.create');
Route::post('/RuasJalan/store', [RuasJalanController::class, 'store'])->name('RuasJalan.store');

Route::get('/ruasjalan/{id}/edit', [RuasJalanController::class, 'edit'])->name('RuasJalan.edit');
Route::put('/ruasjalan/{id}', [RuasJalanController::class, 'update'])->name('RuasJalan.update');

Route::delete('/RuasJalan/{id}', [RuasJalanController::class, 'destroy'])->name('RuasJalan.destroy');

// Route::resource('RuasJalan', RuasJalanController::class);

// Route controller untuk otentikasi
Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');

    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');
    
    Route::post('logout', 'logout')->name('logout');
    // Logout sebaiknya menggunakan metode POST
    // Route::post('logout', 'logout')->middleware('auth')->name('logout');

});

// Mengamankan halaman profile dengan middleware 'auth'
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
});

Route::middleware('auth:api')->get('/ruasjalan', [RuasJalanController::class, 'index']);