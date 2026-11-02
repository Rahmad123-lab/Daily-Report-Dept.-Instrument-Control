<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    public function test_active_user_can_authenticate_using_nik(): void
    {
        $user = User::factory()->create([
            'nik' => '20260001',
            'status' => 'active',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'nik' => $user->nik,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_user_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'nik' => '20260002',
            'status' => 'active',
        ]);

        $this->post('/login', [
            'nik' => $user->nik,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_pending_user_cannot_authenticate(): void
    {
        $user = User::factory()->create([
            'nik' => '20260003',
            'status' => 'pending',
        ]);

        $this->post('/login', [
            'nik' => $user->nik,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_inactive_user_cannot_authenticate(): void
    {
        $user = User::factory()->create([
            'nik' => '20260004',
            'status' => 'inactive',
        ]);

        $this->post('/login', [
            'nik' => $user->nik,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();

        $response->assertRedirect('/');
    }
}
