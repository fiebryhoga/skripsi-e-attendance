<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

// PASTIKAN NAMA MODEL SESUAI FILE DI FOLDER APP/MODELS
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\StudentViolation; // Pastikan nama filenya StudentViolation.php
use App\Models\Violation;

class ResetDataController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function destroy(Request $request)
    {
        // 1. Cek Password Admin
        if (!Hash::check($request->password_confirmation, auth()->user()->password)) {
            return redirect()->route('settings.index')
                ->with('error', 'Password salah! Reset dibatalkan.');
        }

        // Matikan pengecekan relasi (Foreign Key) agar bisa truncate bebas
        Schema::disableForeignKeyConstraints();

        try {
            // --- HAPUS DATA TABEL ---
            // Kita tidak pakai DB::transaction karena truncate() di MySQL 
            // menyebabkan konflik transaksi.
            
            // 1. Hapus Presensi
            if (class_exists(Attendance::class)) Attendance::truncate();

            // 2. Hapus Pelanggaran Siswa
            if (class_exists(StudentViolation::class)) StudentViolation::truncate();
            
            // 3. Hapus Master Kategori Pelanggaran (Opsional)
            if (class_exists(Violation::class)) Violation::truncate();

            // 4. Hapus Jadwal
            if (class_exists(Schedule::class)) Schedule::truncate();

            // 5. Hapus Siswa
            if (class_exists(Student::class)) Student::truncate();

            // --- HAPUS FILE FISIK ---
            $folderFoto = 'public/students'; 
            if (Storage::exists($folderFoto)) {
                Storage::deleteDirectory($folderFoto);
                Storage::makeDirectory($folderFoto);
            }

            // Nyalakan lagi pengecekan relasi (WAJIB)
            Schema::enableForeignKeyConstraints();

            return redirect()->route('settings.index')
                ->with('success', 'Sistem berhasil di-reset total.');

        } catch (\Exception $e) {
            // Jika error, pastikan pengaman dinyalakan lagi
            Schema::enableForeignKeyConstraints();
            
            return redirect()->route('settings.index')
                ->with('error', 'Gagal reset: ' . $e->getMessage());
        }
    }
}