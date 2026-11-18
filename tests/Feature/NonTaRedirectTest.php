<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class NonTaRedirectTest extends TestCase
{
    use RefreshDatabase;

    private function createStudent(bool $isTa, string $email): User
    {
        $user = User::factory()->create([
            'name' => $isTa ? 'Mahasiswa TA' : 'Mahasiswa Non-TA',
            'email' => $email,
            'role' => 'mahasiswa',
        ]);

        $user->mahasiswaProfile()->create([
            'nim' => $isTa ? '12S24001' : '12S24002',
            'prodi' => 'S1 Sistem Informasi',
            'angkatan' => 2024,
            'is_ta' => $isTa,
        ]);

        return $user;
    }

    public function test_non_ta_normal_login_redirects_to_arsip(): void
    {
        $this->createStudent(false, 'nonta@test.com');

        $this->post('/login', [
            'username' => 'nonta@test.com',
            'password' => 'password',
        ])->assertRedirect(route('arsip.index'));
    }

    public function test_non_ta_dashboard_redirects_to_arsip(): void
    {
        $user = $this->createStudent(false, 'nonta@test.com');

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('arsip.index'));
    }

    public function test_non_ta_root_redirects_to_arsip(): void
    {
        $user = $this->createStudent(false, 'nonta@test.com');

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect(route('arsip.index'));
    }

    public function test_non_ta_demo_login_redirects_to_arsip(): void
    {
        $this->createStudent(
            false,
            'mhs.nonta@students.del.ac.id'
        );

        $this->get('/demo-login/mahasiswa_non_ta')
            ->assertRedirect(route('arsip.index'));
    }

    public function test_mahasiswa_ta_can_still_access_dashboard(): void
    {
        $user = $this->createStudent(true, 'mhs.ta@test.com');

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();
    }
}
