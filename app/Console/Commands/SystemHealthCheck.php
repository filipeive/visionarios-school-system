<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SystemHealthCheck extends Command
{
    protected $signature = 'health:check 
                            {--fix : Tentar corrigir problemas encontrados}
                            {--detailed : Mostrar detalhes de cada verificação}';

    protected $description = 'Verifica a saúde do sistema de gestão escolar';

    public function handle(): int
    {
        $this->info('🏥 Verificando saúde do sistema...');
        $this->newLine();

        $issues = [];
        $fixes = [];

        // 1. Verificar conexão com banco de dados
        $this->info('📊 Verificando conexão com banco de dados...');
        try {
            DB::connection()->getPdo();
            $this->info('   ✅ Conexão OK');
        } catch (\Exception $e) {
            $this->error('   ❌ Erro de conexão: '.$e->getMessage());
            $issues[] = 'Banco de dados inacessível';
        }

        // 2. Verificar tabelas essenciais
        $this->info('📋 Verificando tabelas...');
        $tables = ['users', 'students', 'enrollments', 'grades', 'payments', 'classes'];
        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                $this->line("   {$table}: {$count} registos");
            } catch (\Exception $e) {
                $this->error("   ❌ Tabela '{$table}' não existe");
                $issues[] = "Tabela '{$table}' em falta";
            }
        }

        // 3. Verificar índices
        $this->info('🔍 Verificando índices de performance...');
        try {
            $indexes = $this->checkIndexes();
            if (empty($indexes['missing'])) {
                $this->info('   ✅ Todos os índices OK');
            } else {
                foreach ($indexes['missing'] as $index) {
                    $this->warn("   ⚠️  Índice em falta: {$index}");
                    $issues[] = "Índice '{$index}' não existe";
                }
            }
        } catch (\Exception $e) {
            $this->warn('   ⚠️  Não foi possível verificar índices');
        }

        // 4. Verificar configurações críticas
        $this->info('⚙️  Verificando configurações...');
        $checks = [
            'app.name' => config('app.name'),
            'current_school_year()' => function_exists('current_school_year') ? current_school_year() : 'N/A',
            'APP_URL' => config('app.url'),
        ];
        foreach ($checks as $key => $value) {
            if (empty($value) || $value === 'N/A') {
                $this->warn("   ⚠️  {$key}: não configurado");
                $issues[] = "Configuração '{$key}' em falta";
            } else {
                $this->line("   {$key}: {$value}");
            }
        }

        // 5. Verificar roles e permissões
        $this->info('🔐 Verificando sistema de permissões...');
        try {
            $roles = DB::table('roles')->count();
            $permissions = DB::table('permissions')->count();
            $this->line("   Roles: {$roles}");
            $this->line("   Permissões: {$permissions}");

            if ($roles === 0) {
                $this->warn('   ⚠️  Nenhum role criado - execute db:seed');
                $issues[] = 'Permissões não configuradas';
            }
        } catch (\Exception $e) {
            $this->warn('   ⚠️  Tabela de permissões não existe');
        }

        // 6. Verificar cache
        $this->info('💾 Verificando cache...');
        try {
            Cache::put('health_check_test', true, 10);
            if (Cache::get('health_check_test')) {
                $this->info('   ✅ Cache funcional');
            } else {
                $this->warn('   ⚠️  Cache não está a funcionar corretamente');
            }
        } catch (\Exception $e) {
            $this->warn('   ⚠️  Cache com problemas: '.$e->getMessage());
        }

        // 7. Verificar storage
        $this->info('📁 Verificando storage...');
        $storagePath = storage_path('app');
        if (is_dir($storagePath) && is_writable($storagePath)) {
            $this->info('   ✅ Storage OK');
        } else {
            $this->error('   ❌ Storage não é gravável');
            $issues[] = 'Storage não configurado';
        }

        // Resumo
        $this->newLine();
        if (empty($issues)) {
            $this->info('✅ Sistema saudável!');

            return Command::SUCCESS;
        }

        $this->warn('⚠️  Problemas encontrados:');
        foreach ($issues as $issue) {
            $this->line("   - {$issue}");
        }

        if ($this->option('fix')) {
            $this->newLine();
            $this->info('🔧 A tentar corrigir problemas...');
            $this->call('migrate', ['--force' => true]);
            $this->call('db:seed', ['--class' => 'PermissionSeeder', '--force' => true]);
            $this->info('✅ Correções aplicadas');
        }

        return Command::FAILURE;
    }

    private function checkIndexes(): array
    {
        $requiredColumns = [
            'enrollments' => ['student_id', 'school_year', 'status', 'class_id'],
            'payments' => ['student_id', 'status', 'due_date', 'year', 'month'],
            'grades' => ['student_id', 'subject_id', 'term', 'year', 'class_id'],
            'attendances' => ['student_id', 'attendance_date', 'class_id'],
        ];

        $missing = [];
        $existing = [];

        try {
            foreach ($requiredColumns as $table => $columns) {
                $tableIndexes = DB::select("SHOW INDEX FROM {$table}");
                $indexedColumns = array_column($tableIndexes, 'Column_name');

                foreach ($columns as $col) {
                    if (! in_array($col, $indexedColumns)) {
                        $missing[] = "{$table}.{$col}";
                    } else {
                        $existing[] = "{$table}.{$col}";
                    }
                }
            }
        } catch (\Exception $e) {
            // Tabela pode não existir
        }

        return ['missing' => $missing, 'existing' => $existing];
    }
}
