<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Violation;
use App\Enums\UserRole;
use Carbon\Carbon;

class HomeroomController extends Controller
{
    // Tambahkan Request $request
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole(UserRole::WALI_KELAS)) {
            abort(403, 'Anda bukan Wali Kelas.');
        }

        // 1. AMBIL SEMUA KELAS BINAAN
        $allClassrooms = $user->supervisedClassrooms;

        if ($allClassrooms->isEmpty()) {
            return view('admin.homeroom.no-class');
        }

        // 2. TENTUKAN KELAS MANA YANG AKTIF DITAMPILKAN
        // Jika user memilih via tab (ada di URL), pakai ID itu.
        // Jika tidak, pakai ID kelas pertama.
        $activeClassroomId = $request->get('classroom_id', $allClassrooms->first()->id);
        
        // Cari object kelasnya dari collection (agar aman)
        $classroom = $allClassrooms->firstWhere('id', $activeClassroomId);

        // Validasi keamanan: Pastikan ID yang diminta benar-benar milik guru ini
        if (!$classroom) {
            abort(403, 'Anda bukan Wali Kelas dari kelas ini.');
        }

        // --- LOGIKA KE BAWAH SAMA PERSIS, TINGGAL PAKAI $classroom YANG SUDAH DIPILIH ---

        $studentIds = Student::where('classroom_id', $classroom->id)->pluck('id');
        $today = Carbon::today();

        // Data Presensi
        $todayAbsences = StudentAttendance::whereIn('student_id', $studentIds)
                            ->whereDate('date', $today)
                            ->where('status', '!=', 'Hadir')
                            ->with(['student', 'schedule.subject', 'schedule.teacher'])
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Data Pelanggaran
        $latestViolations = Violation::whereIn('student_id', $studentIds)
                            ->with(['student', 'category']) 
                            ->orderBy('tanggal', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // Rekap Siswa
        $students = Student::where('classroom_id', $classroom->id)
                    ->orderBy('name')
                    ->withCount([
                        'attendances as sakit_count' => function ($query) {
                            $query->where('status', 'Sakit')->whereMonth('date', Carbon::now()->month);
                        },
                        'attendances as izin_count' => function ($query) {
                            $query->where('status', 'Izin')->whereMonth('date', Carbon::now()->month);
                        },
                        'attendances as alpha_count' => function ($query) {
                            $query->where('status', 'Alpha')->whereMonth('date', Carbon::now()->month);
                        }
                    ])
                    ->with('violations.category') 
                    ->get();

        // Kirim $allClassrooms juga ke view untuk membuat Tab Navigasi
        return view('admin.homeroom.index', compact(
            'allClassrooms', 
            'classroom', 
            'todayAbsences', 
            'latestViolations', 
            'students'
        ));
    }
}