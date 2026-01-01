<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use App\Enums\UserRole;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        $classrooms = Classroom::all();
        $subjects = Subject::all();
        
        
        
        
        $teachers = User::whereJsonContains('roles', UserRole::GURU_MAPEL)->get();

        if ($classrooms->isEmpty() || $subjects->isEmpty() || $teachers->isEmpty()) {
            $this->command->warn('Data kosong. Skip Schedule.');
            return;
        }

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        foreach ($classrooms as $classroom) {
            foreach ($days as $day) {
                
                $slots = [
                    ['start' => 1, 'end' => 3],
                    ['start' => 4, 'end' => 6],
                    ['start' => 7, 'end' => 8],
                ];

                foreach ($slots as $slot) {
                    Schedule::create([
                        'classroom_id' => $classroom->id,
                        'subject_id'   => $subjects->random()->id,
                        'user_id'      => $teachers->random()->id, 
                        'day'          => $day,
                        'jam_mulai'    => $slot['start'],
                        'jam_selesai'  => $slot['end'],
                    ]);
                }
            }
        }
    }
}