<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', function () {
    return view('login');
})->name('login_page');


Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
