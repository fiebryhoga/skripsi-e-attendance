<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case WALI_KELAS = 'wali_kelas';
    case GURU_TATIB = 'guru_tatib';
    case GURU_MAPEL = 'guru_mapel';
    
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Staf Admin',
            self::WALI_KELAS => 'Guru Wali Kelas',
            self::GURU_TATIB => 'Guru Tata Tertib',
            self::GURU_MAPEL => 'Guru Mata Pelajaran',
        };
    }
}