<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Resetar cache de roles e permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ========== CRIAR PERMISSÕES ==========

        // Dashboard e Acesso Geral
        Permission::create(['name' => 'access_dashboard']);
        Permission::create(['name' => 'view_statistics']);

        // Gestão de Alunos
        Permission::create(['name' => 'view_students']);
        Permission::create(['name' => 'create_students']);
        Permission::create(['name' => 'edit_students']);
        Permission::create(['name' => 'delete_students']);
        Permission::create(['name' => 'manage_students']);
        Permission::create(['name' => 'view_own_students']); // Para pais

        // Gestão de Professores
        Permission::create(['name' => 'view_teachers']);
        Permission::create(['name' => 'create_teachers']);
        Permission::create(['name' => 'edit_teachers']);
        Permission::create(['name' => 'delete_teachers']);
        Permission::create(['name' => 'manage_teachers']);

        // Gestão de Turmas
        Permission::create(['name' => 'view_classes']);
        Permission::create(['name' => 'create_classes']);
        Permission::create(['name' => 'edit_classes']);
        Permission::create(['name' => 'delete_classes']);
        Permission::create(['name' => 'manage_classes']);
        Permission::create(['name' => 'view_own_classes']); // Para professores

        // Gestão de Disciplinas
        Permission::create(['name' => 'view_subjects']);
        Permission::create(['name' => 'create_subjects']);
        Permission::create(['name' => 'edit_subjects']);
        Permission::create(['name' => 'delete_subjects']);
        Permission::create(['name' => 'manage_subjects']);

        // Gestão de Matrículas
        Permission::create(['name' => 'view_enrollments']);
        Permission::create(['name' => 'create_enrollments']);
        Permission::create(['name' => 'edit_enrollments']);
        Permission::create(['name' => 'delete_enrollments']);
        Permission::create(['name' => 'manage_enrollments']);

        // Gestão de Presenças
        Permission::create(['name' => 'view_attendances']);
        Permission::create(['name' => 'mark_attendances']);
        Permission::create(['name' => 'manage_attendances']);
        Permission::create(['name' => 'view_own_class_attendances']); // Para professores

        // Gestão de Notas/Avaliações
        Permission::create(['name' => 'view_grades']);
        Permission::create(['name' => 'create_grades']);
        Permission::create(['name' => 'edit_grades']);
        Permission::create(['name' => 'delete_grades']);
        Permission::create(['name' => 'manage_grades']);
        Permission::create(['name' => 'view_own_grades']); // Para pais e alunos
        Permission::create(['name' => 'grade_own_subjects']); // Para professores

        // Gestão Financeira
        Permission::create(['name' => 'view_payments']);
        Permission::create(['name' => 'create_payments']);
        Permission::create(['name' => 'edit_payments']);
        Permission::create(['name' => 'delete_payments']);
        Permission::create(['name' => 'manage_payments']);
        Permission::create(['name' => 'view_own_payments']); // Para pais
        Permission::create(['name' => 'generate_payment_references']);
        Permission::create(['name' => 'process_payments']);

        // Gestão de Eventos
        Permission::create(['name' => 'view_events']);
        Permission::create(['name' => 'create_events']);
        Permission::create(['name' => 'edit_events']);
        Permission::create(['name' => 'delete_events']);
        Permission::create(['name' => 'manage_events']);

        // Comunicação
        Permission::create(['name' => 'send_notifications']);
        Permission::create(['name' => 'send_bulk_notifications']);
        Permission::create(['name' => 'manage_communications']);

        // Relatórios
        Permission::create(['name' => 'view_reports']);
        Permission::create(['name' => 'view_basic_reports']);
        Permission::create(['name' => 'view_financial_reports']);
        Permission::create(['name' => 'view_academic_reports']);
        Permission::create(['name' => 'export_reports']);

        // Gestão de Usuários e Sistema
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'create_users']);
        Permission::create(['name' => 'edit_users']);
        Permission::create(['name' => 'delete_users']);
        Permission::create(['name' => 'view_users']);

        // Configurações do Sistema
        Permission::create(['name' => 'manage_settings']);
        Permission::create(['name' => 'backup_system']);
        Permission::create(['name' => 'view_logs']);
        Permission::create(['name' => 'manage_school_year']);

        // Licenças de Staff
        Permission::create(['name' => 'manage_leave_requests']);
        Permission::create(['name' => 'approve_leave_requests']);
        Permission::create(['name' => 'create_leave_requests']);
        Permission::create(['name' => 'view_leave_requests']);

        // Observações e Registros
        Permission::create(['name' => 'manage_observations']);
        Permission::create(['name' => 'create_observations']);
        Permission::create(['name' => 'view_observations']);
        Permission::create(['name' => 'manage_student_records']);

        // ========== CRIAR ROLES E ATRIBUIR PERMISSÕES ==========

        // 1. SUPER ADMIN - Acesso total ao sistema
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // 2. ADMIN - Quase todos os acessos, exceto configurações muito sensíveis
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'access_dashboard', 'view_statistics',
            
            // Gestão de Alunos
            'view_students', 'create_students', 'edit_students', 'delete_students', 'manage_students',
            
            // Gestão de Professores
            'view_teachers', 'create_teachers', 'edit_teachers', 'delete_teachers', 'manage_teachers',
            
            // Gestão de Turmas
            'view_classes', 'create_classes', 'edit_classes', 'delete_classes', 'manage_classes',
            
            // Gestão de Disciplinas
            'view_subjects', 'create_subjects', 'edit_subjects', 'delete_subjects', 'manage_subjects',
            
            // Gestão de Matrículas
            'view_enrollments', 'create_enrollments', 'edit_enrollments', 'delete_enrollments', 'manage_enrollments',
            
            // Gestão de Presenças
            'view_attendances', 'mark_attendances', 'manage_attendances',
            
            // Gestão de Notas
            'view_grades', 'create_grades', 'edit_grades', 'delete_grades', 'manage_grades',
            
            // Gestão Financeira
            'view_payments', 'create_payments', 'edit_payments', 'delete_payments', 'manage_payments',
            'generate_payment_references', 'process_payments',
            
            // Gestão de Eventos
            'view_events', 'create_events', 'edit_events', 'delete_events', 'manage_events',
            
            // Comunicação
            'send_notifications', 'send_bulk_notifications', 'manage_communications',
            
            // Relatórios
            'view_reports', 'view_basic_reports', 'view_financial_reports', 'view_academic_reports', 'export_reports',
            
            // Gestão de Usuários
            'manage_users', 'create_users', 'edit_users', 'delete_users', 'view_users',
            
            // Configurações
            'manage_settings', 'view_logs', 'manage_school_year',
            
            // Licenças
            'manage_leave_requests', 'approve_leave_requests', 'view_leave_requests',
            
            // Observações
            'manage_observations', 'create_observations', 'view_observations', 'manage_student_records',
        ]);

        // 3. SECRETARIA - Gestão administrativa e financeira
        $secretaryRole = Role::create(['name' => 'secretary']);
        $secretaryRole->givePermissionTo([
            'access_dashboard', 'view_statistics',
            
            // Alunos
            'view_students', 'create_students', 'edit_students', 'manage_students',
            
            // Professores (apenas visualização)
            'view_teachers',
            
            // Turmas (apenas visualização)
            'view_classes',
            
            // Disciplinas (apenas visualização)
            'view_subjects',
            
            // Matrículas
            'view_enrollments', 'create_enrollments', 'edit_enrollments', 'manage_enrollments',
            
            // Financeiro
            'view_payments', 'create_payments', 'edit_payments', 'manage_payments',
            'generate_payment_references', 'process_payments',
            
            // Presenças (apenas visualização)
            'view_attendances',
            
            // Notas (apenas visualização)
            'view_grades',
            
            // Eventos
            'view_events', 'create_events', 'edit_events',
            
            // Comunicação
            'send_notifications', 'manage_communications',
            
            // Relatórios básicos
            'view_basic_reports', 'view_financial_reports', 'export_reports',
            
            // Licenças
            'view_leave_requests',
        ]);

        // 4. SEÇÃO PEDAGÓGICA - Gestão acadêmica
        $pedagogyRole = Role::create(['name' => 'pedagogy']);
        $pedagogyRole->givePermissionTo([
            'access_dashboard', 'view_statistics',
            
            // Gestão Acadêmica
            'view_classes', 'create_classes', 'edit_classes', 'manage_classes',
            'view_subjects', 'create_subjects', 'edit_subjects', 'manage_subjects',
            
            // Professores
            'view_teachers', 'create_teachers', 'edit_teachers', 'manage_teachers',
            
            // Alunos
            'view_students', 'create_students', 'edit_students', 'manage_students',
            
            // Matrículas
            'view_enrollments', 'create_enrollments', 'edit_enrollments', 'manage_enrollments',
            
            // Presenças e Notas
            'view_attendances', 'manage_attendances',
            'view_grades', 'manage_grades', 'create_grades', 'edit_grades',
            
            // Observações e Registros
            'manage_observations', 'create_observations', 'view_observations', 'manage_student_records',
            
            // Licenças
            'manage_leave_requests', 'approve_leave_requests', 'view_leave_requests',
            
            // Relatórios
            'view_reports', 'view_academic_reports', 'export_reports',
            
            // Comunicação
            'send_notifications', 'manage_communications',
            
            // Eventos
            'view_events', 'create_events', 'edit_events',
        ]);

        // 5. PROFESSOR - Gestão de suas turmas e disciplinas
        $teacherRole = Role::create(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'access_dashboard',
            
            // Suas turmas e disciplinas
            'view_own_classes', 'view_subjects',
            
            // Presenças
            'mark_attendances', 'view_own_class_attendances',
            
            // Notas/Avaliações
            'grade_own_subjects', 'create_grades', 'edit_grades', 'view_grades',
            
            // Alunos (apenas das suas turmas)
            'view_students',
            
            // Observações
            'create_observations', 'view_observations',
            
            // Licenças
            'create_leave_requests', 'view_leave_requests',
            
            // Comunicação
            'send_notifications',
            
            // Eventos
            'view_events',
            
            // Relatórios básicos
            'view_basic_reports',
        ]);

        // 6. PAI/ENCARREGADO - Acesso aos dados dos filhos
        $parentRole = Role::create(['name' => 'parent']);
        $parentRole->givePermissionTo([
            'access_dashboard',
            
            // Visualizar apenas dados dos próprios filhos
            'view_own_students',
            'view_own_grades',
            'view_own_payments',
            
            // Eventos e comunicações
            'view_events',
            
            // Relatórios básicos dos filhos
            'view_basic_reports',
        ]);

        // ========== CRIAR USUÁRIOS DE EXEMPLO ==========

        // 1. Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrador',
            'email' => 'superadmin@visionarios.co.mz',
            'password' => Hash::make('superadmin123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super_admin');

        // 2. Admin
        $admin = User::create([
            'name' => 'Administrador do Sistema',
            'email' => 'admin@visionarios.co.mz',
            'password' => Hash::make('admin123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // 3. Secretaria
        $secretary = User::create([
            'name' => 'Secretaria Escolar',
            'email' => 'secretaria@visionarios.co.mz',
            'password' => Hash::make('secretaria123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $secretary->assignRole('secretary');

        // 4. Seção Pedagógica
        $pedagogy = User::create([
            'name' => 'Coordenador Pedagógico',
            'email' => 'pedagogia@visionarios.co.mz',
            'password' => Hash::make('pedagogia123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $pedagogy->assignRole('pedagogy');

        // 5. Professor
        $teacher = User::create([
            'name' => 'Professor Exemplo',
            'email' => 'professor@visionarios.co.mz',
            'password' => Hash::make('professor123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $teacher->assignRole('teacher');

        // 6. Pai/Encarregado
        $parent = User::create([
            'name' => 'Encarregado de Educação',
            'email' => 'pai@visionarios.co.mz',
            'password' => Hash::make('pai123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $parent->assignRole('parent');

        $this->command->info('Sistema de permissões criado com sucesso!');
        $this->command->info('Usuários de exemplo criados:');
        $this->command->info('- Super Admin: superadmin@visionarios.co.mz / superadmin123');
        $this->command->info('- Admin: admin@visionarios.co.mz / admin123');
        $this->command->info('- Secretaria: secretaria@visionarios.co.mz / secretaria123');
        $this->command->info('- Pedagogia: pedagogia@visionarios.co.mz / pedagogia123');
        $this->command->info('- Professor: professor@visionarios.co.mz / professor123');
        $this->command->info('- Pai: pai@visionarios.co.mz / pai123');
    }
}