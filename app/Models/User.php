<?php

namespace App\Models;


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
        'roles', 
        'avatar',
        'phone',
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
            
            'roles' => AsEnumCollection::of(UserRole::class), 
        ];
    }

    
    public function classroom()
    {
        return $this->hasOne(Classroom::class, 'teacher_id');
    }

    public function hasRole(UserRole|string $role): bool
    {
        
        if (is_string($role)) {
            $role = UserRole::tryFrom($role);
        }

        
        if (!$role || $this->roles === null) {
            return false;
        }

        
        return $this->roles->contains($role);
    }
    
    public function hasAnyRole(array $roles): bool
    {
        if ($this->roles === null || $this->roles->isEmpty()) {
            return false;
        }

        foreach ($roles as $role) {
            
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