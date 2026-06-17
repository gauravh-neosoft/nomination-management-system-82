<?php

use App\Http\Controllers\Admin\EventsAdminController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NominatorController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', function () {
    return view('login');
})->name('login');


Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [DashboardController::class, 'profile'])->name('profile');

    // Nominator Routes
    Route::get('nominator/dashboard', [NominatorController::class, 'dashboard'])->name('nominator-dashboard');
    Route::get('nominator/events/active', [NominatorController::class, 'activeEvents'])->name('nominator-active-events');
    Route::get('nominator/events/completed', [NominatorController::class, 'completedEvents'])->name('nominator-completed-events');
    Route::get('nominator/nominations', [NominatorController::class, 'nominations'])->name('nominator-nominations');
    Route::get('nominator/events/{id}/nominate', [NominatorController::class, 'showNominationForm'])->name('nominator-nominate-form');
    Route::post('nominator/events/{id}/nominate', [NominatorController::class, 'submitNomination'])->name('nominator-submit-nomination');
    Route::get('nominator/events/{id}/download-template', [NominatorController::class, 'downloadTemplate'])->name('nominator-download-template');
    Route::post('nominator/events/{id}/bulk-upload', [NominatorController::class, 'bulkUpload'])->name('nominator-bulk-upload');

    Route::get('admin/create-new-event', [EventsAdminController::class, 'create'])->name('admin-new-event-form');
    Route::post('admin/create-new-event', [EventsAdminController::class, 'store'])->name('admin-save-new-event');

    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin-dashboard');
    Route::get('admin/roles', [AdminController::class, 'roles'])->name('admin-roles');
    Route::get('admin/users', [AdminController::class, 'users'])->name('admin-users');
    Route::get('admin/events', [AdminController::class, 'events'])->name('admin-events');
    Route::get('admin/queue', [AdminController::class, 'queue'])->name('admin-queue');
    Route::get('admin/contacts', [AdminController::class, 'contacts'])->name('admin-contacts');
    Route::get('admin/exclusion', [AdminController::class, 'exclusion'])->name('admin-exclusion');
    Route::get('admin/mdm', [AdminController::class, 'mdm'])->name('admin-mdm');
    Route::get('admin/cms', [AdminController::class, 'cms'])->name('admin-cms');
    Route::get('admin/reports', [AdminController::class, 'reports'])->name('admin-reports');
    Route::get('admin/domain', [AdminController::class, 'domain'])->name('admin-domain');
    Route::post('admin/events/{id}/delete', [AdminController::class, 'destroy'])->name('admin-delete-event');
});
