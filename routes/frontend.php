<?php

use App\Http\Controllers\Frontend\LandingController;
use App\Http\Controllers\Frontend\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend / Public Routes
|--------------------------------------------------------------------------
*/

// ── Landing pages ────────────────────────────────────────────────────────────
Route::get('/',                     [LandingController::class, 'index'])->name('frontend');
Route::get('/about-us',             [LandingController::class, 'aboutUs'])->name('about_us');
Route::get('/privacy-policy',       [LandingController::class, 'privacyPolicy'])->name('privacy_policy');
Route::get('/return-policy',        [LandingController::class, 'returnPolicy'])->name('return_policy');
Route::get('/terms-and-conditions', [LandingController::class, 'termsAndConditions'])->name('terms_and_condition');
Route::post('/contact-us',          [LandingController::class, 'contactStore'])->name('contact-us.store');

// ── Notifications ────────────────────────────────────────────────────────────
Route::get('notification/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notification.mark-all-as-read');
Route::get('notification/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notification.mark-as-read');
