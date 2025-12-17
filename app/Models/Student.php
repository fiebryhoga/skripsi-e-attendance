<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'name',
        'gender',
        'religion',
        'nisn',
        'angkatan',
        'classroom_id',
        'phone_parent',
        'photo',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    // --- TAMBAHKAN INI ---
    public function attendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

}