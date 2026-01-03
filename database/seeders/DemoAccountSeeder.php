<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoAccountSeeder extends Seeder
{
    public function run(): void
    {
        // Garantir que o role admin existe
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Criar usuário demo
        $demoUser = User::firstOrCreate(
            ['email' => 'demo@visionarios.co.mz'],
            [
                'name' => 'Usuário Demo (Visionários)',
                'password' => Hash::make('demo1234'),
                'status' => 'active',
                'phone' => '840000000',
            ]
        );

        // Atribuir role
        if (!$demoUser->hasRole('admin')) {
            $demoUser->assignRole($adminRole);
        }

        $this->command->info('Demo user created: demo@visionarios.co.mz / demo1234');
    }
}
