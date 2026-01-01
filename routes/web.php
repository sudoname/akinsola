<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/eligibility', [PublicController::class, 'eligibility'])->name('eligibility');
Route::get('/awardees', [PublicController::class, 'awardees'])->name('awardees');
Route::get('/in-memory', [PublicController::class, 'inMemory'])->name('in-memory');

// Policy pages
Route::get('/policy/privacy', [PublicController::class, 'privacyPolicy'])->name('policy.privacy');
Route::get('/policy/terms', [PublicController::class, 'termsOfService'])->name('policy.terms');

// Facebook data deletion callback (required by Facebook)
Route::get('/auth/facebook/deletion', [PublicController::class, 'facebookDataDeletion'])->name('facebook.deletion');

Route::get('/dashboard', [ApplicantController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Applicant-specific routes
Route::middleware(['auth', 'verified', 'throttle:60,1'])->prefix('applicant')->name('applicant.')->group(function () {
    Route::get('/profile', [ApplicantController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [ApplicantController::class, 'updateProfile'])->name('profile.update')->middleware('throttle:10,1');
});

Route::middleware(['auth', 'verified', 'throttle:60,1'])->prefix('applications')->name('applications.')->group(function () {
    Route::get('/{application}', [ApplicantController::class, 'showApplication'])->name('show');
    Route::get('/cycle/{cycle}/track/{track}/create', [ApplicantController::class, 'createApplication'])->name('create');
    Route::post('/cycle/{cycle}/track/{track}/store', [ApplicantController::class, 'storeApplication'])->name('store')->middleware('throttle:20,1');
    Route::get('/cycle/{cycle}/track/{track}/edit', [ApplicantController::class, 'createApplication'])->name('edit');
    Route::post('/cycle/{cycle}/track/{track}/submit', [ApplicantController::class, 'submitApplication'])->name('submit')->middleware('throttle:5,1');
});

require __DIR__.'/auth.php';
