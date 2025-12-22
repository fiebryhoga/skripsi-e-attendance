<?php

namespace App\Http\Controllers;

use App\Models\ViolationCategory;
use Illuminate\Http\Request;

class ViolationCategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori dikelompokkan per Grup.
     */
    public function index()
    {
        $violations = ViolationCategory::orderBy('kode', 'asc')->get();
        $groupedViolations = $violations->groupBy('grup');

        return view('admin.violationsCategory.index', compact('groupedViolations'));
    }

    /**
     * Menampilkan form tambah data.
     */
    public function create()
    {
        return view('admin.violationsCategory.create');
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'grup'      => 'required|in:A,B,C,D', // Sesuaikan grup yang ada
            'kode'      => 'required|string|unique:violation_categories,kode',
            'deskripsi' => 'required|string',
        ]);

        ViolationCategory::create($request->all());

        return redirect()->route('admin.violations.index')
            ->with('success', 'Kategori pelanggaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit data.
     */
    public function edit($id)
    {
        // Cari data berdasarkan ID
        $violation = ViolationCategory::findOrFail($id);
        return view('admin.violationsCategory.edit', compact('violation'));
    }

    /**
     * Menyimpan perubahan data (Update).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'grup'      => 'required|in:A,B,C,D',
            // Validasi unique kode, tapi abaikan untuk ID ini sendiri
            'kode'      => 'required|string|unique:violation_categories,kode,' . $id,
            'deskripsi' => 'required|string',
        ]);

        $violation = ViolationCategory::findOrFail($id);
        $violation->update($request->all());

        return redirect()->route('admin.violations.index')
            ->with('success', 'Kategori pelanggaran berhasil diperbarui.');
    }

    /**
     * Menghapus data.
     */
    public function destroy($id)
    {
        $violation = ViolationCategory::findOrFail($id);
        $violation->delete();

        return redirect()->route('admin.violations.index')
            ->with('success', 'Kategori pelanggaran berhasil dihapus.');
    }
}