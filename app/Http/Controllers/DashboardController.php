<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Violation;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\StudentAttendance;
use App\Enums\UserRole;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. DASHBOARD ADMIN
        if ($user->hasRole(UserRole::ADMIN)) {
            return $this->adminDashboard();
        }

        // 2. DASHBOARD TATA TERTIB
        // PERBAIKAN: Ganti TATA_TERTIB menjadi GURU_TATIB
        if ($user->hasRole(UserRole::GURU_TATIB)) {
            return $this->tatibDashboard();
        }

        // 3. DASHBOARD WALI KELAS
        if ($user->hasRole(UserRole::WALI_KELAS)) {
            return $this->waliKelasDashboard($user);
        }

        // 4. DASHBOARD GURU BIASA
        return $this->guruDashboard($user);
    }

    // --- LOGIC PER ROLE ---

    private function adminDashboard()
    {
        $totalStudents = Student::count();
        $totalViolations = Violation::count();
        $totalClasses = Classroom::count();
        $todayViolations = Violation::whereDate('tanggal', Carbon::today())->count();

        $recentViolations = Violation::with(['student.classroom', 'category'])
            ->latest('created_at')->take(5)->get();

        // Pastikan model Student punya relasi 'violations'
        $topViolators = Student::with('classroom')
            ->withCount('violations')
            ->having('violations_count', '>', 0)
            ->orderByDesc('violations_count')
            ->take(5)->get();

        return view('dashboard.admin', compact('totalStudents', 'totalViolations', 'todayViolations', 'totalClasses', 'recentViolations', 'topViolators'));
    }

    private function tatibDashboard()
    {
        $todayViolationsCount = Violation::whereDate('tanggal', Carbon::today())->count();
        $monthViolationsCount = Violation::whereMonth('tanggal', Carbon::now()->month)->count();
        
        $todayViolationsList = Violation::with(['student.classroom', 'category', 'reporter'])
            ->whereDate('tanggal', Carbon::today())
            ->latest('created_at')->get();

        // Error pertama tadi solved karena kita sudah tambah relasi violations() di model ViolationCategory
        $topCategories = \App\Models\ViolationCategory::withCount('violations')
            ->orderByDesc('violations_count')
            ->take(5)->get();

        return view('dashboard.tatib', compact('todayViolationsCount', 'monthViolationsCount', 'todayViolationsList', 'topCategories'));
    }

    private function waliKelasDashboard($user)
    {
        // Ambil semua kelas binaan, pilih yang pertama
        $classroom = $user->supervisedClassrooms->first();

        if (!$classroom) {
            // Pastikan view dashboard.empty sudah dibuat, atau return view polos
            return view('dashboard.guru', ['todaySchedules' => collect([])])->with('warning', 'Anda belum diassign ke kelas manapun.');
        }

        $totalStudents = $classroom->students()->count();
        
        $todayAbsence = StudentAttendance::where('classroom_id', $classroom->id)
            ->whereDate('date', Carbon::today())
            ->where('status', '!=', 'Hadir')
            ->count();

        $monthViolations = Violation::whereHas('student', function($q) use ($classroom) {
            $q->where('classroom_id', $classroom->id);
        })->whereMonth('tanggal', Carbon::now()->month)->count();

        $problematicStudents = $classroom->students()
            ->withCount(['violations', 'attendances as alpha_count' => function($q){
                $q->where('status', 'Alpha');
            }])
            ->orderByDesc('violations_count')
            ->orderByDesc('alpha_count')
            ->take(5)->get();

        return view('dashboard.walikelas', compact('classroom', 'totalStudents', 'todayAbsence', 'monthViolations', 'problematicStudents'));
    }

    private function guruDashboard($user)
    {
        // 1. Tentukan Nama Hari (Sesuaikan dengan isi database Anda)
        // Jika Database pakai Bahasa Inggris (Monday, Tuesday): gunakan format('l')
        // Jika Database pakai Bahasa Indonesia (Senin, Selasa): gunakan translatedFormat('l') dan pastikan Locale Laravel 'id'
        
        $hariIni = Carbon::now()->format('l'); // Output: Wednesday
        
        // PENTING: Cek nama kolom di tabel schedules database Anda. 
        // Biasanya 'day' atau 'hari'. Di sini saya ubah jadi 'day'.
        
        $todaySchedules = Schedule::with(['classroom', 'subject'])
            ->where('user_id', $user->id)
            ->where('day', $hariIni) // <--- Ganti 'hari' menjadi 'day'
            ->orderBy('jam_mulai')
            ->get();

        return view('dashboard.guru', compact('todaySchedules'));
    }
}