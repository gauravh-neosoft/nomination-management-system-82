<?php

use App\Http\Controllers\Admin\EventsAdminController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

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

    Route::get('admin/create-new-event', [EventsAdminController::class, 'create'])->name('admin-new-event-form');
    Route::post('admin/create-new-event', [EventsAdminController::class, 'store'])->name('admin-save-new-event');

    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin-dashboard');
    Route::get('admin/roles', [AdminController::class, 'roles'])->name('admin-roles');
    Route::get('admin/users', [AdminController::class, 'users'])->name('admin-users');
    Route::get('admin/events', [AdminController::class, 'events'])->name('admin-events');
    Route::get('admin/queue', [AdminController::class, 'queue'])->name('admin-queue');
    Route::get('admin/contacts', [AdminController::class, 'contacts'])->name('admin-contacts');
    Route::get('admin/exclusion', [AdminController::class, 'exclusion'])->name('admin-exclusion');
    // MDM Hub Routes
    Route::get('admin/mdm', [AdminController::class, 'mdm'])->name('admin-mdm');
    
    // MDM Units AJAX Endpoints
    Route::get('admin/mdm/units', [AdminController::class, 'getUnits'])->name('admin-mdm-get-units');
    Route::post('admin/mdm/units', [AdminController::class, 'storeUnit'])->name('admin-mdm-store-unit');
    Route::post('admin/mdm/units/{id}/update', [AdminController::class, 'updateUnit'])->name('admin-mdm-update-unit');
    Route::post('admin/mdm/units/{id}/toggle', [AdminController::class, 'toggleUnitStatus'])->name('admin-mdm-toggle-unit');
    Route::post('admin/mdm/units/{id}/delete', [AdminController::class, 'deleteUnit'])->name('admin-mdm-delete-unit');

    // MDM Sub Units AJAX Endpoints
    Route::get('admin/mdm/sub-units', [AdminController::class, 'getSubUnits'])->name('admin-mdm-get-subunits');
    Route::post('admin/mdm/sub-units', [AdminController::class, 'storeSubUnit'])->name('admin-mdm-store-subunit');
    Route::post('admin/mdm/sub-units/{id}/update', [AdminController::class, 'updateSubUnit'])->name('admin-mdm-update-subunit');
    Route::post('admin/mdm/sub-units/{id}/toggle', [AdminController::class, 'toggleSubUnitStatus'])->name('admin-mdm-toggle-subunit');
    Route::post('admin/mdm/sub-units/{id}/delete', [AdminController::class, 'deleteSubUnit'])->name('admin-mdm-delete-subunit');

    // MDM GDPR Compliance AJAX Endpoints
    Route::get('admin/mdm/gdpr', [AdminController::class, 'getGdpr'])->name('admin-mdm-get-gdpr');
    Route::post('admin/mdm/gdpr', [AdminController::class, 'storeGdpr'])->name('admin-mdm-store-gdpr');
    Route::post('admin/mdm/gdpr/{id}/update', [AdminController::class, 'updateGdpr'])->name('admin-mdm-update-gdpr');
    Route::post('admin/mdm/gdpr/{id}/toggle', [AdminController::class, 'toggleGdprStatus'])->name('admin-mdm-toggle-gdpr');
    Route::post('admin/mdm/gdpr/{id}/delete', [AdminController::class, 'deleteGdpr'])->name('admin-mdm-delete-gdpr');

    Route::get('admin/cms', [AdminController::class, 'cms'])->name('admin-cms');
    Route::get('admin/reports', [AdminController::class, 'reports'])->name('admin-reports');
    Route::get('admin/domain', [AdminController::class, 'domain'])->name('admin-domain');
    Route::post('admin/events/{id}/delete', [AdminController::class, 'destroy'])->name('admin-delete-event');
});


