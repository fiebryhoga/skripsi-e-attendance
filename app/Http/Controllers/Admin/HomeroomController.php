<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Classroom;
use App\Models\Violation;
use App\Enums\UserRole;
use Carbon\Carbon;

class HomeroomController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole(UserRole::WALI_KELAS)) {
            abort(403, 'Anda bukan Wali Kelas.');
        }

        
        $allClassrooms = $user->supervisedClassrooms;

        if ($allClassrooms->isEmpty()) {
            return view('admin.homeroom.no-class');
        }

        
        
        
        $activeClassroomId = $request->get('classroom_id', $allClassrooms->first()->id);
        
        
        $classroom = $allClassrooms->firstWhere('id', $activeClassroomId);

        
        if (!$classroom) {
            abort(403, 'Anda bukan Wali Kelas dari kelas ini.');
        }

        

        $studentIds = Student::where('classroom_id', $classroom->id)->pluck('id');
        $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        
        $todayAbsences = StudentAttendance::whereIn('student_id', $studentIds)
                            ->whereDate('date', $today)
                            ->where('status', '!=', 'Hadir')
                            ->with(['student', 'schedule.subject', 'schedule.teacher'])
                            ->orderBy('created_at', 'desc')
                            ->get();

        
        $latestViolations = Violation::whereIn('student_id', $studentIds)
                            ->with(['student', 'category']) 
                            ->orderBy('tanggal', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        
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

        
        return view('admin.homeroom.index', compact(
            'allClassrooms', 
            'classroom', 
            'todayAbsences', 
            'latestViolations', 
            'students'
        ));
    }

    

    public function show(Student $student)
    {
        $user = Auth::user();
        
        
        if (!$user->hasRole(UserRole::ADMIN)) {
            $myClassroom = Classroom::where('teacher_id', $user->id)->first();
            if (!$myClassroom || $student->classroom_id != $myClassroom->id) {
                abort(403, 'Anda tidak berhak melihat detail siswa dari kelas lain.');
            }
        }

        
        $student->load(['violations.category', 'violations.reporter', 'classroom']);

        
        
        $totalEntries = StudentAttendance::where('student_id', $student->id)->count();
        
        
        $totalHadir = StudentAttendance::where('student_id', $student->id)
                        ->where('status', 'Hadir')
                        ->count();

        
        $attendancePercentage = $totalEntries > 0 
            ? round(($totalHadir / $totalEntries) * 100) 
            : 0; 

        
        return view('admin.homeroom.show', compact('student', 'attendancePercentage', 'totalHadir', 'totalEntries'));
    }

    public function violations(Classroom $classroom)
    {
        $user = Auth::user();

        
        if (!$user->hasRole(UserRole::ADMIN) && $classroom->teacher_id != $user->id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        
        $violations = Violation::whereHas('student', function($q) use ($classroom) {
                            $q->where('classroom_id', $classroom->id);
                        })
                        ->with(['student', 'category', 'reporter'])
                        ->orderBy('tanggal', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('admin.homeroom.violations', compact('classroom', 'violations'));
    }
}