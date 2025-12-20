<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Violation;
use App\Models\ViolationCategory;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentViolationController extends Controller
{
    /**
     * Menampilkan daftar pelanggaran dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $query = Violation::with(['student.classroom', 'category', 'reporter'])
            ->latest('tanggal')
            ->latest('created_at');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('classroom', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $violations = $query->paginate(10)->withQueryString();

        return view('admin.violations.index', compact('violations'));
    }

    /**
     * Menampilkan form input pelanggaran.
     */
    public function create()
    {
        // Dropdown Siswa (Group per Kelas)
        $classrooms = Classroom::with(['students' => function($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        // Dropdown Kategori
        $categories = ViolationCategory::orderBy('grup')
                        ->orderBy('kode')
                        ->get();

        return view('admin.violations.create', compact('classrooms', 'categories'));
    }

    /**
     * Menyimpan data pelanggaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'violation_category_id' => 'required|exists:violation_categories,id',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        $data = $request->only(['student_id', 'violation_category_id', 'tanggal', 'catatan']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('evidence', 'public');
        }

        Violation::create($data);

        return redirect()->route('admin.student-violations.index')
            ->with('success', 'Data pelanggaran berhasil dicatat.');
    }

    /**
     * Menampilkan form edit data.
     */
    public function edit($id)
    {
        $violation = Violation::findOrFail($id);

        $classrooms = Classroom::with(['students' => function($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $categories = ViolationCategory::orderBy('grup')
                        ->orderBy('kode')
                        ->get();

        return view('admin.violations.edit', compact('violation', 'classrooms', 'categories'));
    }

    /**
     * Memperbarui data pelanggaran.
     */
    public function update(Request $request, $id)
    {
        $violation = Violation::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'violation_category_id' => 'required|exists:violation_categories,id',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $data = $request->only(['student_id', 'violation_category_id', 'tanggal', 'catatan']);

        // Handle Foto Baru
        if ($request->hasFile('bukti_foto')) {
            // Hapus foto lama jika ada
            if ($violation->bukti_foto && Storage::disk('public')->exists($violation->bukti_foto)) {
                Storage::disk('public')->delete($violation->bukti_foto);
            }
            // Simpan foto baru
            $data['bukti_foto'] = $request->file('bukti_foto')->store('evidence', 'public');
        }

        $violation->update($data);

        return redirect()->route('admin.student-violations.index')
            ->with('success', 'Data pelanggaran berhasil diperbarui.');
    }

    /**
     * Menghapus data pelanggaran.
     */
    public function destroy($id)
    {
        $violation = Violation::findOrFail($id);

        if ($violation->bukti_foto && Storage::disk('public')->exists($violation->bukti_foto)) {
            Storage::disk('public')->delete($violation->bukti_foto);
        }

        $violation->delete();

        return redirect()->back()->with('success', 'Data pelanggaran dihapus.');
    }
}