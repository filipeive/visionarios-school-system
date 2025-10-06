<?php
// database/seeders/PermissionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Resetar cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar permissões
        $permissions = [
            // Gestão de Usuários
            'manage_users',
            'create_users',
            'edit_users', 
            'delete_users',
            'view_users',

            // Gestão de Alunos
            'manage_students',
            'create_students',
            'edit_students',
            'delete_students',
            'view_students',
            'view_own_students', // Para pais verem apenas seus filhos

            // Gestão de Professores
            'manage_teachers',
            'create_teachers',
            'edit_teachers',
            'delete_teachers',
            'view_teachers',

            // Gestão de Turmas
            'manage_classes',
            'create_classes',
            'edit_classes',
            'delete_classes',
            'view_classes',
            'view_own_classes', // Para professores verem apenas suas turmas

            // Gestão de Disciplinas
            'manage_subjects',
            'create_subjects',
            'edit_subjects',
            'delete_subjects',
            'view_subjects',

            // Gestão de Matrículas
            'manage_enrollments',
            'create_enrollments',
            'edit_enrollments',
            'delete_enrollments',
            'view_enrollments',

            // Gestão de Presenças
            'manage_attendances',
            'mark_attendances',
            'view_attendances',
            'view_own_class_attendances', // Para professores

            // Gestão de Notas/Avaliações
            'manage_grades',
            'create_grades',
            'edit_grades',
            'delete_grades',
            'view_grades',
            'view_own_grades', // Para pais e alunos
            'grade_own_subjects', // Para professores

            // Gestão Financeira
            'manage_payments',
            'create_payments',
            'edit_payments',
            'delete_payments',
            'view_payments',
            'view_own_payments', // Para pais
            'generate_payment_references',
            'process_payments',

            // Gestão de Eventos
            'manage_events',
            'create_events',
            'edit_events',
            'delete_events',
            'view_events',

            // Comunicação
            'send_notifications',
            'send_bulk_notifications',
            'manage_communications',

            // Relatórios
            'view_reports',
            'view_basic_reports',
            'view_financial_reports',
            'view_academic_reports',
            'export_reports',

            // Licenças de Staff
            'manage_leave_requests',
            'approve_leave_requests',
            'create_leave_requests',
            'view_leave_requests',

            // Configurações do Sistema
            'manage_settings',
            'backup_system',
            'view_logs',
            'manage_school_year',

            // Observações e Registros
            'manage_observations',
            'create_observations',
            'view_observations',
            'manage_student_records',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Criar roles e atribuir permissões

        // 1. ADMINISTRADOR - Acesso total
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // 2. SECRETARIA - Gestão administrativa e financeira
        $secretaryRole = Role::create(['name' => 'secretary']);
        $secretaryRole->givePermissionTo([
            // Alunos
            'manage_students', 'create_students', 'edit_students', 'view_students',
            
            // Matrículas
            'manage_enrollments', 'create_enrollments', 'edit_enrollments', 'view_enrollments',
            
            // Pagamentos
            'manage_payments', 'create_payments', 'edit_payments', 'view_payments',
            'generate_payment_references', 'process_payments',
            
            // Eventos e Comunicação
            'manage_events', 'create_events', 'edit_events', 'view_events',
            'send_notifications', 'manage_communications',
            
            // Relatórios
            'view_basic_reports', 'view_financial_reports',
            
            // Visualização
            'view_teachers', 'view_classes', 'view_subjects', 'view_attendances', 'view_grades',
        ]);

        // 3. SEÇÃO PEDAGÓGICA - Gestão acadêmica
        $pedagogyRole = Role::create(['name' => 'pedagogy']);
        $pedagogyRole->givePermissionTo([
            // Gestão Acadêmica
            'manage_classes', 'create_classes', 'edit_classes', 'view_classes',
            'manage_subjects', 'create_subjects', 'edit_subjects', 'view_subjects',
            
            // Professores
            'manage_teachers', 'create_teachers', 'edit_teachers', 'view_teachers',
            
            // Presenças e Notas
            'manage_attendances', 'view_attendances',
            'manage_grades', 'view_grades',
            
            // Observações e Registros
            'manage_observations', 'create_observations', 'view_observations',
            'manage_student_records',
            
            // Licenças
            'manage_leave_requests', 'approve_leave_requests', 'view_leave_requests',
            
            // Relatórios
            'view_reports', 'view_academic_reports', 'export_reports',
            
            // Comunicação
            'send_notifications', 'manage_communications',
            
            // Visualização
            'view_students', 'view_enrollments', 'view_payments',
        ]);

        // 4. PROFESSOR - Gestão de suas turmas
        $teacherRole = Role::create(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            // Suas turmas e disciplinas
            'view_own_classes', 'view_subjects',
            
            // Presenças
            'mark_attendances', 'view_own_class_attendances',
            
            // Notas/Avaliações
            'grade_own_subjects', 'create_grades', 'edit_grades',
            
            // Observações
            'create_observations', 'view_observations',
            
            // Licenças
            'create_leave_requests', 'view_leave_requests',
            
            // Comunicação
            'send_notifications',
            
            // Visualização básica
            'view_students', 'view_events',
        ]);

        // 5. PAI/ENCARREGADO - Acesso aos dados dos filhos
        $parentRole = Role::create(['name' => 'parent']);
        $parentRole->givePermissionTo([
            // Visualizar apenas dados dos próprios filhos
            'view_own_students',
            'view_own_grades',
            'view_own_payments',
            
            // Eventos e comunicações
            'view_events',
            
            // Relatórios básicos dos filhos
            'view_basic_reports',
        ]);

        // Criar usuário administrador inicial
        $admin = User::create([
            'name' => 'Administrador do Sistema',
            'email' => 'admin@visionarios.co.mz',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        $admin->assignRole('admin');

        // Criar usuário de secretaria
        $secretary = User::create([
            'name' => 'Secretaria Escolar',
            'email' => 'secretaria@visionarios.co.mz',
            'password' => bcrypt('secretaria123'),
            'role' => 'secretary',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        $secretary->assignRole('secretary');

        // Criar usuário da seção pedagógica
        $pedagogy = User::create([
            'name' => 'Seção Pedagógica',
            'email' => 'pedagogia@visionarios.co.mz',
            'password' => bcrypt('pedagogia123'),
            'role' => 'pedagogy',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        $pedagogy->assignRole('pedagogy');

        // Criar professor exemplo
        $teacher = User::create([
            'name' => 'Professor Demo',
            'email' => 'professor@visionarios.co.mz',
            'password' => bcrypt('professor123'),
            'role' => 'teacher',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        $teacher->assignRole('teacher');

        // Criar pai exemplo
        $parent = User::create([
            'name' => 'Pai Exemplo',
            'email' => 'pai@visionarios.co.mz',
            'password' => bcrypt('pai123'),
            'role' => 'parent',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        $parent->assignRole('parent');

        $this->command->info('Permissões e usuários iniciais criados com sucesso!');
    }
}
