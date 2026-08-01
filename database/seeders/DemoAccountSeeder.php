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

        $demoEmail = setting('demo_email', 'demo@zamedu.co.mz');
        $schoolName = setting('school_name', 'ZamEdu');

        // Criar usuário demo
        $demoUser = User::firstOrCreate(
            ['email' => $demoEmail],
            [
                'name' => "Usuário Demo ({$schoolName})",
                'password' => Hash::make('demo1234'),
                'status' => 'active',
                'phone' => '840000000',
            ]
        );

        // Atribuir role
        if (!$demoUser->hasRole('admin')) {
            $demoUser->assignRole($adminRole);
        }

        $this->command->info("Demo user created: {$demoEmail} / demo1234");
    }
}
