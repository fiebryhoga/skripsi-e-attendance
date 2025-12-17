<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceRecapExport;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Enums\UserRole;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentAttendanceController extends Controller
{
    // 1. Pilih Kelas (Sama seperti sebelumnya)
    public function index()
    {
        $user = Auth::user();
        $query = Classroom::withCount('students')->orderBy('name');

        // JIKA BUKAN ADMIN (GURU / WALI KELAS)
        if (! $user->hasRole(UserRole::ADMIN)) {
            
            // 1. Filter: Hanya ambil kelas yang diajar oleh guru ini
            $query->whereHas('schedules', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });

            // 2. Load Data: Ambil detail jadwal guru tersebut di kelas ini
            // (Supaya bisa ditampilkan di kartu: Mapel - Hari - Jam)
            $query->with(['schedules' => function($q) use ($user) {
                $q->where('user_id', $user->id)
                ->with('subject') // Load nama mapel
                ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                ->orderBy('jam_mulai');
            }]);
        }

        $classrooms = $query->get();

        return view('admin.attendances.index', compact('classrooms'));
    }

    // 2. Lihat Daftar Mapel di Hari Tertentu (Halaman Baru)
    public function show(Request $request, Classroom $classroom)
    {
        Carbon::setLocale('id');
        $date = $request->input('date', date('Y-m-d'));
        $dayName = Carbon::parse($date)->translatedFormat('l'); 
        $user = Auth::user();

        // Validasi Keamanan:
        // Jika Guru iseng ganti ID Kelas di URL ke kelas yang tidak diajar, tolak aksesnya.
        if (! $user->hasRole(UserRole::ADMIN)) {
            $isTeachingThisClass = Schedule::where('classroom_id', $classroom->id)
                                    ->where('user_id', $user->id)
                                    ->exists();
            if (!$isTeachingThisClass) {
                abort(403, 'Anda tidak memiliki jadwal mengajar di kelas ini.');
            }
        }

        // Query Jadwal
        $query = Schedule::with(['subject', 'teacher'])
                    ->where('classroom_id', $classroom->id)
                    ->where('day', $dayName)
                    ->orderBy('jam_mulai');

        // LOGIKA PEMBATASAN JADWAL
        // Jika BUKAN Admin, hanya tampilkan jadwal milik guru tersebut
        if (! $user->hasRole(UserRole::ADMIN)) {
            $query->where('user_id', $user->id);
        }

        $schedules = $query->get();

        // Hitung statistik kehadiran
        $schedules->map(function($schedule) use ($date) {
            $schedule->attendance_count = StudentAttendance::where('schedule_id', $schedule->id)
                                            ->where('date', $date)
                                            ->count();
            return $schedule;
        });

        return view('admin.attendances.schedule-list', compact('classroom', 'schedules', 'date', 'dayName'));
    }

    // 3. Form Input Absen untuk 1 Mapel Spesifik
    public function create(Request $request, Classroom $classroom, Schedule $schedule)
    {

        $user = Auth::user();

        if (! $user->hasRole(UserRole::ADMIN) && $schedule->user_id != $user->id) {
            abort(403, 'Anda tidak berhak mengisi presensi mata pelajaran guru lain.');
        }

        $date = $request->input('date', date('Y-m-d'));

        // Validasi: Pastikan jadwal ini memang milik kelas ini (Security check)
        if($schedule->classroom_id != $classroom->id) {
            abort(404);
        }

        // Ambil Siswa + Data Absen di Mapel ini & Tanggal ini
        $students = Student::where('classroom_id', $classroom->id)
            ->orderBy('name')
            ->get()
            ->map(function ($student) use ($date, $schedule) {
                $attendance = StudentAttendance::where('student_id', $student->id)
                                ->where('date', $date)
                                ->where('schedule_id', $schedule->id) // Filter per Mapel
                                ->first();
                
                $student->attendance_today = $attendance;
                return $student;
            });

        // Summary Statistik
        $summary = [
            'hadir' => $students->where('attendance_today.status', 'Hadir')->count(),
            'sakit' => $students->where('attendance_today.status', 'Sakit')->count(),
            'izin'  => $students->where('attendance_today.status', 'Izin')->count(),
            'alpha' => $students->where('attendance_today.status', 'Alpha')->count(),
        ];

        return view('admin.attendances.form', compact('classroom', 'schedule', 'students', 'date', 'summary'));
    }

    // 4. Simpan Data
    public function store(Request $request, Classroom $classroom, Schedule $schedule)
    {

        $user = Auth::user();
        
        // Security Check
        if (! $user->hasRole(UserRole::ADMIN) && $schedule->user_id != $user->id) {
            abort(403);
        }


        $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
        ]);

        $date = $request->date;
        $attendances = $request->attendances;

        foreach ($attendances as $studentId => $data) {
            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $date,
                    'schedule_id' => $schedule->id, // Kunci Utama Tambahan
                ],
                [
                    'classroom_id' => $classroom->id,
                    'status' => $data['status'],
                    'note' => $data['note'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.attendances.create', [
            'classroom' => $classroom->id, 
            'schedule' => $schedule->id, 
            'date' => $date
        ])->with('success', 'Presensi mapel ' . $schedule->subject->name . ' berhasil disimpan.');
    }

    public function recap(Request $request)
    {
        $user = Auth::user();

        // ---------------------------------------------------------
        // 1. POPULASI DROPDOWN KELAS
        // ---------------------------------------------------------
        // Admin: Semua Kelas
        // Guru/Wali: Hanya kelas yang ada di jadwalnya
        $classroomsQuery = Classroom::orderBy('name');
        
        if (! $user->hasRole(UserRole::ADMIN)) {
            $classroomsQuery->whereHas('schedules', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        $classrooms = $classroomsQuery->get();


        // ---------------------------------------------------------
        // 2. POPULASI DROPDOWN MAPEL (DEPENDENT LOGIC)
        // ---------------------------------------------------------
        // Default kosong agar user memilih kelas dulu.
        // TAPI, jika user sudah submit (ada request classroom_id), 
        // kita harus isi ulang agar pilihannya tidak hilang setelah reload.
        
        $subjects = []; 

        if ($request->filled('classroom_id')) {
            $subjectsQuery = \App\Models\Subject::orderBy('name');

            // Filter Mapel berdasarkan Jadwal di Kelas TERSEBUT
            $subjectsQuery->whereHas('schedules', function($q) use ($user, $request) {
                // Syarat 1: Jadwalnya harus di kelas yang dipilih
                $q->where('classroom_id', $request->classroom_id);

                // Syarat 2: Jika bukan Admin, jadwalnya harus milik guru ini
                if (! $user->hasRole(UserRole::ADMIN)) {
                    $q->where('user_id', $user->id);
                }
            });

            $subjects = $subjectsQuery->get();
        }


        // ---------------------------------------------------------
        // 3. LOGIKA PENGAMBILAN DATA PRESENSI (JIKA DISUBMIT)
        // ---------------------------------------------------------
        $attendances = null;
        $dates = [];
        $students = [];
        $selectedClassroom = null;
        $selectedSubject = null;
        
        if ($request->filled(['classroom_id', 'subject_id', 'start_date', 'end_date'])) {
            
            // A. VALIDASI KEAMANAN
            // Pastikan guru tidak mengutak-atik ID di URL untuk melihat kelas orang lain
            $this->authorizeAccess($request->classroom_id, $request->subject_id);

            // B. AMBIL DATA OBJECT UTAMA
            $selectedClassroom = Classroom::find($request->classroom_id);
            $selectedSubject = \App\Models\Subject::find($request->subject_id);

            // C. AMBIL DATA REPORT (Menggunakan Helper Private biar rapi)
            $data = $this->getRecapData($request);
            $dates = $data['dates'];
            $students = $data['students'];
        }

        return view('admin.attendances.recap', compact(
            'classrooms', 
            'subjects', 
            'dates', 
            'students', 
            'selectedClassroom', 
            'selectedSubject'
        ));
    }



    // --- METHOD BARU: API UNTUK AJAX ---
    public function getSubjectsByClassroom($classroomId)
    {
        $user = Auth::user();
        
        $query = \App\Models\Subject::query();

        // Cari Mapel yang berelasi dengan Jadwal di Kelas $classroomId
        $query->whereHas('schedules', function($q) use ($user, $classroomId) {
            $q->where('classroom_id', $classroomId);
            
            // Jika Guru, filter juga berdasarkan ID user dia
            if (! $user->hasRole(UserRole::ADMIN)) {
                $q->where('user_id', $user->id);
            }
        });

        $subjects = $query->orderBy('name')->get(['id', 'name']); // Ambil ID dan Nama saja

        return response()->json($subjects);
    }



    

    // 6. ACTION DOWNLOAD EXCEL
    public function downloadRecap(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required',
            'subject_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        // --- VALIDASI KEAMANAN (PENTING) ---
        // Ini mencegah Guru mengganti ID di URL untuk download punya orang lain
        $this->authorizeAccess($request->classroom_id, $request->subject_id);
        // -----------------------------------

        $classroom = Classroom::findOrFail($request->classroom_id);
        $subject = \App\Models\Subject::findOrFail($request->subject_id);

        // Ambil Data
        $dataRef = $this->getRecapData($request);

        $data = [
            'classroom' => $classroom,
            'subject' => $subject,
            'dates' => $dataRef['dates'],
            'students' => $dataRef['students'],
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
        ];

        // Nama File: REKAP_XA_MTK_20251231.xlsx
        $fileName = 'REKAP_' . str_replace(' ', '', $classroom->name) . '_' . 
                     str_replace(' ', '', $subject->name) . '_' . date('YmdHis') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AttendanceRecapExport($data), $fileName);
    }

    // --- PRIVATE HELPER (Agar kodingan rapi) ---

    /**
     * Fungsi Validasi Hak Akses
     * Admin: Boleh semua.
     * Guru/Wali/Tatib: Hanya boleh jika punya jadwal mengajar Mapel X di Kelas Y.
     */
    private function authorizeAccess($classroomId, $subjectId)
    {
        $user = Auth::user();

        // Admin Bebas Akses
        if ($user->hasRole(UserRole::ADMIN)) {
            return true;
        }

        // Cek apakah Guru punya jadwal Mapel X di Kelas Y
        $hasSchedule = Schedule::where('classroom_id', $classroomId)
            ->where('subject_id', $subjectId)
            ->where('user_id', $user->id)
            ->exists();

        if (! $hasSchedule) {
            abort(403, 'ANDA TIDAK MEMILIKI HAK AKSES UNTUK MELIHAT REKAP KELAS INI.');
        }
    }

    /**
     * Logic Query Data (Dipakai di view & download)
     */
    private function getRecapData($request)
    {
        $classroomId = $request->classroom_id;
        $subjectId = $request->subject_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // 1. Ambil List Tanggal Pertemuan (Header Kolom)
        // Cari tanggal berapa saja ada input presensi untuk mapel & kelas ini
        $dates = StudentAttendance::where('classroom_id', $classroomId)
            ->whereHas('schedule', function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            })
            ->whereBetween('date', [$startDate, $endDate])
            ->select('date')
            ->distinct()
            ->orderBy('date')
            ->pluck('date')
            ->toArray();

        // 2. Ambil List Siswa beserta Status Presensinya (Baris Data)
        $students = Student::where('classroom_id', $classroomId)
            ->orderBy('name')
            // Eager Load absensi HANYA untuk mapel dan tanggal yang dipilih
            // (Penting: agar absensi mapel Biologi tidak muncul di rekap Matematika)
            ->with(['attendances' => function($q) use ($subjectId, $startDate, $endDate) {
                $q->whereHas('schedule', function($subQ) use ($subjectId) {
                    $subQ->where('subject_id', $subjectId);
                })
                ->whereBetween('date', [$startDate, $endDate]);
            }])
            ->get();

        return [
            'dates' => $dates, 
            'students' => $students
        ];
    }
}