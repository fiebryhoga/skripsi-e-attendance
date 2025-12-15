<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        
        User::create([
            'name' => 'Budi Admin',
            'nip' => '19800101', 
            'email' => 'admin@sman1malang.sch.id',
            'role' => UserRole::ADMIN,
            'password' => Hash::make('password'),
            'avatar' => null, 
        ]);

        
        User::create([
            'name' => 'Siti Wali Kelas',
            'nip' => '19850202',
            'email' => 'siti@sman1malang.sch.id',
            'role' => UserRole::WALI_KELAS,
            'password' => Hash::make('password'),
            'avatar' => null,
        ]);

        
        User::create([
            'name' => 'Pak Tono Tatib',
            'nip' => '19750303',
            'email' => 'tono@sman1malang.sch.id',
            'role' => UserRole::GURU_TATIB,
            'password' => Hash::make('password'),
            'avatar' => null,
        ]);

        
        User::create([
            'name' => 'Bu Ani Matematika',
            'nip' => '19900404',
            'email' => 'ani@sman1malang.sch.id',
            'role' => UserRole::GURU_MAPEL,
            'password' => Hash::make('password'),
            'avatar' => null,
        ]);
    }
}