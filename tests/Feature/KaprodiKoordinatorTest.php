<?php

namespace Tests\Feature;

use App\Models\DosenProfile;
use App\Models\User;
use Tests\TestCase;

class KaprodiKoordinatorTest extends TestCase
{
    /**
     * Test 1: Route kaprodi tidak lagi tersedia (404)
     */
    public function test_route_kaprodi_sudah_dihapus(): void
    {
        $user = User::where('role', 'kaprodi')->first() ?? User::where('role', 'dosen')->first();
        $dosen = DosenProfile::first();

        // Panel kaprodi harus 404
        $this->actingAs($user)->get('/kaprodi')->assertStatus(404);

        // Endpoint toggle/tetapkan koordinator kaprodi harus 404
        $this->actingAs($user)
            ->withSession(['_token' => 'test-csrf-token'])
            ->post("/kaprodi/dosen/{$dosen->id}/tetapkan-koordinator", ['_token' => 'test-csrf-token'])
            ->assertStatus(404);

        $this->actingAs($user)
            ->withSession(['_token' => 'test-csrf-token'])
            ->patch("/kaprodi/dosen/{$dosen->id}/toggle-koordinator", ['_token' => 'test-csrf-token'])
            ->assertStatus(404);
    }

    /**
     * Test 2: Panel Koordinator tetap dapat diakses oleh user Koordinator TA
     */
    public function test_koordinator_tetap_dapat_mengakses_panel_koordinator(): void
    {
        $dosenKoor = DosenProfile::where('is_koordinator_ta', true)->with('user')->first();
        $this->assertNotNull($dosenKoor);

        $response = $this->actingAs($dosenKoor->user)->get('/koordinator');
        $response->assertStatus(200);
    }

    /**
     * Test 3: Koordinator TA hanya ada 1 per prodi di level model operasional
     */
    public function test_koordinator_ta_hanya_ada_satu_tiap_prodi(): void
    {
        $dosenMarlinaSi = DosenProfile::where('kode_dosen', 'MVN')->first(); // S1 Sistem Informasi
        $dosenToguSi = DosenProfile::where('kode_dosen', 'TNV')->first(); // S1 Sistem Informasi

        // 1. Pastikan Marlina adalah koordinator awal
        $dosenMarlinaSi->tetapkanSebagaiKoordinator();
        $this->assertTrue($dosenMarlinaSi->fresh()->is_koordinator_ta);

        // 2. Tetapkan Togu sebagai koordinator baru di prodi yang sama
        $dosenToguSi->tetapkanSebagaiKoordinator();

        // 3. Verifikasi Togu aktif, dan Marlina otomatis dinonaktifkan
        $this->assertTrue($dosenToguSi->fresh()->is_koordinator_ta);
        $this->assertFalse($dosenMarlinaSi->fresh()->is_koordinator_ta);

        // 4. Hitung total koordinator aktif di S1 Sistem Informasi: HARUS TEPAT 1
        $count = DosenProfile::where('prodi', 'S1 Sistem Informasi')->where('is_koordinator_ta', true)->count();
        $this->assertEquals(1, $count, 'Jumlah koordinator TA aktif di S1 Sistem Informasi harus tepat 1 orang');

        // Kembalikan Marlina sebagai koordinator SI
        $dosenMarlinaSi->tetapkanSebagaiKoordinator();
    }
}
