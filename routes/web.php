<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\Auth\CreateEmployeeAboutController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canCreate' => Route::has('create'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//         'company' => 'GoCo , Inc • SL',
//     ]);
// });

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('create/about', [ProfileController::class, 'create'])
        ->name('create.about');
    Route::post('create/about', [ProfileController::class, 'store']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Interests
    Route::get('create/interest', [InterestController::class, 'create'])
        ->name('create.interest');
    Route::post('create/interest', [InterestController::class, 'store']);

    // Availability
    Route::get('create/availability', [AvailabilityController::class, 'create'])
        ->name('create.availability');
    Route::post('create/availability', [AvailabilityController::class, 'store']);

});

require __DIR__ . '/auth.php';


