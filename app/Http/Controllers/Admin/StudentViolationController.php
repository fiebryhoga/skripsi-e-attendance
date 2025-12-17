<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Violation;
use App\Models\Student;
use App\Models\ViolationCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Enums\UserRole;

class StudentViolationController extends Controller
{
    // Pastikan hanya Admin & Tatib yang bisa akses
    public function __construct()
    {
        // Alternatif middleware check bisa disini atau di Route
    }

    public function index(Request $request)
    {
        // Fitur Pencarian & Filter
        $query = Violation::with(['student.classroom', 'category', 'reporter'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');

        // Cari berdasarkan Nama Siswa
        if ($request->has('search')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $violations = $query->paginate(10); // Pagination

        return view('admin.violations.index', compact('violations'));
    }

    public function create()
    {
        // Ambil data siswa dikelompokkan per kelas untuk dropdown
        $classrooms = \App\Models\Classroom::with(['students' => function($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $categories = ViolationCategory::orderBy('points')->get();

        return view('admin.violations.create', compact('classrooms', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'violation_category_id' => 'required|exists:violation_categories,id',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'bukti_foto' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); // Pelapor adalah user yang login

        // Handle Upload Foto
        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('evidence', 'public');
        }

        Violation::create($data);

        return redirect()->route('admin.student-violations.index')
            ->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function destroy(Violation $studentViolation)
    {
        // Hapus file foto jika ada
        if ($studentViolation->bukti_foto) {
            Storage::disk('public')->delete($studentViolation->bukti_foto);
        }

        $studentViolation->delete();

        return redirect()->back()->with('success', 'Data pelanggaran dihapus.');
    }
}