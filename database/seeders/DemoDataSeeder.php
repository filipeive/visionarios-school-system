<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Event;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Criar professores demo
        $this->createTeachers();
        
        // Criar pais demo
        $this->createParents();
        
        // Criar alunos demo
        $this->createStudents();
        
        // Criar matrículas
        $this->createEnrollments();
        
        // Criar alguns pagamentos
        $this->createPayments();
        
        // Criar eventos
        $this->createEvents();

        $this->command->info('Dados de demonstração criados com sucesso!');
    }

    private function createTeachers()
    {
        $teachers = [
            ['name' => 'Maria Santos Silva', 'email' => 'maria@visionarios.co.mz', 'qualification' => 'Licenciatura em Educação Infantil', 'specialization' => 'Educação Infantil'],
            ['name' => 'João Carlos Mendes', 'email' => 'joao@visionarios.co.mz', 'qualification' => 'Licenciatura em Matemática', 'specialization' => 'Matemática'],
            ['name' => 'Ana Paula Costa', 'email' => 'ana@visionarios.co.mz', 'qualification' => 'Licenciatura em Língua Portuguesa', 'specialization' => 'Português'],
            ['name' => 'Carlos Alberto Nunes', 'email' => 'carlos@visionarios.co.mz', 'qualification' => 'Licenciatura em Ciências Naturais', 'specialization' => 'Ciências'],
            ['name' => 'Fernanda Lima', 'email' => 'fernanda@visionarios.co.mz', 'qualification' => 'Licenciatura em Educação Física', 'specialization' => 'Educação Física'],
        ];

        foreach ($teachers as $index => $teacherData) {
            $user = User::create([
                'name' => $teacherData['name'],
                'email' => $teacherData['email'],
                'password' => bcrypt('123456'),
                'role' => 'teacher',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            $user->assignRole('teacher');

            $names = explode(' ', $teacherData['name']);
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'first_name' => $names[0],
                'last_name' => implode(' ', array_slice($names, 1)),
                'email' => $teacherData['email'],
                'phone' => '+258 84 ' . rand(100, 999) . ' ' . rand(1000, 9999),
                'hire_date' => now()->subMonths(rand(6, 36)),
                'qualification' => $teacherData['qualification'],
                'specialization' => $teacherData['specialization'],
                'bi_number' => rand(100000000, 999999999) . 'Q',
                'birth_date' => now()->subYears(rand(25, 45)),
                'gender' => rand(0, 1) ? 'male' : 'female',
                'address' => 'Quelimane, Zambézia',
                'salary' => rand(15000, 25000),
                'status' => 'active',
            ]);

            // Atribuir professor à uma turma aleatória
            $class = ClassRoom::where('grade_level', $index + 2)->first();
            if ($class) {
                $class->update(['teacher_id' => $teacher->id]);
            }
        }
    }

    private function createParents()
    {
        $parents = [
            ['name' => 'António Manuel Joaquim', 'email' => 'antonio@example.com', 'profession' => 'Comerciante'],
            ['name' => 'Helena Maria Cardoso', 'email' => 'helena@example.com', 'profession' => 'Enfermeira'],
            ['name' => 'José Francisco Mateus', 'email' => 'jose@example.com', 'profession' => 'Professor'],
            ['name' => 'Isabel Santos Pereira', 'email' => 'isabel@example.com', 'profession' => 'Contabilista'],
            ['name' => 'Manuel João da Costa', 'email' => 'manuel@example.com', 'profession' => 'Agricultor'],
        ];

        foreach ($parents as $parentData) {
            $user = User::create([
                'name' => $parentData['name'],
                'email' => $parentData['email'],
                'password' => bcrypt('123456'),
                'role' => 'parent',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            $user->assignRole('parent');

            $names = explode(' ', $parentData['name']);
            ParentModel::create([
                'user_id' => $user->id,
                'first_name' => $names[0],
                'last_name' => implode(' ', array_slice($names, 1)),
                'phone' => '+258 84 ' . rand(100, 999) . ' ' . rand(1000, 9999),
                'email' => $parentData['email'],
                'address' => 'Quelimane, Zambézia, Moçambique',
                'relationship' => rand(0, 1) ? 'Father' : 'Mother',
                'profession' => $parentData['profession'],
                'workplace' => 'Quelimane',
                'emergency_contact' => 'Familiar próximo',
                'emergency_phone' => '+258 84 ' . rand(100, 999) . ' ' . rand(1000, 9999),
                'bi_number' => rand(100000000, 999999999) . 'Q',
                'birth_date' => now()->subYears(rand(25, 45)),
            ]);
        }
    }

    private function createStudents()
    {
        $firstNames = ['Ana', 'Carlos', 'Beatriz', 'Daniel', 'Elena', 'Francisco', 'Graça', 'Hugo', 'Inês', 'João', 'Laura', 'Miguel', 'Natália', 'Pedro', 'Raquel'];
        $lastNames = ['Silva', 'Santos', 'Pereira', 'Costa', 'Ferreira', 'Rodrigues', 'Almeida', 'Martins', 'Cardoso', 'Mendes', 'Nunes', 'Gomes', 'Lopes', 'Sousa', 'Pinto'];

        $parents = ParentModel::all();
        $studentNumber = 1;

        foreach ($parents as $parent) {
            // Cada pai tem 1-3 filhos
            $numberOfChildren = rand(1, 3);
            
            for ($i = 0; $i < $numberOfChildren; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $age = rand(4, 12);
                
                $student = Student::create([
                    'student_number' => 'VIS' . str_pad($studentNumber, 4, '0', STR_PAD_LEFT),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'gender' => rand(0, 1) ? 'male' : 'female',
                    'birthdate' => now()->subYears($age)->subDays(rand(0, 365)),
                    'birth_place' => 'Quelimane, Zambézia',
                    'registration_date' => now()->startOfYear()->addDays(rand(0, 60)),
                    'monthly_fee' => rand(1500, 3000),
                    'parent_id' => $parent->user_id,
                    'address' => 'Quelimane, Zambézia',
                    'emergency_contact' => $parent->first_name . ' ' . $parent->last_name,
                    'emergency_phone' => $parent->phone,
                    'status' => 'active',
                ]);

                $studentNumber++;
            }
        }
    }

    private function createEnrollments()
    {
        $students = Student::all();
        $currentYear = date('Y');

        foreach ($students as $student) {
            // Determinar classe baseado na idade
            $age = $student->age;
            $gradeLevel = match (true) {
                $age <= 4 => 0, // Pré-Infantil
                $age == 5 => 1, // Pré-Escolar
                $age == 6 => 2, // 1ª Classe
                $age == 7 => 3, // 2ª Classe
                $age == 8 => 4, // 3ª Classe
                $age == 9 => 5, // 4ª Classe
                $age == 10 => 6, // 5ª Classe
                $age >= 11 => 7, // 6ª Classe
            };

            // Encontrar uma turma apropriada
            $availableClasses = ClassRoom::where('grade_level', $gradeLevel)
                ->where('current_students', '<', 'max_students')
                ->get();

            if ($availableClasses->count() > 0) {
                $selectedClass = $availableClasses->random();
                
                Enrollment::create([
                    'student_id' => $student->id,
                    'class_id' => $selectedClass->id,
                    'school_year' => $currentYear,
                    'status' => 'active',
                    'enrollment_date' => $student->registration_date,
                    'monthly_fee' => $student->monthly_fee,
                    'payment_day' => rand(5, 15),
                ]);

                // Atualizar contador de alunos na turma
                $selectedClass->increment('current_students');
            }
        }
    }

    private function createPayments()
    {
        $enrollments = Enrollment::with('student')->get();
        $currentYear = date('Y');
        $currentMonth = date('n');

        foreach ($enrollments as $enrollment) {
            // Criar pagamento de matrícula
            Payment::create([
                'reference_number' => Payment::generateReference($enrollment->student_id, 1, $currentYear),
                'student_id' => $enrollment->student_id,
                'enrollment_id' => $enrollment->id,
                'type' => 'matricula',
                'amount' => $enrollment->monthly_fee * 1.5, // Taxa de matrícula = 1.5x mensalidade
                'month' => 1,
                'year' => $currentYear,
                'due_date' => Carbon::create($currentYear, 1, 31),
                'payment_date' => rand(0, 1) ? Carbon::create($currentYear, 1, rand(15, 31)) : null,
                'status' => rand(0, 1) ? 'paid' : 'pending',
                'payment_method' => rand(0, 1) ? 'mpesa' : 'cash',
            ]);

            // Criar mensalidades (Fevereiro a mês atual)
            for ($month = 2; $month <= min($currentMonth + 1, 11); $month++) {
                $dueDate = Carbon::create($currentYear, $month, $enrollment->payment_day);
                $isPaid = $month < $currentMonth - 1 || rand(0, 100) < 70; // 70% de chance de estar pago
                $isOverdue = !$isPaid && $dueDate->isPast();

                Payment::create([
                    'reference_number' => Payment::generateReference($enrollment->student_id, $month, $currentYear),
                    'student_id' => $enrollment->student_id,
                    'enrollment_id' => $enrollment->id,
                    'type' => 'mensalidade',
                    'amount' => $enrollment->monthly_fee,
                    'month' => $month,
                    'year' => $currentYear,
                    'due_date' => $dueDate,
                    'payment_date' => $isPaid ? $dueDate->subDays(rand(0, 5)) : null,
                    'status' => $isPaid ? 'paid' : ($isOverdue ? 'overdue' : 'pending'),
                    'payment_method' => $isPaid ? collect(['mpesa', 'emola', 'cash', 'bank'])->random() : null,
                    'penalty' => $isOverdue ? $enrollment->monthly_fee * 0.1 : 0, // 10% de multa
                ]);
            }
        }
    }

    private function createEvents()
    {
        $events = [
            [
                'title' => 'Reunião de Pais - 1º Trimestre',
                'description' => 'Reunião para apresentação das notas e discussão do desenvolvimento dos alunos.',
                'event_date' => now()->addDays(15),
                'type' => 'meeting',
                'target_audience' => 'parents'
            ],
            [
                'title' => 'Exame do 1º Trimestre',
                'description' => 'Avaliações trimestrais de todas as disciplinas.',
                'event_date' => now()->addDays(30),
                'type' => 'exam',
                'target_audience' => 'students'
            ],
            [
                'title' => 'Dia das Crianças',
                'description' => 'Celebração especial com atividades lúdicas e recreativas.',
                'event_date' => now()->addDays(45),
                'type' => 'celebration',
                'target_audience' => 'all'
            ],
            [
                'title' => 'Formação Pedagógica',
                'description' => 'Workshop sobre novas metodologias de ensino.',
                'event_date' => now()->addDays(20),
                'type' => 'activity',
                'target_audience' => 'teachers'
            ],
            [
                'title' => 'Feira de Ciências',
                'description' => 'Exposição dos projetos científicos desenvolvidos pelos alunos.',
                'event_date' => now()->addDays(60),
                'type' => 'activity',
                'target_audience' => 'all'
            ]
        ];

        $admin = User::where('role', 'admin')->first();

        foreach ($events as $eventData) {
            Event::create([
                'title' => $eventData['title'],
                'description' => $eventData['description'],
                'event_date' => $eventData['event_date'],
                'start_time' => now()->setHour(rand(8, 16))->setMinute(0),
                'end_time' => now()->setHour(rand(8, 16))->addHours(rand(1, 3))->setMinute(0),
                'type' => $eventData['type'],
                'target_audience' => $eventData['target_audience'],
                'created_by' => $admin->id,
                'send_notification' => true,
            ]);
        }
    }
}