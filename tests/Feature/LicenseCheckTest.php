<?php

namespace Tests\Feature;

use App\Models\License;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LicenseCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_license_allows_access()
    {
        $user = User::factory()->create();

        License::create([
            'client_name' => 'Escola Teste',
            'license_key' => 'KEY-ACTIVE-123',
            'expires_at' => now()->addDays(30),
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_grace_period_license_allows_access_with_warning()
    {
        $user = User::factory()->create();

        License::create([
            'client_name' => 'Escola Teste',
            'license_key' => 'KEY-GRACE-123',
            'expires_at' => now()->subDays(2),
            'grace_period_days' => 7,
            'status' => 'active', // Será recalculado para grace_period
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewHas('licenseGraceWarning');
    }

    public function test_suspended_license_redirects_to_suspended_page()
    {
        $user = User::factory()->create();

        License::create([
            'client_name' => 'Escola Teste',
            'license_key' => 'KEY-SUSPENDED-123',
            'expires_at' => now()->subDays(20),
            'grace_period_days' => 7,
            'status' => 'active', // Será recalculado para suspended
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertRedirect(route('license.suspended'));
    }
}
