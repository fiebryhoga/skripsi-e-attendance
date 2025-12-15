<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); 

        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Student::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        
        $classrooms = Classroom::all();

        
        foreach ($classrooms as $classroom) {
            
            
            $angkatan = '2025'; 
            if (str_contains($classroom->name, 'XII')) {
                $angkatan = '2023';
            } elseif (str_contains($classroom->name, 'XI')) {
                $angkatan = '2024';
            }

            for ($i = 1; $i <= 32; $i++) {
                
                $gender = $faker->randomElement(['L', 'P']);
                $name = $gender == 'L' ? $faker->firstNameMale . ' ' . $faker->lastName : $faker->firstNameFemale . ' ' . $faker->lastName;
                
                $nis = $angkatan . sprintf('%02d', $classroom->id) . sprintf('%02d', $i);

                Student::create([
                    'nis'           => $nis,
                    'name'          => $name,
                    'gender'        => $gender,
                    'angkatan'      => $angkatan,
                    'religion'      => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
                    'nisn'          => $faker->unique()->numerify('00########'),
                    'phone_parent'  => '08' . $faker->numerify('##########'),
                    'classroom_id'  => $classroom->id, 
                    'photo'         => null,
                ]);
            }
        }
    }
}