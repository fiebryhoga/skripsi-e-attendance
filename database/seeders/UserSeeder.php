<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole; 
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        
        User::create([
            'name' => 'Administrator IT',
            'email' => 'admin@sman1.sch.id',
            'password' => Hash::make('password'),
            'nip' => '199001012022011001',
            'roles' => [UserRole::ADMIN], 
        ]);

        
        
        User::create([
            'name' => 'Pak Budi (Guru Tatib)',
            'email' => 'tatib@sman1.sch.id',
            'password' => Hash::make('password'),
            'nip' => '198505052010011005',
            'roles' => [UserRole::GURU_MAPEL, UserRole::GURU_TATIB], 
        ]);

        
        for ($i = 1; $i <= 15; $i++) {
            
            
            $roles = [UserRole::GURU_MAPEL]; 

            
            if ($i <= 5) {
                $roles[] = UserRole::WALI_KELAS; 
            }

            User::create([
                'name' => $faker->firstName . ' ' . $faker->lastName . ', S.Pd', 
                'email' => "guru{$i}@sman1.sch.id", 
                'password' => Hash::make('password'),
                'nip' => $faker->unique()->numerify('19##########00##'), 
                'roles' => $roles,
            ]);
        }
    }
}