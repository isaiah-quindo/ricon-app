<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\RaceCategoryController;
use App\Http\Controllers\Admin\DiscountCodeController;

// ----------------------------------------------------------
// Public Routes
// ----------------------------------------------------------

Route::get('/', fn() => view('welcome'));
Route::get('/rules', fn() => view('rules'))->name('rules');
Route::get('/about', fn() => view('about'))->name('about');

Route::prefix('race-category')->name('race-category.')->group(function () {
    Route::get('/100km', fn() => view('race-category.100km'))->name('100km');
    Route::get('/60km',  fn() => view('race-category.60km'))->name('60km');
    Route::get('/21km',  fn() => view('race-category.21km'))->name('21km');
    Route::get('/10km',  fn() => view('race-category.10km'))->name('10km');
});

Route::prefix('register')->name('registration.')->group(function () {
    Route::get('/', [RegistrationController::class, 'create'])->name('create');
    Route::post('/', [RegistrationController::class, 'store'])->name('store');
    Route::post('/validate-discount', [RegistrationController::class, 'validateDiscount'])->name('validateDiscount');
    Route::get('/success', [RegistrationController::class, 'success'])->name('success');
});

// ----------------------------------------------------------
// Admin Routes (auth protected)
// ----------------------------------------------------------

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Registrations
    Route::prefix('registrations')->name('registrations.')->group(function () {
        Route::get('/', [AdminRegistrationController::class, 'index'])->name('index');
        Route::get('/export', [AdminRegistrationController::class, 'export'])->name('export');
        Route::get('/{registration}', [AdminRegistrationController::class, 'show'])->name('show');
        Route::post('/{registration}/approve', [AdminRegistrationController::class, 'approve'])->name('approve');
        Route::post('/{registration}/reject', [AdminRegistrationController::class, 'reject'])->name('reject');
        Route::post('/{registration}/resend-email', [AdminRegistrationController::class, 'resendEmail'])->name('resendEmail');
        Route::patch('/{registration}/bib', [AdminRegistrationController::class, 'updateBib'])->name('updateBib');
    });

    // Race Categories
    Route::resource('race-categories', RaceCategoryController::class)->names('race-categories');

    // Discount Codes (no destroy per org policy — retire via is_active toggle)
    Route::resource('discount-codes', DiscountCodeController::class)
        ->except(['show', 'destroy'])
        ->names('discount-codes');
});

// ----------------------------------------------------------
// Auth Routes (login/logout for admin)
// ----------------------------------------------------------

require __DIR__ . '/auth.php';
