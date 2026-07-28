<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\ParentModel;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📦 Criando dados de teste...');

        // 1. CriarTurmas
        $classes = $this->createClasses();

        // 2. Criar Professores e Users
        $teachers = $this->createTeachers();

        // 3. Criar Disciplinas
        $subjects = $this->createSubjects();

        // 4. Criar Pais/Encarregados
        $parents = $this->createParents();

        // 5. Criar Alunos
        $students = $this->createStudents($parents);

        // 6. Criar Matrículas
        $this->createEnrollments($students, $classes);

        // 7. Criar Notas
        $this->createGrades($students, $classes, $subjects, $teachers);

        // 8. Criar Pagamentos
        $this->createPayments($students);

        $this->printCredentials();
        $this->command->info('✅ Dados de teste criados!');
    }

    private function createClasses(): array
    {
        $data = [
            ['name' => '1ª Classe A', 'grade_level' => 2, 'max' => 40],
            ['name' => '1ª Classe B', 'grade_level' => 2, 'max' => 40],
            ['name' => '2ª Classe A', 'grade_level' => 3, 'max' => 40],
            ['name' => '3ª Classe A', 'grade_level' => 4, 'max' => 35],
            ['name' => '4ª Classe A', 'grade_level' => 5, 'max' => 35],
            ['name' => '5ª Classe A', 'grade_level' => 6, 'max' => 30],
        ];

        $classes = [];
        foreach ($data as $item) {
            $class = ClassRoom::updateOrCreate(
                ['name' => $item['name'], 'school_year' => current_school_year()],
                [
                    'grade_level' => $item['grade_level'],
                    'max_students' => $item['max'],
                    'is_active' => true,
                    'school_year' => current_school_year(),
                ]
            );
            $classes[] = $class;
        }

        return $classes;
    }

    private function createTeachers(): array
    {
        $data = [
            ['name' => 'João Silva', 'email' => 'joao@teste.com'],
            ['name' => 'Maria Santos', 'email' => 'maria@teste.com'],
        ];

        $teachers = [];
        foreach ($data as $item) {
            $user = User::updateOrCreate(
                ['email' => $item['email']],
                ['name' => $item['name'], 'password' => Hash::make('professor123'), 'status' => 'active', 'email_verified_at' => now()]
            );
            $user->syncRoles(['teacher']);

            $teacher = Teacher::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => explode(' ', $item['name'])[0],
                    'last_name' => explode(' ', $item['name'])[1],
                    'email' => $item['email'],
                    'status' => 'active',
                    'hire_date' => now()->subYears(rand(1, 5)),
                ]
            );
            $teachers[] = $teacher;
        }

        return $teachers;
    }

    private function createSubjects(): array
    {
        $data = [
            ['name' => 'Língua Portuguesa', 'code' => 'LP'],
            ['name' => 'Matemática', 'code' => 'MAT'],
            ['name' => 'Estudo do Meio', 'code' => 'EM'],
            ['name' => 'Ciências Naturais', 'code' => 'CN'],
            ['name' => 'História', 'code' => 'HIST'],
            ['name' => 'Geografia', 'code' => 'GEO'],
        ];

        $subjects = [];
        foreach ($data as $item) {
            $subject = Subject::updateOrCreate(
                ['code' => $item['code']],
                ['name' => $item['name'], 'code' => $item['code'], 'is_active' => true, 'grade_level' => 2]
            );
            $subjects[] = $subject;
        }

        return $subjects;
    }

    private function createParents(): array
    {
        $data = [
            ['name' => 'Carlos Mendes', 'email' => 'carlos@teste.com'],
            ['name' => 'Ana Luísa', 'email' => 'ana@teste.com'],
            ['name' => 'Pedro Dinis', 'email' => 'pedro@teste.com'],
        ];

        $parents = [];
        foreach ($data as $item) {
            $user = User::updateOrCreate(
                ['email' => $item['email']],
                ['name' => $item['name'], 'password' => Hash::make('pai123'), 'status' => 'active', 'email_verified_at' => now()]
            );
            $user->syncRoles(['parent']);

            $parent = ParentModel::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'first_name' => explode(' ', $item['name'])[0],
                    'last_name' => explode(' ', $item['name'])[1] ?? '',
                ]
            );
            $parents[] = $parent;
        }

        return $parents;
    }

    private function createStudents(array $parents): array
    {
        $firstNames = ['Ana', 'Pedro', 'Maria', 'João', 'Filipe', 'Sofia', 'Miguel', 'Isabel', 'Carlos', 'Luísa'];
        $lastNames = ['Silva', 'Santos', 'Mendes', 'Chipango', 'André', 'Mabjaia', 'Tomo', 'Cossa', 'Jónimo', 'Dinis'];

        $students = [];
        $year = current_school_year();

        for ($i = 0; $i < 10; $i++) {
            $student = Student::updateOrCreate(
                ['student_number' => "VIS{$year}".str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                [
                    'first_name' => $firstNames[$i],
                    'last_name' => $lastNames[$i],
                    'gender' => $i % 2 === 0 ? 'male' : 'female',
                    'birthdate' => Carbon::now()->subYears(rand(6, 12)),
                    'birth_place' => 'Maputo',
                    'address' => 'Maputo',
                    'registration_date' => Carbon::now()->subMonths(rand(1, 6)),
                    'monthly_fee' => rand(80, 200) * 100,
                    'status' => 'active',
                    'parent_id' => $parents[$i % count($parents)]->user_id,
                ]
            );
            $students[] = $student;
        }

        return $students;
    }

    private function createEnrollments(array $students, array $classes): void
    {
        foreach ($students as $i => $student) {
            $class = $classes[$i % count($classes)];
            Enrollment::updateOrCreate(
                ['student_id' => $student->id, 'school_year' => current_school_year()],
                [
                    'class_id' => $class->id,
                    'status' => 'active',
                    'enrollment_date' => Carbon::now()->subMonths(rand(1, 4)),
                    'monthly_fee' => $student->monthly_fee,
                ]
            );
        }
    }

    private function createGrades(array $students, array $classes, array $subjects, array $teachers): void
    {
        $teacher = $teachers[0] ?? null;
        foreach ($students as $i => $student) {
            if ($i >= 5) {
                break;
            }
            $class = $classes[$i % count($classes)];
            foreach ([1, 2, 3] as $term) {
                foreach ($subjects as $subject) {
                    foreach (['test', 'continuous'] as $type) {
                        Grade::create([
                            'student_id' => $student->id,
                            'class_id' => $class->id,
                            'subject_id' => $subject->id,
                            'grade' => rand(10, 20),
                            'assessment_type' => $type,
                            'term' => $term,
                            'year' => current_school_year(),
                            'date_recorded' => Carbon::now()->subDays(rand(1, 30)),
                            'teacher_id' => $teacher?->id,
                        ]);
                    }
                }
            }
        }
    }

    private function createPayments(array $students): void
    {
        foreach ($students as $i => $student) {
            if ($i >= 5) {
                break;
            }
            for ($month = 1; $month <= 5; $month++) {
                $status = $month < 4 ? 'paid' : ($month == 4 ? 'pending' : 'overdue');
                Payment::updateOrCreate(
                    ['student_id' => $student->id, 'type' => 'mensalidade', 'month' => $month, 'year' => current_school_year()],
                    [
                        'reference_number' => 'VIS'.str_pad($student->id, 4, '0', STR_PAD_LEFT).current_school_year().str_pad($month, 2, '0', STR_PAD_LEFT),
                        'amount' => $student->monthly_fee ?? 15000,
                        'due_date' => Carbon::create(current_school_year(), $month, 5)->addMonth(),
                        'status' => $status,
                        'payment_date' => $status === 'paid' ? Carbon::now()->subDays(rand(5, 20)) : null,
                    ]
                );
            }
        }
    }

    private function printCredentials(): void
    {
        $this->command->newLine();
        $this->command->info('🔑 CREDENCIAIS DE TESTE');
        $this->command->line(str_repeat('=', 50));

        $rows = [
            ['Super Admin', 'superadmin@teste.com', 'superadmin123'],
            ['Admin', 'admin@teste.com', 'admin123'],
            ['Secretária', 'secretaria@teste.com', 'secretaria123'],
            ['Pedagogia', 'pedagogia@teste.com', 'pedagogia123'],
            ['Professor', 'joao@teste.com', 'professor123'],
            ['Pai/Encarregado', 'carlos@teste.com', 'pai123'],
        ];

        $this->command->table(['Perfil', 'Email', 'Senha'], $rows);

        $this->command->newLine();
        $this->command->warn('Execute primeiro: php artisan db:seed --class=PermissionSeeder');
    }
}
