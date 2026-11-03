<?php

namespace Tests\Feature;

use App\Models\DosenProfile;
use App\Models\User;
use Tests\TestCase;

class KaprodiKoordinatorTest extends TestCase
{
    /**
     * Test 1: Kaprodi hanya ada 1 di setiap prodi
     */
    public function test_kaprodi_hanya_ada_satu_di_setiap_prodi(): void
    {
        $prodis = ['S1 Informatika', 'S1 Sistem Informasi', 'S1 Teknik Elektro'];

        foreach ($prodis as $prodi) {
            $kaprodi = DosenProfile::getKaprodiByProdi($prodi);
            $this->assertNotNull($kaprodi, "Harus ada 1 Kaprodi di {$prodi}");
            $this->assertEquals($prodi, $kaprodi->prodi);

            $count = DosenProfile::where('prodi', $prodi)->where('is_kaprodi', true)->count();
            $this->assertEquals(1, $count, "Jumlah Kaprodi aktif di {$prodi} harus tepat 1");
        }
    }

    /**
     * Test 2: Kaprodi hanya bisa menetapkan Koordinator di prodinya sendiri
     */
    public function test_kaprodi_tidak_bisa_menetapkan_dosen_prodi_lain(): void
    {
        $kaprodiSi = User::where('email', 'kaprodi@del.ac.id')->first();
        $dosenJohannesIf = DosenProfile::where('kode_dosen', 'JHH')->first(); // S1 Informatika

        $this->assertEquals('S1 Sistem Informasi', $kaprodiSi->kaprodiProdi());
        $this->assertEquals('S1 Informatika', $dosenJohannesIf->prodi);

        // Kaprodi SI mencoba mengubah status koordinator dosen IF (harus 403)
        $response = $this->actingAs($kaprodiSi)
            ->withSession(['_token' => 'test-csrf-token'])
            ->post("/kaprodi/dosen/{$dosenJohannesIf->id}/tetapkan-koordinator", ['_token' => 'test-csrf-token']);

        $response->assertStatus(403);

        // Uji juga via endpoint toggle
        $responseToggle = $this->actingAs($kaprodiSi)
            ->withSession(['_token' => 'test-csrf-token'])
            ->patch("/kaprodi/dosen/{$dosenJohannesIf->id}/toggle-koordinator", ['_token' => 'test-csrf-token']);

        $responseToggle->assertStatus(403);
    }

    /**
     * Test 3: Kaprodi berhasil menetapkan Koordinator di prodinya sendiri
     */
    public function test_kaprodi_berhasil_menetapkan_dosen_di_prodinya_sendiri(): void
    {
        $kaprodiSi = User::where('email', 'kaprodi@del.ac.id')->first();
        $dosenMarlinaSi = DosenProfile::where('kode_dosen', 'MVN')->first(); // S1 Sistem Informasi

        $this->assertEquals('S1 Sistem Informasi', $kaprodiSi->kaprodiProdi());
        $this->assertEquals('S1 Sistem Informasi', $dosenMarlinaSi->prodi);

        $response = $this->actingAs($kaprodiSi)
            ->withSession(['_token' => 'test-csrf-token'])
            ->post("/kaprodi/dosen/{$dosenMarlinaSi->id}/tetapkan-koordinator", ['_token' => 'test-csrf-token']);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $dosenMarlinaSi->refresh();
        $this->assertTrue($dosenMarlinaSi->is_koordinator_ta);

        $koorAktif = DosenProfile::getKoordinatorByProdi('S1 Sistem Informasi');
        $this->assertNotNull($koorAktif);
        $this->assertEquals($dosenMarlinaSi->id, $koorAktif->id);
    }

    /**
     * Test 4: Koordinator TA hanya ada 1 tiap prodi (Pergantian otomatis koordinator lama)
     */
    public function test_koordinator_ta_hanya_ada_satu_tiap_prodi(): void
    {
        $kaprodiSi = User::where('email', 'kaprodi@del.ac.id')->first();
        $dosenMarlinaSi = DosenProfile::where('kode_dosen', 'MVN')->first(); // S1 Sistem Informasi
        $dosenToguSi = DosenProfile::where('kode_dosen', 'TNV')->first(); // S1 Sistem Informasi

        // 1. Pastikan Marlina adalah koordinator awal
        $dosenMarlinaSi->tetapkanSebagaiKoordinator();
        $this->assertTrue($dosenMarlinaSi->fresh()->is_koordinator_ta);

        // 2. Kaprodi menetapkan Togu sebagai koordinator baru di prodi yang sama
        $response = $this->actingAs($kaprodiSi)
            ->withSession(['_token' => 'test-csrf-token'])
            ->post("/kaprodi/dosen/{$dosenToguSi->id}/tetapkan-koordinator", ['_token' => 'test-csrf-token']);

        $response->assertRedirect();

        // 3. Verifikasi Togu aktif, dan Marlina otomatis dinonaktifkan
        $this->assertTrue($dosenToguSi->fresh()->is_koordinator_ta);
        $this->assertFalse($dosenMarlinaSi->fresh()->is_koordinator_ta);

        // 4. Hitung total koordinator aktif di S1 Sistem Informasi: HARUS TEPAT 1
        $count = DosenProfile::where('prodi', 'S1 Sistem Informasi')->where('is_koordinator_ta', true)->count();
        $this->assertEquals(1, $count, 'Jumlah koordinator TA aktif di S1 Sistem Informasi harus tepat 1 orang');
    }

    /**
     * Test 5: Menonaktifkan Koordinator TA di prodi sendiri
     */
    public function test_kaprodi_bisa_menonaktifkan_koordinator_di_prodinya(): void
    {
        $kaprodiSi = User::where('email', 'kaprodi@del.ac.id')->first();
        $dosenToguSi = DosenProfile::where('kode_dosen', 'TNV')->first();

        $dosenToguSi->tetapkanSebagaiKoordinator();
        $this->assertTrue($dosenToguSi->fresh()->is_koordinator_ta);

        $response = $this->actingAs($kaprodiSi)
            ->withSession(['_token' => 'test-csrf-token'])
            ->post("/kaprodi/dosen/{$dosenToguSi->id}/nonaktifkan-koordinator", ['_token' => 'test-csrf-token']);

        $response->assertRedirect();
        $this->assertFalse($dosenToguSi->fresh()->is_koordinator_ta);

        $count = DosenProfile::where('prodi', 'S1 Sistem Informasi')->where('is_koordinator_ta', true)->count();
        $this->assertEquals(0, $count);

        // Kembalikan Marlina sebagai koordinator SI
        $dosenMarlinaSi = DosenProfile::where('kode_dosen', 'MVN')->first();
        $dosenMarlinaSi->tetapkanSebagaiKoordinator();
    }
}
