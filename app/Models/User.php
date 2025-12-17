<?php

namespace App\Models;

// Pastikan file Enum ini ada (lihat langkah 2)
use App\Enums\UserRole; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'nip',
        'roles', // Kolom JSON di database
        'avatar',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            // PENTING: Ini mengubah JSON ["admin", "guru"] menjadi Collection Enum
            'roles' => AsEnumCollection::of(UserRole::class), 
        ];
    }

    // Relasi ke Kelas (Jika dia Wali Kelas)
    public function classroom()
    {
        return $this->hasOne(Classroom::class, 'teacher_id');
    }

    /**
     * Cek apakah user memiliki role tertentu.
     * Bisa terima parameter Enum (UserRole::ADMIN) atau String ('admin')
     */
    public function hasRole(UserRole|string $role): bool
    {
        // 1. Jika inputnya string, ubah ke Enum dulu
        if (is_string($role)) {
            $role = UserRole::tryFrom($role);
        }

        // 2. Jika role tidak valid atau user belum punya roles, return false
        if (!$role || $this->roles === null) {
            return false;
        }

        // 3. Cek menggunakan contains (aman untuk Enum)
        return $this->roles->contains($role);
    }
    
    /**
     * Cek apakah user punya SALAH SATU dari role yang diminta.
     * Menggunakan Loop agar aman dari error "Object to string" pada intersect.
     */
    public function hasAnyRole(array $roles): bool
    {
        if ($this->roles === null || $this->roles->isEmpty()) {
            return false;
        }

        foreach ($roles as $role) {
            // Kita reuse fungsi hasRole yang sudah aman di atas
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    public function supervisedClassrooms()
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }
}