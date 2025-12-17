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
// TAMBAHAN PENTING DI SINI:
use App\Http\Controllers\ViolationController; 
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

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

    // --- MANAJEMEN KELAS ---
    Route::resource('classrooms', ClassroomController::class)->only(['index', 'show', 'update']);
    Route::post('classrooms/{classroom}/assign-student', [ClassroomController::class, 'assignStudent'])->name('classrooms.assign-student');
    Route::put('students/{student}/transfer', [ClassroomController::class, 'transferStudent'])->name('students.transfer');
    Route::delete('students/{student}/release', [ClassroomController::class, 'releaseStudent'])->name('students.release');

    // --- MANAJEMEN GURU ---
    Route::resource('teachers', TeacherController::class);

    // --- KATEGORI PELANGGARAN (MASTER DATA) ---
    // URL: /admin/violation-categories
    // Route Name: admin.violations.index (Sesuai Sidebar)
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
    // URL: /admin/student-violations
    // Route Name: admin.student-violations.*
    // Kita ganti nama routenya agar tidak bentrok sama sekali dengan kategori
    Route::resource('student-violations', ViolationController::class);

    
    // ... di dalam group prefix admin
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class)->except(['create', 'show', 'edit']);
    

    // Halaman Index (Pilih Kelas)
    Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');

    // Halaman Detail Jadwal per Kelas
    Route::get('schedules/{classroom}', [ScheduleController::class, 'show'])->name('schedules.show');

    // Simpan Jadwal
    Route::post('schedules/{classroom}', [ScheduleController::class, 'store'])->name('schedules.store');

    // Hapus Jadwal
    Route::delete('schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // REKAP (Taruh paling atas di blok Presensi)
    Route::get('attendances/recap', [StudentAttendanceController::class, 'recap'])->name('attendances.recap');

    // Halaman Pilih Kelas
    Route::get('attendances', [StudentAttendanceController::class, 'index'])->name('attendances.index');

    // Presensi: Halaman Pilih Mapel di Tanggal Tertentu
    // Parameter {classroom} sesuai dengan yang diharapkan controller method show()
    Route::get('attendances/{classroom}', [StudentAttendanceController::class, 'show'])->name('attendances.show');

    // Presensi: Input Absen Per Mapel
    Route::get('attendances/{classroom}/schedule/{schedule}', [StudentAttendanceController::class, 'create'])->name('attendances.create');

    // Presensi: Simpan Absen
    Route::post('attendances/{classroom}/schedule/{schedule}', [StudentAttendanceController::class, 'store'])->name('attendances.store');

    Route::get('api/subjects/{classroom}', [StudentAttendanceController::class, 'getSubjectsByClassroom'])
    ->name('api.subjects.by.classroom');

    // Download Excel
    Route::get('attendances/recap/download', [StudentAttendanceController::class, 'downloadRecap'])
        ->name('attendances.recap.download');

    // MONITORING WALI KELAS
    Route::get('/homeroom', [HomeroomController::class, 'index'])->name('homeroom.index');

    Route::resource('student-violations', StudentViolationController::class);

});

require __DIR__.'/auth.php';