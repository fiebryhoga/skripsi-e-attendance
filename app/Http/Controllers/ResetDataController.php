<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Schedule;


use App\Models\StudentAttendance;
use App\Models\Violation;         
use App\Models\ViolationCategory; 

class ResetDataController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function destroy(Request $request)
    {
        if (!Hash::check($request->password_confirmation, auth()->user()->password)) {
            return redirect()->route('admin.settings.index')->with('error', 'Password salah! Proses dibatalkan.');
        }

        Schema::disableForeignKeyConstraints();

        try {
            $type = $request->type;

            switch ($type) {
                case 'all':
                    $this->resetAllData();
                    $message = 'Sistem berhasil di-reset. Data Siswa & Guru dihapus, namun Data Kelas & Kategori tetap aman.';
                    break;

                case 'semester':
                    $this->resetSemester();
                    $message = 'Data Semester (Jadwal & Presensi) berhasil di-reset.';
                    break;

                case 'academic_year':
                    $this->resetAcademicYear();
                    $message = 'Tahun Ajar berhasil di-reset. Siswa telah dinaikkan kelas.';
                    break;

                default:
                    return redirect()->route('admin.settings.index')->with('error', 'Tipe reset tidak dikenali.');
            }

            Schema::enableForeignKeyConstraints();
            return redirect()->route('admin.settings.index')->with('success', $message);

        } catch (\Exception $e) {
            Schema::enableForeignKeyConstraints();
            return redirect()->route('admin.settings.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    
    private function resetAllData()
    {
        
        StudentAttendance::truncate(); 
        Violation::truncate();         
        Schedule::truncate();          
        DB::table('notifications')->truncate();
        
        
        Student::truncate();

        
        
        

        
        
        Classroom::query()->update(['teacher_id' => null]);

        
        User::where('id', '!=', auth()->id())->delete(); 

        
        $folderFoto = 'public/students'; 
        if (Storage::exists($folderFoto)) {
            Storage::deleteDirectory($folderFoto);
            Storage::makeDirectory($folderFoto);
        }
        
        $folderBukti = 'public/violations';
        if (Storage::exists($folderBukti)) {
            Storage::deleteDirectory($folderBukti);
            Storage::makeDirectory($folderBukti);
        }
    }

    
    private function resetSemester()
    {
        Schedule::truncate();
        StudentAttendance::truncate();
    }

    
    private function resetAcademicYear()
    {
        Schedule::truncate();
        StudentAttendance::truncate();

        $students = Student::with('classroom')->get();
        $classrooms = Classroom::all();

        foreach ($students as $student) {
            if (!$student->classroom) continue;

            $currentName = strtoupper($student->classroom->name);
            
            if (str_contains($currentName, 'XII') || str_contains($currentName, '12')) {
                if ($student->photo) Storage::delete($student->photo);
                $student->delete();
            } elseif (str_contains($currentName, 'XI') || str_contains($currentName, '11')) {
                $newName = str_replace(['XI', '11'], ['XII', '12'], $currentName);
                $this->moveStudentToClass($student, $newName, $classrooms);
            } elseif (str_contains($currentName, 'X') || str_contains($currentName, '10')) {
                $newName = null;
                if (str_starts_with($currentName, 'X')) {
                    $suffix = substr($currentName, 1);
                    $newName = 'XI' . $suffix;
                } elseif (str_starts_with($currentName, '10')) {
                    $suffix = substr($currentName, 2); 
                    $newName = '11' . $suffix;
                }
                if($newName) {
                    $this->moveStudentToClass($student, $newName, $classrooms);
                }
            }
        }
    }

    private function moveStudentToClass($student, $newClassName, $allClassrooms)
    {
        $targetClass = $allClassrooms->first(function($class) use ($newClassName) {
            return strtoupper($class->name) === strtoupper($newClassName);
        });

        if ($targetClass) {
            $student->update(['classroom_id' => $targetClass->id]);
        } else {
            $student->update(['classroom_id' => null]);
        }
    }
}