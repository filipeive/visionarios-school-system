<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\License;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Permission;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an active license to allow request execution
        License::create([
            'client_name' => 'Escola Teste',
            'license_key' => 'KEY-TEST-STUDENTS',
            'expires_at' => now()->addDays(30),
            'status' => 'active',
        ]);
    }

    public function test_authenticated_user_with_permission_can_view_student_list()
    {
        $user = User::factory()->create();
        Permission::findOrCreate('view_students');
        $user->givePermissionTo('view_students');

        Student::create([
            'first_name' => 'Test',
            'last_name' => 'Student',
            'student_number' => 'ZAM-2026-TEST',
            'gender' => 'male',
            'birthdate' => '2010-05-15',
            'registration_date' => now(),
            'monthly_fee' => 2500.00,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get('/students');

        $response->assertStatus(200);
        $response->assertSee('Test Student');
        $response->assertSee('ZAM-2026-TEST');
    }

    public function test_unauthenticated_user_cannot_access_students()
    {
        $response = $this->get('/students');
        $response->assertRedirect('/login');
    }
}
