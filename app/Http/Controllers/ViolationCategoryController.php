<?php

namespace App\Http\Controllers;

use App\Models\ViolationCategory;
use Illuminate\Http\Request;

class ViolationCategoryController extends Controller
{
    public function index()
    {
        // Ambil data, urutkan kode, dan kelompokkan per Grup
        $violations = ViolationCategory::orderBy('kode', 'asc')->get();
        $groupedViolations = $violations->groupBy('grup');

        // Pastikan path view sudah sesuai dengan folder baru Anda
        return view('admin.violationsCategory.index', compact('groupedViolations'));
    }
}