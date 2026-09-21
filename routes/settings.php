<?php

use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'updateProfileInformation'])->name('profile.update-profile-information');
    Route::delete('settings/profile', [ProfileController::class, 'deleteUser'])->name('profile.delete-user');

    Route::inertia('settings/appearance', 'settings/appearance')->name('appearance.edit');
});
