<?php

use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SettingController;
use App\Http\Controllers\User\UserEmailVerifyController;

Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
Route::post('profile-update', [ProfileController::class, 'userProfileUpdate'])->name('profile_update')->middleware('isDemo');
Route::post('add-institution', [ProfileController::class, 'addInstitution'])->name('add_institution')->middleware('isDemo');

Route::get('settings', [SettingController::class, 'settings'])->name('settings');
Route::post('change-password', [SettingController::class, 'changePasswordUpdate'])->name('change-password')->middleware('isDemo');
Route::post('setting-update', [SettingController::class, 'settingUpdate'])->name('setting_update');

Route::post('phone-verification-sms-send', [ProfileController::class, 'smsSend'])->name('phone.verification.sms.send');
Route::get('phone-verification-sms-resend', [ProfileController::class, 'smsReSend'])->name('phone.verification.sms.resend');
Route::post('phone-verification-sms-verify', [ProfileController::class, 'smsVerify'])->name('phone.verification.sms.verify');

Route::post('email/verified/{token}', [UserEmailVerifyController::class, 'emailVerified'])->name('email.verified')->withoutMiddleware('is_email_verify');
Route::get('email/verify/{token}', [UserEmailVerifyController::class, 'emailVerify'])->name('email.verify')->withoutMiddleware('is_email_verify');
Route::post('email/verify/resend/{token}', [UserEmailVerifyController::class, 'emailVerifyResend'])->name('email.verify.resend')->withoutMiddleware('is_email_verify');
