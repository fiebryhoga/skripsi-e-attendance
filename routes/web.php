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
use App\Http\Controllers\ViolationCategoryController;
use App\Http\Controllers\Admin\StudentAttendanceController;
use App\Http\Controllers\Admin\StudentViolationController;
use App\Http\Controllers\ResetDataController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| ROUTE GLOBAL (Auth User)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.markRead');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    // Route untuk menampilkan halaman setting
    Route::get('/settings', [ResetDataController::class, 'index'])->name('settings.index');

    // Route KHUSUS untuk aksi reset data
    Route::delete('/settings/reset-database', [ResetDataController::class, 'destroy'])
        ->name('settings.reset');
});

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // --- MANAJEMEN SISWA ---
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');
    Route::resource('students', StudentController::class);

    // --- MANAJEMEN GURU (UPDATED) ---
    // Saya taruh disini agar rapi dan nama routenya jadi 'admin.teachers.template'
    Route::get('teachers/template', [TeacherController::class, 'downloadTemplate'])->name('teachers.template');
    Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
    Route::resource('teachers', TeacherController::class);

    // --- MANAJEMEN KELAS ---
    Route::resource('classrooms', ClassroomController::class)->only(['index', 'show', 'update']);
    Route::post('classrooms/{classroom}/assign-student', [ClassroomController::class, 'assignStudent'])->name('classrooms.assign-student');
    Route::put('students/{student}/transfer', [ClassroomController::class, 'transferStudent'])->name('students.transfer');
    Route::delete('students/{student}/release', [ClassroomController::class, 'releaseStudent'])->name('students.release');

    // --- KATEGORI PELANGGARAN (MASTER DATA) ---
    Route::resource('violation-categories', ViolationCategoryController::class)
     ->names([
         'index' => 'violations.index',
         'create' => 'violations.create',
         'store' => 'violations.store',
         'edit' => 'violations.edit',
         'update' => 'violations.update',
         'destroy' => 'violations.destroy',
     ]);

    // --- PENCATATAN PELANGGARAN SISWA (TRANSAKSI) ---
    Route::resource('student-violations', StudentViolationController::class);

    // --- MATA PELAJARAN ---
    Route::resource('subjects', SubjectController::class)->except(['create', 'show', 'edit']);
    
    // --- JADWAL PELAJARAN ---
    Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('schedules/{classroom}', [ScheduleController::class, 'show'])->name('schedules.show');
    Route::post('schedules/{classroom}', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::delete('schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // --- PRESENSI (ATTENDANCE) ---
    // Rekap
    Route::get('attendances/recap', [StudentAttendanceController::class, 'recap'])->name('attendances.recap');
    Route::get('attendances/recap/download', [StudentAttendanceController::class, 'downloadRecap'])->name('attendances.recap.download');
    
    // API Ajax untuk Dropdown Mapel
    Route::get('api/subjects/{classroom}', [StudentAttendanceController::class, 'getSubjectsByClassroom'])->name('api.subjects.by.classroom');

    // Flow Input Presensi
    Route::get('attendances', [StudentAttendanceController::class, 'index'])->name('attendances.index');
    Route::get('attendances/{classroom}', [StudentAttendanceController::class, 'show'])->name('attendances.show');
    Route::get('attendances/{classroom}/schedule/{schedule}', [StudentAttendanceController::class, 'create'])->name('attendances.create');
    Route::post('attendances/{classroom}/schedule/{schedule}', [StudentAttendanceController::class, 'store'])->name('attendances.store');

    // --- MONITORING WALI KELAS ---
    Route::get('/homeroom', [HomeroomController::class, 'index'])->name('homeroom.index');

});

require __DIR__.'/auth.php';