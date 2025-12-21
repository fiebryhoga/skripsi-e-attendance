<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeacherTemplateExport;
use App\Imports\TeachersImport;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        // 1. HAPUS 'with('roles')' karena roles bukan relasi, tapi kolom biasa (automatis ter-load)
        $query = User::query(); 

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}&")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 2. GANTI 'whereHas' MENJADI Pengecekan Kolom
        // Kita cari user yang kolom roles-nya TIDAK NULL dan TIDAK KOSONG (bukan "[]")
        $query->whereNotNull('roles')
              ->where('roles', '!=', '[]'); 

        // 3. Ambil data
        $teachersCollection = $query->get();

        // 4. Sorting Manual (Logika tetap sama seperti sebelumnya)
        $sortedTeachers = $teachersCollection->sortBy(function ($user) {
            if ($user->hasRole(UserRole::ADMIN)) return 1;
            if ($user->hasRole(UserRole::WALI_KELAS)) return 2;
            if ($user->hasRole(UserRole::GURU_TATIB)) return 3;
            return 4;
        });

        // 5. Pagination Manual (Logika tetap sama)
        $page = $request->input('page', 1);
        $perPage = 10;
        
        $itemsForCurrentPage = $sortedTeachers->slice(($page - 1) * $perPage, $perPage)->values();

        $teachers = new LengthAwarePaginator(
            $itemsForCurrentPage,
            $sortedTeachers->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|unique:users,nip',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:8',
            'roles' => 'required|array', // Harus array
            'roles.*' => [new Enum(UserRole::class)], // Tiap item harus valid Enum
            'avatar' => 'nullable|image|max:2048',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = $request->except(['avatar', 'password']);
        $data['password'] = Hash::make($request->password);
        
        // Handle Avatar
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($data);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil ditambahkan');
    }

    public function edit(User $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|unique:users,nip,' . $teacher->id,
            'email' => 'nullable|email|unique:users,email,' . $teacher->id,
            'roles' => 'required|array',
            'roles.*' => [new Enum(UserRole::class)],
            'avatar' => 'nullable|image|max:2048',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = $request->except(['avatar', 'password', 'roles']); // Roles kita handle manual biar aman
        
        // Update Roles
        $teacher->roles = $request->roles;

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($teacher->avatar) Storage::disk('public')->delete($teacher->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $teacher->update($data);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui');
    }

    public function destroy(User $teacher)
    {
        if ($teacher->avatar) Storage::disk('public')->delete($teacher->avatar);
        $teacher->delete();
        return back()->with('success', 'Data guru berhasil dihapus');
    }

    public function downloadTemplate()
    {
        return Excel::download(new TeacherTemplateExport, 'template_data_guru.xlsx');
    }

    // 2. PROSES IMPORT
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new TeachersImport, $request->file('file'));
            
            return redirect()->back()->with('success', 'Data Guru berhasil diimport!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = "Gagal Import pada Baris ke-" . $failures[0]->row() . ": " . $failures[0]->errors()[0];
            
            return redirect()->back()->with('error', $errorMsg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}