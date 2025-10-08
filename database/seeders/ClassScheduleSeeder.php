<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassSchedule;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Teacher;

class ClassScheduleSeeder extends Seeder
{
    public function run()
    {
        $classes = ClassRoom::active()->currentYear()->get();
        $subjects = Subject::active()->get();
        $teachers = Teacher::active()->get();

        $schedules = [];

        foreach ($classes as $class) {
            $classSubjects = $subjects->random(rand(5, 8));
            $classTeacher = $teachers->random();

            foreach ($classSubjects as $subject) {
                for ($day = 1; $day <= 5; $day++) { // Segunda a Sexta
                    if (rand(0, 3) > 0) { // 75% de chance de ter aula nesse dia
                        $schedules[] = [
                            'class_id' => $class->id,
                            'subject_id' => $subject->id,
                            'teacher_id' => $classTeacher->id,
                            'weekday' => $day,
                            'start_time' => $this->generateTime(),
                            'end_time' => $this->generateTime(true),
                            'classroom' => 'Sala ' . rand(100, 300),
                            'academic_year' => now()->year,
                            'status' => 'active',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        // Inserir em lotes
        foreach (array_chunk($schedules, 50) as $chunk) {
            ClassSchedule::insert($chunk);
        }
    }

    private function generateTime($isEnd = false)
    {
        $times = ['08:00', '09:30', '11:00', '14:00', '15:30', '17:00'];
        return $times[array_rand($times)];
    }
}