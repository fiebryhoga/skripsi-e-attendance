<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use App\Enums\UserRole;
use App\Notifications\DataChangedNotification; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $query = Classroom::with(['teacher', 'students']); 

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $classrooms = $query->get();

        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function show(Classroom $classroom)
    {
        $classroom->load(['students', 'teacher']);



        $classroom->load(['students' => function ($query) {
            $query->orderBy('name', 'asc');
        }, 'teacher']);
        
        $teachers = User::all()->filter(function ($user) {
            return $user->hasRole(UserRole::WALI_KELAS) || 
                   $user->hasRole(UserRole::GURU_MAPEL);
        })->sortBy('name')->values();

        
        
        
        $availableStudents = Student::where(function($query) use ($classroom) {
                                    $query->where('classroom_id', '!=', $classroom->id)
                                          ->orWhereNull('classroom_id');
                                })
                                ->with('classroom') 
                                ->orderBy('name')
                                ->get();

        $otherClassrooms = Classroom::where('id', '!=', $classroom->id)->get();

        return view('admin.classrooms.show', compact('classroom', 'teachers', 'availableStudents', 'otherClassrooms'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $request->validate(['teacher_id' => 'nullable|exists:users,id']);

        $classroom->update(['teacher_id' => $request->teacher_id]);

        $teacherName = "Tidak Ada";
        
        if ($request->teacher_id) {
            $teacher = User::find($request->teacher_id);
            
            
            
            
            
            if (!$teacher->hasRole(UserRole::WALI_KELAS)) {
                
                $currentRoles = $teacher->roles ?? [];
                
                
                if ($currentRoles instanceof \Illuminate\Support\Collection) {
                    $currentRoles = $currentRoles->toArray();
                }

                
                $currentRoles[] = UserRole::WALI_KELAS;

                
                $teacher->roles = $currentRoles;
                $teacher->save();
            }
            
            $teacherName = $teacher->name;
        }

        Auth::user()->notify(new DataChangedNotification(
            "Wali kelas {$classroom->name} diperbarui menjadi: {$teacherName}"
        ));

        return back()->with('success', 'Wali kelas berhasil diperbarui.');
    }

    
    public function assignStudent(Request $request, Classroom $classroom)
    {
        $request->validate(['student_id' => 'required|exists:students,id']);
        
        $student = Student::findOrFail($request->student_id);
        
        
        $oldClass = $student->classroom; 

        
        $student->update(['classroom_id' => $classroom->id]);

        
        if ($oldClass) {
            $message = "Siswa {$student->name} berhasil dipindahkan dari {$oldClass->name} ke {$classroom->name}";
        } else {
            $message = "Siswa {$student->name} berhasil ditambahkan ke kelas {$classroom->name}";
        }

        Auth::user()->notify(new DataChangedNotification($message));

        return back()->with('success', $message);
    }
    
    public function transferStudent(Request $request, Student $student)
    {
        $request->validate(['target_classroom_id' => 'required|exists:classrooms,id']);
        
        $oldClass = $student->classroom->name ?? '-';
        $targetClass = Classroom::find($request->target_classroom_id);
        
        $student->update(['classroom_id' => $request->target_classroom_id]);

        
        Auth::user()->notify(new DataChangedNotification(
            "Siswa {$student->name} dipindahkan dari {$oldClass} ke {$targetClass->name}"
        ));

        return back()->with('success', 'Siswa berhasil dipindahkan ke kelas lain.');
    }

    
    public function releaseStudent(Student $student)
    {
        $className = $student->classroom->name ?? '-';
        $student->update(['classroom_id' => null]);

        
        
        Auth::user()->notify(new DataChangedNotification(
            "Siswa {$student->name} dikeluarkan dari kelas {$className}",
            'danger' 
        ));

        return back()->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
    }
}