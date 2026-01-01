<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\HomeroomController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\ViolationCategoryController;
use App\Http\Controllers\Admin\StudentAttendanceController;
use App\Http\Controllers\Admin\StudentViolationController;
use App\Http\Controllers\ResetDataController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\WhatsAppGatewayController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| ROUTE GLOBAL
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    
});

/*
|--------------------------------------------------------------------------
| (Prefix: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // ====================================================
    // 1. KHUSUS ADMIN (FULL CONTROL)
    // ====================================================
    Route::middleware(['role:admin'])->group(function () {
        
        Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
        Route::get('students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');
        Route::resource('students', StudentController::class);

        Route::get('teachers/template', [TeacherController::class, 'downloadTemplate'])->name('teachers.template');
        Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
        Route::resource('teachers', TeacherController::class);

        Route::resource('classrooms', ClassroomController::class)->only(['index', 'show', 'update']);
        Route::post('classrooms/{classroom}/assign-student', [ClassroomController::class, 'assignStudent'])->name('classrooms.assign-student');
        Route::put('students/{student}/transfer', [ClassroomController::class, 'transferStudent'])->name('students.transfer');
        Route::delete('students/{student}/release', [ClassroomController::class, 'releaseStudent'])->name('students.release');
        
        Route::resource('subjects', SubjectController::class)->except(['create', 'show', 'edit']);

        Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('schedules/{classroom}', [ScheduleController::class, 'show'])->name('schedules.show');
        Route::post('schedules/{classroom}', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::delete('schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
        Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');

        Route::get('/settings', [ResetDataController::class, 'index'])->name('settings.index');
        Route::delete('/settings/reset-database', [ResetDataController::class, 'destroy'])->name('settings.reset');
        
        Route::get('/wa-gateway-connect', [WhatsAppGatewayController::class, 'connect'])->name('wa.connect');
    });


    // ====================================================
    // 2. ADMIN & GURU TATIB
    // ====================================================
    Route::middleware(['role:admin,guru_tatib'])->group(function () {
        
        
        Route::resource('violation-categories', ViolationCategoryController::class)
        ->names([
            'index' => 'violations.index',
            'create' => 'violations.create',
            'store' => 'violations.store',
            'edit' => 'violations.edit',
            'update' => 'violations.update',
            'destroy' => 'violations.destroy',
        ]);
        
        Route::resource('student-violations', StudentViolationController::class);

        
    });


    // ====================================================
    // 3. ADMIN, GURU MAPEL, WALI KELAS (AKADEMIK)
    // ====================================================
    Route::middleware(['role:admin,guru_mapel,wali_kelas,guru_tatib'])->group(function () {
        
        Route::get('attendances/recap', [StudentAttendanceController::class, 'recap'])->name('attendances.recap');
        Route::get('attendances/recap/download', [StudentAttendanceController::class, 'downloadRecap'])->name('attendances.recap.download');
        Route::get('api/subjects/{classroom}', [StudentAttendanceController::class, 'getSubjectsByClassroom'])->name('api.subjects.by.classroom');

        Route::get('attendances', [StudentAttendanceController::class, 'index'])->name('attendances.index');
        Route::get('attendances/{classroom}', [StudentAttendanceController::class, 'show'])->name('attendances.show');
        
        Route::get('attendances/{classroom}/schedule/{schedule}', [StudentAttendanceController::class, 'create'])->name('attendances.create');
        Route::post('attendances/{classroom}/schedule/{schedule}', [StudentAttendanceController::class, 'store'])->name('attendances.store');
    });


    // ====================================================
    // 4. KHUSUS WALI KELAS
    // ====================================================

    Route::middleware(['role:wali_kelas'])
        ->prefix('homeroom')           
        ->name('homeroom.')            
        ->group(function () {

            Route::get('/', [HomeroomController::class, 'index'])->name('index');

            Route::get('/student/{student}', [HomeroomController::class, 'show'])->name('student.show');

            Route::get('/classroom/{classroom}/violations', [HomeroomController::class, 'violations'])->name('violations');

        });

});

require __DIR__.'/auth.php';