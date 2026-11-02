<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
    }

    public function test_new_users_can_register_with_pending_status(): void
    {
       $response = $this->post('/register', [
    'nik' => '20261001',
    'name' => 'Test User',
    'email' => 'test@example.com',
    'department' => 'Instrument & Control',
    'position' => 'Instrument Technician',
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!',
]);

$response->assertSessionHasNoErrors();

        $user = User::where('nik', '20261001')->first();

        $this->assertNotNull($user);

        $this->assertDatabaseHas('users', [
            'nik' => '20261001',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'department' => 'Instrument & Control',
            'position' => 'Instrument Technician',
            'role' => 'karyawan',
            'status' => 'pending',
        ]);

        $this->assertGuest();

        $response->assertRedirect(route('login'));

        $response->assertSessionHas(
            'status',
            'Pendaftaran berhasil dikirim. Akun Anda menunggu persetujuan admin.'
        );
    }

    public function test_registration_rejects_duplicate_nik(): void
    {
        User::factory()->create([
            'nik' => '20261002',
        ]);

        $response = $this->from('/register')->post('/register', [
            'nik' => '20261002',
            'name' => 'Another User',
            'email' => 'another@example.com',
            'department' => 'Instrument & Control',
            'position' => 'Technician',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/register');

        $response->assertSessionHasErrors('nik');

        $this->assertDatabaseCount('users', 1);
    }
}
