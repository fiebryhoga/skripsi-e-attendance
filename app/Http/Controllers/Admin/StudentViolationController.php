<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Violation;
use App\Models\ViolationCategory;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use App\Services\WhatsAppService;
use App\Notifications\DataChangedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class StudentViolationController extends Controller
{    
    private function getNotificationRecipients()
    {
        return User::where(function($query) {
             $query->whereJsonContains('roles', 'admin')
                   ->orWhereJsonContains('roles', 'guru_tatib');
        })->get();
    }

    public function index(Request $request)
    {
        $query = Violation::with(['student.classroom', 'category', 'reporter'])
            ->latest('tanggal')
            ->latest('created_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%") 
                  ->orWhereHas('classroom', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $violations = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('admin.violations._table_rows', compact('violations'))->render();
        }

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

        
        $this->sendViolationNotification($violation, $waService);

        $violation->load(['student', 'category']);
        $recipients = $this->getNotificationRecipients();
        Notification::send($recipients, new DataChangedNotification(
            'Pelanggaran Baru: ' . $violation->student->name . ' - ' . $violation->category->kode
        ));

        return redirect()->route('admin.student-violations.index')
            ->with('success', 'Data pelanggaran berhasil dicatat dan notifikasi dikirim.');
    }

    public function show($id)
    {
        
        $violation = Violation::with(['student.classroom', 'category', 'reporter'])
            ->findOrFail($id);

        return view('admin.violations.show', compact('violation'));
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

        
        if ($violation->wasChanged(['violation_category_id', 'tanggal', 'student_id'])) {
            $this->sendViolationNotification($violation, $waService);
            $message = 'Data diperbarui dan notifikasi revisi dikirim.';
        } else {
            $message = 'Data diperbarui (Tanpa notifikasi ulang).';
        }

        
        if ($violation->wasChanged(['violation_category_id', 'tanggal', 'student_id'])) {
                        
            
            $recipients = $this->getNotificationRecipients();
            Notification::send($recipients, new DataChangedNotification(
                'Update Pelanggaran: ' . $violation->student->name . ' (Data direvisi)'
            ));
        }

        return redirect()->route('admin.student-violations.index')
            ->with('success', $message);
    }

    public function destroy($id)
    {
        
        $violation = Violation::with(['student', 'category'])->findOrFail($id);

        
        $infoStudent = $violation->student->name ?? 'Siswa';
        $infoCategory = $violation->category->kode ?? '-';

        
        if ($violation->bukti_foto && Storage::disk('public')->exists($violation->bukti_foto)) {
            Storage::disk('public')->delete($violation->bukti_foto);
        }

        
        $violation->delete();

        
        $recipients = $this->getNotificationRecipients();
        
        Notification::send($recipients, new DataChangedNotification(
            'Pelanggaran Dihapus: ' . $infoStudent . ' (' . $infoCategory . ')',
            'danger'
        ));

        
        return redirect()->route('admin.student-violations.index')
            ->with('success', 'Data pelanggaran berhasil dihapus.');
    }

    
    
    
    
    private function sendViolationNotification($violation, WhatsAppService $waService)
    {
        
        $violation->load(['student.classroom.teacher', 'category']);

        $student = $violation->student;
        $category = $violation->category;
        
        if (!$student || !$category) return;

        
        $waliKelas = $student->classroom->teacher ?? null;
        $nomorWaliKelas = ($waliKelas && !empty($waliKelas->phone)) ? $waliKelas->phone : null;

        
        Carbon::setLocale('id');
        $tanggalIndo = Carbon::parse($violation->tanggal)->translatedFormat('l, d F Y');

        
        if (!empty($student->phone_parent)) {
            $msgOrtu = $waService->formatViolationMessage(
                $student->name,
                $category->deskripsi, 
                $category->kode,      
                $tanggalIndo,
                $violation->catatan ?? '-'
            );
            $waService->send($student->phone_parent, $msgOrtu);
        }

        
        if ($nomorWaliKelas) {
            $msgGuru = $waService->formatViolationTeacherMessage(
                $student->name,
                $student->classroom->name,
                $category->deskripsi, 
                $category->kode,      
                $tanggalIndo
            );
            $waService->send($nomorWaliKelas, $msgGuru);
        }
    }
}