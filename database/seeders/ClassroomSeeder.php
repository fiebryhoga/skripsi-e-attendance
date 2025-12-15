<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $grades = ['X', 'XI', 'XII'];
        $sections = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

        foreach ($grades as $grade) {
            foreach ($sections as $section) {
                $className = "$grade-$section"; 
                
                Classroom::create([
                    'name' => $className,
                    'teacher_id' => null, 
                ]);
            }
        }
    }
}