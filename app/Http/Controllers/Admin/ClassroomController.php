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

        // PERUBAHAN DI SINI:
        // Ambil semua siswa KECUALI yang sudah ada di kelas yang sedang dibuka.
        // Jadi siswa dari kelas lain (X-B, XI-A, dll) akan muncul di list.
        $availableStudents = Student::where(function($query) use ($classroom) {
                                    $query->where('classroom_id', '!=', $classroom->id)
                                          ->orWhereNull('classroom_id');
                                })
                                ->with('classroom') // Load relasi classroom untuk label di dropdown
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
            
            // PERBAIKAN LOGIKA UPDATE ROLE:
            // Jika guru tersebut belum punya role Wali Kelas, tambahkan role tersebut.
            // Kita tidak me-replace role lama, tapi menambahkan (karena array).
            
            if (!$teacher->hasRole(UserRole::WALI_KELAS)) {
                // Ambil roles yang ada sekarang
                $currentRoles = $teacher->roles ?? [];
                
                // Jika tipe datanya Collection (karena Cast), ubah ke array dulu
                if ($currentRoles instanceof \Illuminate\Support\Collection) {
                    $currentRoles = $currentRoles->toArray();
                }

                // Tambahkan role baru ke array
                $currentRoles[] = UserRole::WALI_KELAS;

                // Simpan kembali
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
        
        // Simpan nama kelas lama untuk keperluan notifikasi
        $oldClass = $student->classroom; 

        // Update ke kelas baru
        $student->update(['classroom_id' => $classroom->id]);

        // Cek Logika Notifikasi: Pindahan atau Masuk Baru?
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