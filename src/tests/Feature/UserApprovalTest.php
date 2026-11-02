<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApprovalTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'nik' => '20260001',
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    private function createPendingUser(string $nik = '20260002'): User
    {
        return User::factory()->create([
            'nik' => $nik,
            'role' => 'karyawan',
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_view_pending_registrations(): void
    {
        $admin = $this->createAdmin();
        $pendingUser = $this->createPendingUser();

        $response = $this->actingAs($admin)
            ->get(route('admin.user-approvals.index'));

        $response->assertOk();
        $response->assertViewHas('users', function ($users) use ($pendingUser) {
            return $users->contains('id', $pendingUser->id);
        });
    }

    public function test_admin_can_approve_pending_user_and_assign_role(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createPendingUser();

        $response = $this->actingAs($admin)
            ->post(route('admin.user-approvals.approve', $user), [
                'role' => 'teknisi',
            ]);

        $response->assertRedirect(route('admin.user-approvals.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'teknisi',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_reject_pending_registration(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createPendingUser();

        $response = $this->actingAs($admin)
            ->post(route('admin.user-approvals.reject', $user));

        $response->assertRedirect(route('admin.user-approvals.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'inactive',
        ]);
    }

    public function test_non_admin_cannot_view_pending_registrations(): void
    {
        $user = User::factory()->create([
            'nik' => '20260003',
            'role' => 'teknisi',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->get(route('admin.user-approvals.index'));

        $response->assertForbidden();
    }

    public function test_non_admin_cannot_approve_pending_user(): void
    {
        $user = User::factory()->create([
            'nik' => '20260003',
            'role' => 'teknisi',
            'status' => 'active',
        ]);

        $pendingUser = $this->createPendingUser();

        $response = $this->actingAs($user)
            ->post(route('admin.user-approvals.approve', $pendingUser), [
                'role' => 'teknisi',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $pendingUser->id,
            'status' => 'pending',
        ]);
    }

    public function test_approval_rejects_invalid_role(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createPendingUser();

        $response = $this->actingAs($admin)
            ->from(route('admin.user-approvals.index'))
            ->post(route('admin.user-approvals.approve', $user), [
                'role' => 'super_admin',
            ]);

        $response->assertRedirect(route('admin.user-approvals.index'));
        $response->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'pending',
        ]);
    }

    public function test_already_approved_user_cannot_be_processed_again(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create([
            'nik' => '20260002',
            'role' => 'teknisi',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.user-approvals.index'))
            ->post(route('admin.user-approvals.approve', $user), [
                'role' => 'leader',
            ]);

        $response->assertRedirect(route('admin.user-approvals.index'));
        $response->assertSessionHasErrors('approval');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'teknisi',
            'status' => 'active',
        ]);
    }

    public function test_already_rejected_user_cannot_be_approved(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create([
            'nik' => '20260002',
            'role' => 'karyawan',
            'status' => 'inactive',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.user-approvals.index'))
            ->post(route('admin.user-approvals.approve', $user), [
                'role' => 'teknisi',
            ]);

        $response->assertRedirect(route('admin.user-approvals.index'));
        $response->assertSessionHasErrors('approval');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'karyawan',
            'status' => 'inactive',
        ]);
    }
}
