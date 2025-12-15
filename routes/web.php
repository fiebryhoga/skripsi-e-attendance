<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});





Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');
    
    Route::resource('students', StudentController::class);


    // CLASSROOM MANAGEMENT

    Route::resource('classrooms', \App\Http\Controllers\Admin\ClassroomController::class)->only(['index', 'show', 'update']);
    
    Route::post('classrooms/{classroom}/assign-student', [\App\Http\Controllers\Admin\ClassroomController::class, 'assignStudent'])->name('classrooms.assign-student');
    Route::put('students/{student}/transfer', [\App\Http\Controllers\Admin\ClassroomController::class, 'transferStudent'])->name('students.transfer');
    Route::delete('students/{student}/release', [\App\Http\Controllers\Admin\ClassroomController::class, 'releaseStudent'])->name('students.release');
});






Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
