<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the super_admin role exists
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);

        // Give all permissions to super_admin role
        $superAdminRole->syncPermissions(Permission::all());

        // Create the superadmin user
        $user = User::updateOrCreate(
            ['email' => 'admin@visionarios.com'],
            [
                'name' => 'Full System Admin',
                'password' => Hash::make('superadmin2026'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Assign the role to the user
        $user->assignRole($superAdminRole);

        $this->command->info('SuperAdmin user created successfully!');
        $this->command->info('Email: admin@visionarios.com');
        $this->command->info('Password: superadmin2026');
    }
}
