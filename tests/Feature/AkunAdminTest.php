<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AkunAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_admin_registers_as_pending_verification(): void
    {
        $response = $this->post(route('admin.daftar.submit'), [
            'name' => 'Admin Baru',
            'email' => 'baru@psbsd.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHas('success', 'Pendaftaran berhasil. Akun Anda bisa dipakai setelah diverifikasi oleh admin lain.');

        $user = User::where('email', 'baru@psbsd.test')->firstOrFail();
        $this->assertFalse($user->isVerified());
        $this->assertTrue(Hash::check('rahasia123', $user->password));
        $this->assertGuest();
    }

    public function test_registration_requires_matching_password_confirmation(): void
    {
        $response = $this->post(route('admin.daftar.submit'), [
            'name' => 'Admin Baru',
            'email' => 'baru@psbsd.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'beda12345',
        ]);

        $response->assertSessionHasErrors(['password' => 'Konfirmasi kata sandi tidak sama.']);
        $this->assertDatabaseMissing('users', ['email' => 'baru@psbsd.test']);
    }

    public function test_pending_admin_cannot_log_in(): void
    {
        $pending = User::factory()->pendingVerification()->create();

        $response = $this->post(route('admin.login.submit'), [
            'email' => $pending->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email' => 'Akun Anda belum diverifikasi oleh admin lain.']);
        $this->assertGuest();
    }

    public function test_verified_admin_can_verify_pending_account_which_can_then_log_in(): void
    {
        $admin = User::factory()->create();
        $pending = User::factory()->pendingVerification()->create();

        $this->actingAs($admin)
            ->post(route('admin.akun.verify', $pending))
            ->assertSessionHas('success');

        $pending->refresh();
        $this->assertTrue($pending->isVerified());
        $this->assertTrue($pending->verifier->is($admin));

        $this->post(route('admin.logout'));
        $this->post(route('admin.login.submit'), [
            'email' => $pending->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($pending);
    }

    public function test_admin_can_reject_pending_account(): void
    {
        $admin = User::factory()->create();
        $pending = User::factory()->pendingVerification()->create();

        $this->actingAs($admin)
            ->delete(route('admin.akun.destroy', $pending))
            ->assertSessionHas('success');

        $this->assertModelMissing($pending);
    }

    public function test_admin_can_delete_another_verified_admin(): void
    {
        $admin = User::factory()->create();
        $otherAdmin = User::factory()->create(['name' => 'Admin Lama']);

        $this->actingAs($admin)
            ->delete(route('admin.akun.destroy', $otherAdmin))
            ->assertSessionHas('success', 'Akun admin Admin Lama berhasil dihapus.');

        $this->assertModelMissing($otherAdmin);
    }

    public function test_last_verified_admin_cannot_be_deleted(): void
    {
        $admin = User::factory()->create();
        User::factory()->pendingVerification()->create();

        $this->actingAs($admin)
            ->delete(route('admin.akun.destroy', $admin))
            ->assertSessionHas('error', 'Akun tidak dapat dihapus. Minimal harus ada 1 admin aktif.');

        $this->assertModelExists($admin);
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_deleting_own_account_is_logged_out(): void
    {
        $admin = User::factory()->create();
        User::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.akun.destroy', $admin))
            ->assertRedirect(route('admin.login'));

        $this->assertModelMissing($admin);
        $this->assertGuest();
    }

    public function test_guest_cannot_verify_account(): void
    {
        $pending = User::factory()->pendingVerification()->create();

        $this->post(route('admin.akun.verify', $pending))->assertRedirect(route('admin.login'));

        $this->assertFalse($pending->refresh()->isVerified());
    }

    public function test_account_page_lists_pending_accounts(): void
    {
        $admin = User::factory()->create();
        User::factory()->pendingVerification()->create(['name' => 'Calon Admin Kedua']);

        $this->actingAs($admin)
            ->get(route('admin.akun'))
            ->assertOk()
            ->assertSeeInOrder(['Menunggu Verifikasi', 'Calon Admin Kedua', 'Admin Aktif', $admin->name]);
    }
}
