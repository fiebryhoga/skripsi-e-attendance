<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Violation;
use App\Models\Classroom;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Statistik Utama
        $totalStudents = Student::count();
        $totalViolations = Violation::count();
        $todayViolations = Violation::whereDate('tanggal', Carbon::today())->count();
        $totalClasses = Classroom::count();

        // 2. Data Pelanggaran Terbaru (5 Terakhir)
        $recentViolations = Violation::with(['student', 'category'])
                            ->latest()
                            ->take(5)
                            ->get();

        // 3. Top 5 Siswa Sering Melanggar (Paling banyak poin minus)
        // Kita asumsikan hitung manual dari relasi violation -> category -> poin
        // Atau hitung jumlah kasusnya saja
        $topViolators = Student::withCount('violations')
                        ->orderBy('violations_count', 'desc')
                        ->take(5)
                        ->get();

        return view('dashboard', compact(
            'totalStudents', 
            'totalViolations', 
            'todayViolations', 
            'totalClasses',
            'recentViolations',
            'topViolators'
        ));
    }
}