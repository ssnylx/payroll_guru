<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SelfAttendanceController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\AllowanceTypeController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\Auth\ChangePasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Authentication routes
require __DIR__.'/auth.php';

// Change password routes (for first-time login)
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('password.change');
});

// Protected routes
Route::middleware(['auth', 'verified', 'password.changed'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Teachers routes
    Route::middleware(['role:admin,bendahara'])->group(function () {
        Route::resource('teachers', TeacherController::class)->except(['show']);



    });

    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {
        // Shifts management
        Route::resource('shifts', ShiftController::class);
        Route::patch('/shifts/{shift}/toggle-status', [ShiftController::class, 'toggleStatus'])->name('shifts.toggle-status');

        // Positions management
        Route::resource('positions', PositionController::class);
        Route::patch('/positions/{position}/toggle-status', [PositionController::class, 'toggleStatus'])->name('positions.toggle-status');

        // Education Levels management
        Route::resource('education-levels', EducationLevelController::class);
        Route::patch('/education-levels/{educationLevel}/toggle-status', [EducationLevelController::class, 'toggleStatus'])->name('education-levels.toggle-status');

        // Allowance Types management
        Route::resource('allowance-types', AllowanceTypeController::class);
        Route::patch('/allowance-types/{allowanceType}/toggle-status', [AllowanceTypeController::class, 'toggleStatus'])->name('allowance-types.toggle-status');

        // Subjects management
        Route::resource('subjects', App\Http\Controllers\SubjectController::class);

    });

    // Teachers show route - accessible by all authenticated users with restrictions inside controller
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');

    // Attendance routes
    Route::resource('attendances', AttendanceController::class);

    // Salary routes
    Route::resource('salaries', SalaryController::class);
    Route::get('/salaries/{salary}/slip', [SalaryController::class, 'slip'])->name('salaries.slip');
    Route::get('/salaries-bulk/create', [SalaryController::class, 'bulkCreate'])->name('salaries.bulk-create');
    Route::post('/salaries-bulk', [SalaryController::class, 'bulkStore'])->name('salaries.bulk-store');
    Route::get('/cetak', [SalaryController::class, 'cetak'])->name('cetak');

    // Leave Request routes
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::patch('/leave-requests/{leave_request}/status', [LeaveRequestController::class, 'updateStatus'])->name('leave-requests.update-status');

    // Self Attendance routes (for teachers only)
    Route::middleware(['role:guru'])->group(function () {
        Route::get('/self-attendance', [SelfAttendanceController::class, 'index'])->name('self-attendance.index');
        Route::post('/self-attendance', [SelfAttendanceController::class, 'store'])->name('self-attendance.store');
    });

    // Foundation Settings routes (only for admin)
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/foundation-settings', [\App\Http\Controllers\FoundationSettingController::class, 'index'])->name('foundation-settings.index');
        Route::post('/foundation-settings', [\App\Http\Controllers\FoundationSettingController::class, 'update'])->name('foundation-settings.update');
    });
});
