<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Siswa
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi ke Kategori Pelanggaran
    public function category()
    {
        return $this->belongsTo(ViolationCategory::class, 'violation_category_id');
    }

    // Relasi ke Pelapor (Guru/Admin)
    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}