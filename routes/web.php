<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AdminGroupController;
use App\Http\Controllers\Admin\AdminSettingController;


Route::get('/', function () {
    return redirect()->route('login');
})->name('welcome');

// Employee Routes
Route::middleware(['auth', 'verified', 'employee', 'checkProfile'])->group(function () {
    Route::get('/employee', function () {
        return Inertia::render('Employee/Dashboard');
    })->name('dashboard');

    Route::get('/employee/groups', [GroupController::class, 'index'])->name('employee.groups');

    Route::post(
        '/employee/groups/join',
        [GroupController::class, 'joinGroup']
    )->name('employee.groups.join');
    Route::post(
        '/employee/groups/leave',
        [GroupController::class, 'leaveGroup']
    )->name('employee.groups.leave');
    Route::get(
        '/employee/groups/{group}',
        [GroupController::class, 'show']
    )->middleware('groupAccess')->name('employee.groups.show');
    Route::post('/groups/{group}/messages', [MessageController::class, 'store'])->middleware('groupAccess')->name('groups.messages.store');


    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/settings/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/settings/availability', [AvailabilityController::class, 'edit'])->name('availability.edit');
    Route::patch('/settings/availability', [AvailabilityController::class, 'update'])->name('availability.update');

    Route::get('/settings/change-password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::patch('/settings/change-password', [PasswordController::class, 'update'])->name('password.update');

});

// Before creating a profile
Route::middleware(['auth', 'verified', 'employee'])->group(function () {
    // Profile
    Route::get('create/about', [ProfileController::class, 'create'])
        ->name('create.about');
    Route::post('create/about', [ProfileController::class, 'store']);
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

    // Groups
    Route::get('/admin/groups', [AdminGroupController::class, 'index'])
        ->name('admin.groups.index');
    Route::post('/admin/groups/create', [AdminGroupController::class, 'store'])
        ->name('admin.groups.create');
    Route::post('/admin/groups/update/{id}', [AdminGroupController::class, 'update'])->name('admin.groups.update');


    Route::get('/admin/settings/account', [
        AdminSettingController::class,
        'account'
    ])->name('admin.settings.account');
    Route::patch('/admin/settings/account/update', [
        AdminSettingController::class,
        'updateProfile'
    ])->name('admin.account.update');

    Route::patch('/admin/settings/account/password', [
        AdminSettingController::class,
        'changePassword'
    ])->name('admin.password.update');

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

    Route::get('/admin/settings/sport', [
        AdminSettingController::class,
        'sport'
    ])->name('admin.settings.sport');

    Route::post('/admin/settings/sport/create', [
        AdminSettingController::class,
        'storeSport'
    ])->name('admin.sport.create');

    Route::patch('/admin/settings/sport/update/{id}', [
        AdminSettingController::class,
        'updateSport'
    ])->name('admin.sport.update');


    Route::post('/admin/settings/role/create', [
        AdminSettingController::class,
        'storeRole'
    ])->name('admin.role.create');

    Route::patch('/admin/settings/role/update/{id}', [
        AdminSettingController::class,
        'updateRole'
    ])->name('admin.role.update');
});


require __DIR__ . '/auth.php';


