<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceRecapExport;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Services\WhatsAppService;
use App\Enums\UserRole;
use App\Models\Schedule;
use App\Models\User;
use App\Notifications\DataChangedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAttendanceController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        $query = Classroom::withCount('students')->orderBy('name');

        
        if (! $user->hasRole(UserRole::ADMIN)) {
            
            
            $query->whereHas('schedules', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });

            
            
            $query->with(['schedules' => function($q) use ($user) {
                $q->where('user_id', $user->id)
                ->with('subject') 
                ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                ->orderBy('jam_mulai');
            }]);
        }

        $classrooms = $query->get();

        return view('admin.attendances.index', compact('classrooms'));
    }

    
    public function show(Request $request, Classroom $classroom)
    {
        Carbon::setLocale('id');
        $date = $request->input('date', date('Y-m-d'));
        $dayName = Carbon::parse($date)->translatedFormat('l'); 
        $user = Auth::user();

        
        
        if (! $user->hasRole(UserRole::ADMIN)) {
            $isTeachingThisClass = Schedule::where('classroom_id', $classroom->id)
                                    ->where('user_id', $user->id)
                                    ->exists();
            if (!$isTeachingThisClass) {
                abort(403, 'Anda tidak memiliki jadwal mengajar di kelas ini.');
            }
        }

        
        $query = Schedule::with(['subject', 'teacher'])
                    ->where('classroom_id', $classroom->id)
                    ->where('day', $dayName)
                    ->orderBy('jam_mulai');

        
        
        if (! $user->hasRole(UserRole::ADMIN)) {
            $query->where('user_id', $user->id);
        }

        $schedules = $query->get();

        
        $schedules->map(function($schedule) use ($date) {
            $schedule->attendance_count = StudentAttendance::where('schedule_id', $schedule->id)
                                            ->where('date', $date)
                                            ->count();
            return $schedule;
        });

        return view('admin.attendances.schedule-list', compact('classroom', 'schedules', 'date', 'dayName'));
    }

    
    public function create(Request $request, Classroom $classroom, Schedule $schedule)
    {

        $user = Auth::user();

        if (! $user->hasRole(UserRole::ADMIN) && $schedule->user_id != $user->id) {
            abort(403, 'Anda tidak berhak mengisi presensi mata pelajaran guru lain.');
        }

        $date = $request->input('date', date('Y-m-d'));

        
        if($schedule->classroom_id != $classroom->id) {
            abort(404);
        }

        
        $students = Student::where('classroom_id', $classroom->id)
            ->orderBy('name')
            ->get()
            ->map(function ($student) use ($date, $schedule) {
                $attendance = StudentAttendance::where('student_id', $student->id)
                                ->where('date', $date)
                                ->where('schedule_id', $schedule->id) 
                                ->first();
                
                $student->attendance_today = $attendance;
                return $student;
            });

        
        $summary = [
            'hadir' => $students->where('attendance_today.status', 'Hadir')->count(),
            'sakit' => $students->where('attendance_today.status', 'Sakit')->count(),
            'izin'  => $students->where('attendance_today.status', 'Izin')->count(),
            'alpha' => $students->where('attendance_today.status', 'Alpha')->count(),
        ];

        return view('admin.attendances.form', compact('classroom', 'schedule', 'students', 'date', 'summary'));
    }

    
    public function store(Request $request, Classroom $classroom, Schedule $schedule, WhatsAppService $waService)
    {
        $user = Auth::user();
        
        if (! $user->hasRole(UserRole::ADMIN) && $schedule->user_id != $user->id) {
            abort(403);
        }

        $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
        ]);

        $date = $request->date;
        $attendances = $request->attendances;

        
        $classroom->load('teacher'); 
        $waliKelas = $classroom->teacher; 
        $nomorWaliKelas = ($waliKelas && !empty($waliKelas->phone)) ? $waliKelas->phone : null; 

        Carbon::setLocale('id'); 
        $hariTanggal = Carbon::parse($date)->translatedFormat('l, d F Y'); 
        
        $jamMulai = $schedule->jam_mulai;     
        $jamSelesai = $schedule->jam_selesai; 
        $infoJam = ($jamMulai == $jamSelesai) ? "Jam pelajaran ke $jamMulai" : "Jam pelajaran ke $jamMulai - $jamSelesai";

        
        foreach ($attendances as $studentId => $data) {
            
            
            
            $existingAttendance = StudentAttendance::where('student_id', $studentId)
                                    ->where('date', $date)
                                    ->where('schedule_id', $schedule->id)
                                    ->first();

            
            $oldStatus = $existingAttendance ? $existingAttendance->status : null;
            $newStatus = $data['status'];

            
            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $date,
                    'schedule_id' => $schedule->id,
                ],
                [
                    'classroom_id' => $classroom->id,
                    'status' => $newStatus,
                    'note' => $data['note'] ?? null,
                ]
            );

            
            
            
            
            
            
            
            if (in_array($newStatus, ['Alpha', 'Bolos']) && $newStatus !== $oldStatus) {
                
                $student = Student::find($studentId);

                
                if ($student && !empty($student->phone_parent)) { 
                    $msgOrtu = $waService->formatAttendanceMessage(
                        $student->name,
                        $newStatus,
                        $schedule->subject->name,
                        $hariTanggal,
                        $infoJam
                    );
                    $waService->send($student->phone_parent, $msgOrtu);
                }

                
                if ($nomorWaliKelas) {
                    $msgGuru = $waService->formatTeacherMessage(
                        $student->name,
                        $newStatus,
                        $classroom->name,
                        $schedule->subject->name,
                        $hariTanggal,
                        $infoJam
                    );
                    $waService->send($nomorWaliKelas, $msgGuru);
                }
            }
        }

        
        
        
        
        
        
        $admins = User::whereJsonContains('roles', UserRole::ADMIN->value)->get();
        
        
        
        $recipients = $admins->push($user)->unique('id');

        
        $notifMessage = "Presensi {$classroom->name} - {$schedule->subject->name} ($hariTanggal) berhasil disimpan oleh {$user->name}.";

        
        Notification::send($recipients, new DataChangedNotification($notifMessage));

        return redirect()->route('admin.attendances.create', [
            'classroom' => $classroom->id, 
            'schedule' => $schedule->id, 
            'date' => $date
        ])->with('success', 'Presensi disimpan.');
    }

    public function recap(Request $request)
    {
        $user = Auth::user();

        $classroomsQuery = Classroom::orderBy('name');
        
        if (! $user->hasRole(UserRole::ADMIN)) {
            $classroomsQuery->where(function($q) use ($user) {
                
                $q->whereHas('schedules', function($subQ) use ($user) {
                    $subQ->where('user_id', $user->id);
                })
                
                ->orWhere('teacher_id', $user->id);
            });
        }
        $classrooms = $classroomsQuery->get();

        $subjects = []; 

        if ($request->filled('classroom_id')) {
            
            $isWaliKelas = Classroom::where('id', $request->classroom_id)
                            ->where('teacher_id', $user->id)
                            ->exists();

            $subjectsQuery = \App\Models\Subject::orderBy('name');

            $subjectsQuery->whereHas('schedules', function($q) use ($user, $request, $isWaliKelas) {
                $q->where('classroom_id', $request->classroom_id);

                
                
                
                
                if (! $user->hasRole(UserRole::ADMIN) && !$isWaliKelas) {
                    $q->where('user_id', $user->id);
                }
            });

            $subjects = $subjectsQuery->get();
        }

        
        
        $attendances = null;
        $dates = [];
        $students = [];
        $selectedClassroom = null;
        $selectedSubject = null;
        
        if ($request->filled(['classroom_id', 'subject_id', 'start_date', 'end_date'])) {
            
            $this->authorizeAccess($request->classroom_id, $request->subject_id);

            $selectedClassroom = Classroom::find($request->classroom_id);
            $selectedSubject = \App\Models\Subject::find($request->subject_id);

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







    public function getSubjectsByClassroom($classroomId)
    {
        $user = Auth::user();
        
        
        $isWaliKelas = Classroom::where('id', $classroomId)
                        ->where('teacher_id', $user->id)
                        ->exists();

        $query = \App\Models\Subject::query();

        $query->whereHas('schedules', function($q) use ($user, $classroomId, $isWaliKelas) {
            $q->where('classroom_id', $classroomId);
            
            
            
            if (! $user->hasRole(UserRole::ADMIN) && !$isWaliKelas) {
                $q->where('user_id', $user->id);
            }
        });

        $subjects = $query->orderBy('name')->get(['id', 'name']); 

        return response()->json($subjects);
    }



    

    
    public function downloadRecap(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required',
            'subject_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        
        
        $this->authorizeAccess($request->classroom_id, $request->subject_id);
        

        $classroom = Classroom::findOrFail($request->classroom_id);
        $subject = \App\Models\Subject::findOrFail($request->subject_id);

        
        $dataRef = $this->getRecapData($request);

        $data = [
            'classroom' => $classroom,
            'subject' => $subject,
            'dates' => $dataRef['dates'],
            'students' => $dataRef['students'],
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
        ];

        
        $fileName = 'REKAP_' . str_replace(' ', '', $classroom->name) . '_' . 
                     str_replace(' ', '', $subject->name) . '_' . date('YmdHis') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AttendanceRecapExport($data), $fileName);
    }


    private function authorizeAccess($classroomId, $subjectId)
    {
        $user = Auth::user();

        
        if ($user->hasRole(UserRole::ADMIN)) {
            return true;
        }

        
        $isWaliKelas = Classroom::where('id', $classroomId)
                        ->where('teacher_id', $user->id)
                        ->exists();

        if ($isWaliKelas) {
            return true; 
        }

        
        $hasSchedule = Schedule::where('classroom_id', $classroomId)
            ->where('subject_id', $subjectId)
            ->where('user_id', $user->id)
            ->exists();

        if (! $hasSchedule) {
            abort(403, 'ANDA TIDAK MEMILIKI HAK AKSES UNTUK MELIHAT REKAP KELAS INI (Bukan Pengajar & Bukan Wali Kelas).');
        }
    }


    private function getRecapData($request)
    {
        $classroomId = $request->classroom_id;
        $subjectId = $request->subject_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        
        
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

        
        $students = Student::where('classroom_id', $classroomId)
            ->orderBy('name')
            
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