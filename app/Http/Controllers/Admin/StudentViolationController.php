<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Violation;
use App\Models\ViolationCategory;
use App\Models\Classroom;
use App\Models\Student;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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

    public function create()
    {
        $classrooms = Classroom::with(['students' => function($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $categories = ViolationCategory::orderBy('grup')
                        ->orderBy('kode')
                        ->get();

        return view('admin.violations.create', compact('classrooms', 'categories'));
    }

    public function store(Request $request, WhatsAppService $waService)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'violation_category_id' => 'required|exists:violation_categories,id',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $data = $request->only(['student_id', 'violation_category_id', 'tanggal', 'catatan']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('evidence', 'public');
        }

        $violation = Violation::create($data);

        // Kirim Notifikasi
        $this->sendViolationNotification($violation, $waService);

        return redirect()->route('admin.student-violations.index')
            ->with('success', 'Data pelanggaran berhasil dicatat dan notifikasi dikirim.');
    }

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

    public function update(Request $request, $id, WhatsAppService $waService)
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

        if ($request->hasFile('bukti_foto')) {
            if ($violation->bukti_foto && Storage::disk('public')->exists($violation->bukti_foto)) {
                Storage::disk('public')->delete($violation->bukti_foto);
            }
            $data['bukti_foto'] = $request->file('bukti_foto')->store('evidence', 'public');
        }

        $violation->update($data);

        // Anti Spam: Hanya kirim jika Kategori, Tanggal, atau Siswa berubah
        if ($violation->wasChanged(['violation_category_id', 'tanggal', 'student_id'])) {
            $this->sendViolationNotification($violation, $waService);
            $message = 'Data diperbarui dan notifikasi revisi dikirim.';
        } else {
            $message = 'Data diperbarui (Tanpa notifikasi ulang).';
        }

        return redirect()->route('admin.student-violations.index')
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $violation = Violation::findOrFail($id);

        if ($violation->bukti_foto && Storage::disk('public')->exists($violation->bukti_foto)) {
            Storage::disk('public')->delete($violation->bukti_foto);
        }

        $violation->delete();

        return redirect()->back()->with('success', 'Data pelanggaran dihapus.');
    }

    // =========================================================================
    // HELPER KIRIM WA (Updated: Pakai deskripsi & kode)
    // =========================================================================
    
    private function sendViolationNotification($violation, WhatsAppService $waService)
    {
        // Load data student, classroom(teacher), dan category
        $violation->load(['student.classroom.teacher', 'category']);

        $student = $violation->student;
        $category = $violation->category;
        
        if (!$student || !$category) return;

        // Data Wali Kelas (Gunakan relasi 'teacher' sesuai konfirmasi sebelumnya)
        $waliKelas = $student->classroom->teacher ?? null;
        $nomorWaliKelas = ($waliKelas && !empty($waliKelas->phone)) ? $waliKelas->phone : null;

        // Format Tanggal
        Carbon::setLocale('id');
        $tanggalIndo = Carbon::parse($violation->tanggal)->translatedFormat('l, d F Y');

        // --- 1. KIRIM KE ORANG TUA ---
        if (!empty($student->phone_parent)) {
            $msgOrtu = $waService->formatViolationMessage(
                $student->name,
                $category->deskripsi, // <--- Pakai deskripsi
                $category->kode,      // <--- Pakai kode
                $tanggalIndo,
                $violation->catatan ?? '-'
            );
            $waService->send($student->phone_parent, $msgOrtu);
        }

        // --- 2. KIRIM KE WALI KELAS ---
        if ($nomorWaliKelas) {
            $msgGuru = $waService->formatViolationTeacherMessage(
                $student->name,
                $student->classroom->name,
                $category->deskripsi, // <--- Pakai deskripsi
                $category->kode,      // <--- Pakai kode
                $tanggalIndo
            );
            $waService->send($nomorWaliKelas, $msgGuru);
        }
    }
}