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

Route::get('/send-test-mail', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('This is a test email from the Nomination Management System to verify mail functionality.', function ($message) {
            $message->to('hedagaurav93@gmail.com')
                    ->subject('Test Email Functionality');
        });
        return response()->json([
            'success' => true,
            'message' => 'Test mail successfully sent to hedagaurav93@gmail.com! Please check your storage/logs/laravel.log file (since MAIL_MAILER=log is configured) or your inbox if you have SMTP configured.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send test mail: ' . $e->getMessage()
        ], 500);
    }
})->name('send-test-mail');


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
    Route::get('admin/users', [AdminController::class, 'users'])->name('admin-users');
    Route::get('admin/users/create', [AdminController::class, 'createUserForm'])->name('admin-users-create');
    Route::post('admin/users/store', [AdminController::class, 'storeUser'])->name('admin-users-store');
    Route::post('admin/users/{id}/toggle', [AdminController::class, 'toggleUserStatus'])->name('admin-users-toggle');
    Route::get('admin/events', [AdminController::class, 'events'])->name('admin-events');
    Route::get('admin/queue', [AdminController::class, 'queue'])->name('admin-queue');
    Route::get('admin/contacts', [AdminController::class, 'contacts'])->name('admin-contacts');
    Route::get('admin/exclusion', [AdminController::class, 'exclusion'])->name('admin-exclusion');
    // MDM Hub Routes
    Route::get('admin/mdm', [AdminController::class, 'mdm'])->name('admin-mdm');

    // Dropdown Management Routes
    Route::get('admin/dropdown', [AdminController::class, 'dropdown'])->name('admin-dropdown');
    
    // Dropdown Units AJAX Endpoints
    Route::get('admin/dropdown/units', [AdminController::class, 'getUnits'])->name('admin-dropdown-get-units');
    Route::post('admin/dropdown/units', [AdminController::class, 'storeUnit'])->name('admin-dropdown-store-unit');
    Route::post('admin/dropdown/units/{id}/update', [AdminController::class, 'updateUnit'])->name('admin-dropdown-update-unit');
    Route::post('admin/dropdown/units/{id}/toggle', [AdminController::class, 'toggleUnitStatus'])->name('admin-dropdown-toggle-unit');
    Route::post('admin/dropdown/units/{id}/delete', [AdminController::class, 'deleteUnit'])->name('admin-dropdown-delete-unit');

    // Dropdown Sub Units AJAX Endpoints
    Route::get('admin/dropdown/sub-units', [AdminController::class, 'getSubUnits'])->name('admin-dropdown-get-subunits');
    Route::post('admin/dropdown/sub-units', [AdminController::class, 'storeSubUnit'])->name('admin-dropdown-store-subunit');
    Route::post('admin/dropdown/sub-units/{id}/update', [AdminController::class, 'updateSubUnit'])->name('admin-dropdown-update-subunit');
    Route::post('admin/dropdown/sub-units/{id}/toggle', [AdminController::class, 'toggleSubUnitStatus'])->name('admin-dropdown-toggle-subunit');
    Route::post('admin/dropdown/sub-units/{id}/delete', [AdminController::class, 'deleteSubUnit'])->name('admin-dropdown-delete-subunit');

    // Dropdown GDPR Compliance AJAX Endpoints
    Route::get('admin/dropdown/gdpr', [AdminController::class, 'getGdpr'])->name('admin-dropdown-get-gdpr');
    Route::post('admin/dropdown/gdpr', [AdminController::class, 'storeGdpr'])->name('admin-dropdown-store-gdpr');
    Route::post('admin/dropdown/gdpr/{id}/update', [AdminController::class, 'updateGdpr'])->name('admin-dropdown-update-gdpr');
    Route::post('admin/dropdown/gdpr/{id}/toggle', [AdminController::class, 'toggleGdprStatus'])->name('admin-dropdown-toggle-gdpr');
    Route::post('admin/dropdown/gdpr/{id}/delete', [AdminController::class, 'deleteGdpr'])->name('admin-dropdown-delete-gdpr');

    Route::get('admin/cms', [AdminController::class, 'cms'])->name('admin-cms');
    Route::get('admin/reports', [AdminController::class, 'reports'])->name('admin-reports');
    Route::get('admin/domain', [AdminController::class, 'domain'])->name('admin-domain');
    Route::post('admin/events/{id}/delete', [AdminController::class, 'destroy'])->name('admin-delete-event');
    Route::post('admin/events/{id}/update', [EventsAdminController::class, 'update'])->name('admin-update-event');

    // Nominator Event Limits CRUD
    Route::get('admin/nominator-limits', [AdminController::class, 'nominatorLimits'])->name('admin-nominator-limits');
    Route::post('admin/nominator-limits', [AdminController::class, 'storeNominatorLimit'])->name('admin-nominator-limits-store');
    Route::post('admin/nominator-limits/{id}/update', [AdminController::class, 'updateNominatorLimit'])->name('admin-nominator-limits-update');
    Route::post('admin/nominator-limits/{id}/delete', [AdminController::class, 'deleteNominatorLimit'])->name('admin-nominator-limits-delete');

    // Event Ops Routes
    Route::prefix('event-ops')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\EventOpsController::class, 'dashboard'])->name('event-ops-dashboard');
        Route::get('events/active', [\App\Http\Controllers\EventOpsController::class, 'activeEvents'])->name('event-ops-active-events');
        Route::get('events/completed', [\App\Http\Controllers\EventOpsController::class, 'completedEvents'])->name('event-ops-completed-events');
        Route::get('nominations', [\App\Http\Controllers\EventOpsController::class, 'nominations'])->name('event-ops-nominations');
        Route::get('events/{id}/nominations', [\App\Http\Controllers\EventOpsController::class, 'eventNominations'])->name('event-ops-event-nominations');
        Route::get('reports', [\App\Http\Controllers\EventOpsController::class, 'reports'])->name('event-ops-reports');
        Route::get('dnc-contact', [\App\Http\Controllers\EventOpsController::class, 'dncContact'])->name('event-ops-dnc-contact');

        Route::post('nominations/{id}/remark', [\App\Http\Controllers\EventOpsController::class, 'addRemark'])->name('event-ops-add-remark');
        Route::post('nominations/{id}/update', [\App\Http\Controllers\EventOpsController::class, 'updateNomination'])->name('event-ops-update-nomination');
        Route::post('dnc-contact/save', [\App\Http\Controllers\EventOpsController::class, 'saveDncContact'])->name('event-ops-save-dnc-contact');
        Route::post('dnc-contact/upload', [\App\Http\Controllers\EventOpsController::class, 'uploadDncContact'])->name('event-ops-upload-dnc-contact');
        Route::post('dnc-contact/{id}/delete', [\App\Http\Controllers\EventOpsController::class, 'deleteDncContact'])->name('event-ops-delete-dnc-contact');
        Route::post('dnc-domain/save', [\App\Http\Controllers\EventOpsController::class, 'saveDncDomain'])->name('event-ops-save-dnc-domain');
        Route::post('dnc-domain/upload', [\App\Http\Controllers\EventOpsController::class, 'uploadDncDomain'])->name('event-ops-upload-dnc-domain');
        Route::post('dnc-domain/{id}/delete', [\App\Http\Controllers\EventOpsController::class, 'deleteDncDomain'])->name('event-ops-delete-dnc-domain');
    });
});






