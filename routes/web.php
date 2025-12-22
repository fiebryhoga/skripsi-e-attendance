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
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| ROUTE GLOBAL (Semua User Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Notifikasi & Profil boleh diakses semua
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    
    // PERBAIKAN: Route Settings & Reset SAYA PINDAHKAN KE GRUP ADMIN DI BAWAH
    // Agar Guru biasa tidak bisa mereset database.
});

/*
|--------------------------------------------------------------------------
| GROUP UTAMA (Prefix: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // ====================================================
    // 1. KHUSUS ADMIN (FULL CONTROL)
    // ====================================================
    Route::middleware(['role:admin'])->group(function () {
        
        // Manajemen Master Data
        Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
        Route::get('students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');
        Route::resource('students', StudentController::class);

        Route::get('teachers/template', [TeacherController::class, 'downloadTemplate'])->name('teachers.template');
        Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
        Route::resource('teachers', TeacherController::class);

        // Kelas & Mapel
        Route::resource('classrooms', ClassroomController::class)->only(['index', 'show', 'update']);
        Route::post('classrooms/{classroom}/assign-student', [ClassroomController::class, 'assignStudent'])->name('classrooms.assign-student');
        Route::put('students/{student}/transfer', [ClassroomController::class, 'transferStudent'])->name('students.transfer');
        Route::delete('students/{student}/release', [ClassroomController::class, 'releaseStudent'])->name('students.release');
        
        Route::resource('subjects', SubjectController::class)->except(['create', 'show', 'edit']);

        // Jadwal
        Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('schedules/{classroom}', [ScheduleController::class, 'show'])->name('schedules.show');
        Route::post('schedules/{classroom}', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::delete('schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

        // SETTINGS & RESET DATABASE (PENTING: Hanya Admin)
        // Saya pindahkan dari global ke sini
        Route::get('/settings', [ResetDataController::class, 'index'])->name('settings.index');
        Route::delete('/settings/reset-database', [ResetDataController::class, 'destroy'])->name('settings.reset');
    });


    // ====================================================
    // 2. ADMIN & GURU TATIB (KEDISIPLINAN)
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
        
        // Input Pelanggaran Siswa
        Route::resource('student-violations', StudentViolationController::class);

        
    });


    // ====================================================
    // 3. ADMIN, GURU MAPEL, WALI KELAS (AKADEMIK)
    // ====================================================
    // Semua guru perlu akses presensi (minimal lihat rekap)
    Route::middleware(['role:admin,guru_mapel,wali_kelas,guru_tatib'])->group(function () {
        
        // Rekap Presensi
        Route::get('attendances/recap', [StudentAttendanceController::class, 'recap'])->name('attendances.recap');
        Route::get('attendances/recap/download', [StudentAttendanceController::class, 'downloadRecap'])->name('attendances.recap.download');
        Route::get('api/subjects/{classroom}', [StudentAttendanceController::class, 'getSubjectsByClassroom'])->name('api.subjects.by.classroom');

        // Flow Input Presensi
        Route::get('attendances', [StudentAttendanceController::class, 'index'])->name('attendances.index');
        Route::get('attendances/{classroom}', [StudentAttendanceController::class, 'show'])->name('attendances.show');
        
        // Khusus Input (Create/Store) biasanya hanya Admin & Guru Mapel yang punya jadwal
        // Tapi kita filter di Controller saja (AuthorizeAccess), di sini kita buka akses rutenya
        Route::get('attendances/{classroom}/schedule/{schedule}', [StudentAttendanceController::class, 'create'])->name('attendances.create');
        Route::post('attendances/{classroom}/schedule/{schedule}', [StudentAttendanceController::class, 'store'])->name('attendances.store');
    });


    // ====================================================
    // 4. KHUSUS WALI KELAS
    // ====================================================
    Route::middleware(['role:wali_kelas'])->group(function () {
        Route::get('/homeroom', [HomeroomController::class, 'index'])->name('homeroom.index');
    });

});

require __DIR__.'/auth.php';