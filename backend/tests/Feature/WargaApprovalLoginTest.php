<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WargaApprovalLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_pending_status_cannot_login_and_sees_approval_warning(): void
    {
        $user = User::create([
            'name' => 'Warga Baru',
            'email' => 'wargabaru@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'Warga',
            'status' => 'Pending',
        ]);

        $response = $this->post('/login', [
            'email' => 'wargabaru@gmail.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('warning_message');
        $response->assertSessionHasErrors('email');
    }

    public function test_user_with_aktif_status_can_login_normally(): void
    {
        $user = User::create([
            'name' => 'Warga Disetujui',
            'email' => 'wargaaktif@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'Warga',
            'status' => 'Aktif',
        ]);

        $response = $this->post('/login', [
            'email' => 'wargaaktif@gmail.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }
}
