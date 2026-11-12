<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Storage::fake('public');

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'nomor_kk' => '3275012345678901',
            'status_warga' => 'Tetap',
            'blok_rumah' => 'A1/01',
            'umur' => 30,
            'status_keluarga' => 'Kepala Keluarga',
            'foto_ktp' => UploadedFile::fake()->image('ktp.jpg'),
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'status' => 'Pending',
        ]);
        $response->assertRedirect(route('login'));
    }
}
