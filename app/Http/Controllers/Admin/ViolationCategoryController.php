<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViolationCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\DataChangedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole; 

class ViolationCategoryController extends Controller
{
    /**
     * Helper: Ambil user Admin & Guru Tatib untuk notifikasi
     */
    private function getNotificationRecipients()
    {
        
        
        return User::where(function($query) {
                    $query->whereJsonContains('roles', UserRole::ADMIN->value)
                        ->orWhereJsonContains('roles', UserRole::GURU_TATIB->value);
                })->get();
    }

    public function index()
    {
        $violations = ViolationCategory::orderBy('kode', 'asc')->get();
        $groupedViolations = $violations->groupBy('grup');

        return view('admin.violationsCategory.index', compact('groupedViolations'));
    }

    public function create()
    {
        return view('admin.violationsCategory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'grup'      => 'required|in:A,B,C,D',
            'kode'      => 'required|string|unique:violation_categories,kode',
            'deskripsi' => 'required|string',
        ]);

        
        $category = ViolationCategory::create($request->only(['grup', 'kode', 'deskripsi']));

        
        $recipients = $this->getNotificationRecipients();
        Notification::send($recipients, new DataChangedNotification(
            'Kategori pelanggaran baru (' . $category->kode . ') ditambahkan oleh ' . Auth::user()->name
        ));


        
        return redirect()->route('admin.violations.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    
    public function edit(ViolationCategory $violationCategory)
    {
        
        return view('admin.violationsCategory.edit', compact('violationCategory'));
    }

    public function update(Request $request, ViolationCategory $violationCategory)
    {
        $request->validate([
            'grup'      => 'required|in:A,B,C,D',
            
            'kode'      => 'required|string|unique:violation_categories,kode,' . $violationCategory->id,
            'deskripsi' => 'required|string',
        ]);

        
        $violationCategory->update($request->only(['grup', 'kode', 'deskripsi']));

        
        $recipients = $this->getNotificationRecipients();
        Notification::send($recipients, new DataChangedNotification(
            'Kategori pelanggaran diperbarui: ' . $violationCategory->kode
        ));

        
        return redirect()->route('admin.violations.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(ViolationCategory $violationCategory)
    {
        
        $info = $violationCategory->kode . ' (' . $violationCategory->deskripsi . ')';
        
        $violationCategory->delete();

        
        $recipients = $this->getNotificationRecipients();
        Notification::send($recipients, new DataChangedNotification(
            'Kategori pelanggaran telah dihapus: ' . $info, 
            'danger'
        ));

        
        return redirect()->route('admin.violations.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}