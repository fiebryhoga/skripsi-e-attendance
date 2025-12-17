<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Student;
use App\Imports\StudentsImport;
use App\Notifications\DataChangedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentTemplateExport; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        
        $classrooms = Classroom::orderBy('name')->get(); 

        
        $query = Student::with('classroom'); 

        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        
        $students = $query->latest()->paginate(10)->withQueryString();

        
        if ($request->ajax()) {
            return view('admin.students._table_rows', compact('students'))->render();
        }

        
        return view('admin.students.index', compact('students', 'classrooms'));
    }

    public function show(Student $student)
    {
        $student->load(['classroom', 'violations' => function($query) {
            $query->latest('tanggal')->with('category', 'reporter');
        }]);

        return view('admin.students.show', compact('student'));
    }

    public function create()
    {
        
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'nis' => 'required|unique:students,nis',
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:10240', 
            'angkatan' => 'required|numeric|digits:4',
        ]);

        $data = $request->all();

        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        Student::create($data);

        Auth::user()->notify(new DataChangedNotification('Berhasil menambahkan siswa baru: ' . $data['name']));
        return redirect()->route('admin.students.index');
    }

    public function edit(Student $student)
    {
        
        $classrooms = Classroom::orderBy('name')->get();

        return view('admin.students.edit', compact('student', 'classrooms'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nis' => 'required|unique:students,nis,' . $student->id,
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:10240',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'angkatan' => 'required|numeric|digits:4',
        ]);

        // Ambil semua data input ke variabel $data
        $data = $request->all();

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            // Simpan foto baru ke variabel $data['photo']
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        // PERBAIKAN DI SINI: Gunakan $data, BUKAN $request->all()
        $student->update($data); 

        Auth::user()->notify(new DataChangedNotification('Data siswa ' . $student->name . ' berhasil diperbarui.'));
        return redirect()->route('admin.students.index');
    }

    public function destroy(Student $student)
    {
        $name = $student->name;
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }
        $student->delete();

        Auth::user()->notify(new DataChangedNotification('Siswa ' . $name . ' telah dihapus.', 'danger'));

        return redirect()->route('admin.students.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            
            'file' => 'required|mimes:xlsx,xls,csv|max:51200',
        ]);

        try {
            Excel::import(new StudentsImport, $request->file('file'));
            
            
            Auth::user()->notify(new DataChangedNotification('Berhasil import data siswa.'));
            
            return back()->with('success', 'Data siswa berhasil diimport!');
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return back()->with('error', 'Gagal Import: ' . implode('<br>', $errorMessages));

        } catch (\Exception $e) {
            
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_siswa.csv"',
        ];

        $columns = ['nis', 'nama_lengkap', 'nama_kelas', 'jenis_kelamin', 'agama', 'nisn', 'angkatan', 'nomor_ortu'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, ['2024001', 'Budi Santoso', 'XII-A', 'L', 'Islam', '0012345678', '2024', '08123456789']);
            fclose($file);
        };

        return Excel::download(new StudentTemplateExport, 'template_siswa_simadis.xlsx');
    }
}