<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\Admin\EmployeeController;


Route::get('/', function () {
    return redirect()->route('login');
})->name('welcome');

// Employee Routes
Route::middleware(['auth', 'verified', 'employee'])->group(function () {
    Route::get('/employee', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/settings', function () {
        return Inertia::render('Setting');
    })->name('settings');
    // Profile
    Route::get('create/about', [ProfileController::class, 'create'])
        ->name('create.about');
    Route::post('create/about', [ProfileController::class, 'store']);
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Interests
    Route::get('create/interest', [InterestController::class, 'create'])
        ->name('create.interest');
    Route::post('create/interest', [InterestController::class, 'store']);
    Route::patch('interest', [InterestController::class, 'update'])->name('interest.update');

    // Availability
    Route::get('create/availability', [AvailabilityController::class, 'create'])
        ->name('create.availability');
    Route::post('create/availability', [AvailabilityController::class, 'store']);
});


// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');
    
    Route::get('/admin/employees', [
        EmployeeController::class,
        'index'
    ])->name('admin.employees.index');
    
    Route::post('/admin/employees/create', [
        EmployeeController::class,
        'store'
    ])->name('admin.employees.create');
    
    Route::patch('/admin/employees/update/{id}', [
        EmployeeController::class,
        'update'
    ])->name('admin.employees.update');
    
    
    // Admin Settings
    // Route::get('/admin/settings/account', [
    //     AdminSettingController::class,
    //     'account'
    // ])->name('admin.settings.account');
    
    Route::get('/admin/settings/department', [
        AdminSettingController::class,
        'department'
    ])->name('admin.settings.department');
    
    Route::post('/admin/settings/department/create', [
        AdminSettingController::class,
        'storeDepartment'
    ])->name('admin.department.create');
    
    Route::patch('/admin/settings/department/update/{id}', [
        AdminSettingController::class,
        'updateDepartment'
    ])->name('admin.department.update');
    
    Route::get('/admin/settings/role', [
        AdminSettingController::class,
        'role'
    ])->name('admin.settings.role');
    
    Route::post('/admin/settings/role/create', [
        AdminSettingController::class,
        'storeRole'
    ])->name('admin.role.create');
    
    Route::patch('/admin/settings/role/update/{id}', [
        AdminSettingController::class,
        'updateRole'
    ])->name('admin.role.update');
    });    







// Test Route
Route::get('/test', function () {
    return Inertia::render('Admin/Test');
})->name('test');


require __DIR__ . '/auth.php';


