<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use App\Models\Student;
use App\Models\ViolationCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViolationController extends Controller
{
    public function index()
    {
        $violations = Violation::with(['student', 'student.classroom', 'category', 'reporter'])
                        ->latest()
                        ->paginate(10);

        return view('admin.violations.index', compact('violations'));
    }

    public function edit($id)
    {
        // Cari data pelanggaran (kita pakai findOrFail manual karena nama parameter di route mungkin berbeda)
        $violation = Violation::findOrFail($id);
        
        $students = Student::with('classroom')->orderBy('name')->get();
        $categories = ViolationCategory::all()->groupBy('grup');

        return view('admin.violations.edit', compact('violation', 'students', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $violation = Violation::findOrFail($id);

        $request->validate([
            'student_id' => 'required',
            'violation_category_id' => 'required',
            'tanggal' => 'required|date',
            'catatan' => 'nullable',
            'bukti_foto' => 'nullable|image|max:10240',
        ]);

        // Handle File Upload Baru
        if ($request->hasFile('bukti_foto')) {
            // Hapus foto lama jika ada
            if ($violation->bukti_foto && Storage::disk('public')->exists($violation->bukti_foto)) {
                Storage::disk('public')->delete($violation->bukti_foto);
            }
            // Upload foto baru
            $fotoPath = $request->file('bukti_foto')->store('bukti-pelanggaran', 'public');
            $violation->bukti_foto = $fotoPath;
        }

        $violation->update([
            'student_id' => $request->student_id,
            'violation_category_id' => $request->violation_category_id,
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
            // kolom bukti_foto otomatis tersimpan jika ada perubahan di atas
        ]);

        return redirect()->route('admin.student-violations.index')
                        ->with('success', 'Data pelanggaran berhasil diperbarui.');
    }

    public function create()
    {
        $students = Student::with('classroom')->orderBy('name')->get();
        // Kelompokkan kategori agar mudah dipilih (Ringan/Sedang/Berat)
        $categories = ViolationCategory::all()->groupBy('grup'); 
        
        return view('admin.violations.create', compact('students', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'violation_category_id' => 'required|exists:violation_categories,id',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('bukti_foto')) {
            $fotoPath = $request->file('bukti_foto')->store('bukti-pelanggaran', 'public');
        }

        Violation::create([
            'student_id' => $request->student_id,
            'violation_category_id' => $request->violation_category_id,
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
            'bukti_foto' => $fotoPath,
        ]);
        
        return redirect()->route('admin.student-violations.index')
                         ->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function destroy(Violation $violation) // Ganti parameter sesuai Binding
    {
        // Cek jika ada foto, hapus dari storage
        if ($violation->bukti_foto && Storage::disk('public')->exists($violation->bukti_foto)) {
            Storage::disk('public')->delete($violation->bukti_foto);
        }

        $violation->delete();
        return back()->with('success', 'Data pelanggaran dihapus.');
    }

    public function show($id)
    {
        // Load relasi lengkap untuk detail
        $violation = Violation::with(['student', 'student.classroom', 'category', 'reporter'])->findOrFail($id);
        
        return view('admin.violations.show', compact('violation'));
    }
}