<?php

namespace Tests\Feature;

use App\Models\AnggotaTugasAkhir;
use App\Models\DosenProfile;
use App\Models\MahasiswaProfile;
use App\Models\ProposalTa;
use App\Models\TopikTa;
use App\Models\TugasAkhir;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TopikProposalTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test 1: Mahasiswa TA melihat topik secara BLIND (nama dosen pemilik TIDAK muncul)
     */
    public function test_mahasiswa_ta_melihat_katalog_topik_secara_blind(): void
    {
        $dosen = DosenProfile::with('user')->first();
        $this->assertNotNull($dosen);

        $topik = TopikTa::create([
            'dosen_id' => $dosen->id,
            'judul_topik' => 'Topik Blind Test Khusus Mahasiswa',
            'deskripsi' => 'Deskripsi untuk memastikan nama dosen tersembunyi.',
            'bidang' => 'Software Engineering',
            'prodi' => 'S1 Informatika',
            'kuota' => 2,
            'status' => 'dipublikasikan',
            'tahap1_selesai' => false,
        ]);

        $mhsUser = User::where('role', 'mahasiswa')
            ->whereHas('mahasiswaProfile', fn($q) => $q->where('is_ta', true))
            ->first();
        $this->assertNotNull($mhsUser);

        $response = $this->actingAs($mhsUser)->get('/topik');

        $response->assertStatus(200);
        $response->assertSee('Topik Blind Test Khusus Mahasiswa');
        // Blind rule: nama dosen pemilik topik TIDAK boleh tampil ke mahasiswa
        $response->assertDontSee($dosen->user->name);
    }

    /**
     * Test 2: Mahasiswa TA dapat mengajukan proposal baru
     */
    public function test_mahasiswa_ta_dapat_mengajukan_proposal(): void
    {
        $dosen = DosenProfile::first();
        $topik = TopikTa::create([
            'dosen_id' => $dosen->id,
            'judul_topik' => 'Topik Pengajuan Proposal Unik ' . uniqid(),
            'deskripsi' => 'Deskripsi topik proposal.',
            'bidang' => 'Data Science',
            'prodi' => 'S1 Informatika',
            'kuota' => 2,
            'status' => 'dipublikasikan',
            'tahap1_selesai' => false,
        ]);

        $mhsUser = User::where('role', 'mahasiswa')
            ->whereHas('mahasiswaProfile', fn($q) => $q->where('is_ta', true))
            ->first();
        $mhsProfile = $mhsUser->mahasiswaProfile;

        // Pastikan mahasiswa belum punya proposal lolos pada topik ini
        ProposalTa::where('mahasiswa_id', $mhsProfile->id)
            ->where('topik_ta_id', $topik->id)
            ->delete();

        $response = $this->actingAs($mhsUser)
            ->withSession(['_token' => 'test-csrf-token'])
            ->post("/topik/{$topik->id}/proposal", [
                '_token' => 'test-csrf-token',
                'judul_proposal' => 'Pengembangan Model Prediksi Mahasiswa',
                'ringkasan' => 'Ringkasan proposal detil untuk penelitian.',
                'link_ecourse' => 'https://ecourse.del.ac.id/mod/assign/view.php?id=9999',
            ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('proposal_ta', [
            'topik_ta_id' => $topik->id,
            'mahasiswa_id' => $mhsProfile->id,
            'judul_proposal' => 'Pengembangan Model Prediksi Mahasiswa',
            'status' => 'diajukan',
        ]);
    }

    /**
     * Test 3: Mahasiswa Non-TA dilarang mengakses dan diarahkan ke dashboard
     */
    public function test_mahasiswa_non_ta_dilarang_mengakses_topik_dan_proposal(): void
    {
        $mhsNonTaUser = User::where('role', 'mahasiswa')
            ->whereHas('mahasiswaProfile', fn($q) => $q->where('is_ta', false))
            ->first();
        $this->assertNotNull($mhsNonTaUser);

        // Akses halaman katalog topik harus diredirect ke dashboard dengan pesan error
        $responseGet = $this->actingAs($mhsNonTaUser)->get('/topik');
        $responseGet->assertRedirect(route('dashboard'));
        $responseGet->assertSessionHas('error');

        $topik = TopikTa::first();
        if ($topik) {
            $responsePost = $this->actingAs($mhsNonTaUser)
                ->withSession(['_token' => 'test-csrf-token'])
                ->post("/topik/{$topik->id}/proposal", [
                    '_token' => 'test-csrf-token',
                    'judul_proposal' => 'Proposal Ilegal Non TA',
                ]);
            $responsePost->assertRedirect(route('dashboard'));
            $responsePost->assertSessionHas('error');
        }
    }

    /**
     * Test 4: Dosen pemilik topik dapat menerima proposal (status lolos) dan otomatis sinkron ke tugas akhir
     */
    public function test_dosen_dapat_menerima_proposal_dan_sinkron_tugas_akhir(): void
    {
        $dosen = DosenProfile::with('user')->first();
        $topik = TopikTa::create([
            'dosen_id' => $dosen->id,
            'judul_topik' => 'Topik Seleksi Proposal ' . uniqid(),
            'deskripsi' => 'Deskripsi seleksi.',
            'bidang' => 'Cyber Security',
            'prodi' => 'S1 Informatika',
            'kuota' => 1,
            'status' => 'dipublikasikan',
            'tahap1_selesai' => true,
        ]);

        $mhsUser = User::where('role', 'mahasiswa')
            ->whereHas('mahasiswaProfile', fn($q) => $q->where('is_ta', true))
            ->first();
        $mhsProfile = $mhsUser->mahasiswaProfile;

        // Bersihkan data proposal lama mahasiswa jika ada pada topik ini
        ProposalTa::where('mahasiswa_id', $mhsProfile->id)
            ->where('topik_ta_id', $topik->id)
            ->delete();

        $proposal = ProposalTa::create([
            'topik_ta_id' => $topik->id,
            'mahasiswa_id' => $mhsProfile->id,
            'judul_proposal' => 'Proposal Terpilih Mahasiswa',
            'ringkasan' => 'Analisis keamanan jaringan.',
            'status' => 'diajukan',
        ]);

        // Dosen pemilik memberikan keputusan "lolos"
        $response = $this->actingAs($dosen->user)
            ->withSession(['_token' => 'test-csrf-token'])
            ->patch("/proposal/{$proposal->id}/keputusan", [
                '_token' => 'test-csrf-token',
                'keputusan' => 'lolos',
                'catatan_dosen' => 'Proposal sangat baik dan memenuhi standar.',
            ]);

        $response->assertSessionHas('success');

        // Pastikan status proposal terupdate
        $this->assertEquals('lolos', $proposal->fresh()->status);
        $this->assertEquals('Proposal sangat baik dan memenuhi standar.', $proposal->fresh()->catatan_dosen);

        // Pastikan TugasAkhir terbuat/terupdate dengan dosen pemilik sebagai pembimbing 1
        $this->assertDatabaseHas('tugas_akhir', [
            'mahasiswa_id' => $mhsProfile->id,
            'dosen_pembimbing_1_id' => $dosen->id,
            'judul' => 'Proposal Terpilih Mahasiswa',
        ]);

        // Pastikan anggota tugas akhir tercatat
        $this->assertDatabaseHas('anggota_tugas_akhir', [
            'mahasiswa_id' => $mhsProfile->id,
        ]);
    }

    /**
     * Test 5: Koordinator TA dapat melihat rekap proposal lolos dan mengubah publikasi topik
     */
    public function test_koordinator_dapat_melihat_rekap_dan_toggle_publikasi(): void
    {
        $dosenKoor = DosenProfile::where('is_koordinator_ta', true)->with('user')->first();
        $this->assertNotNull($dosenKoor);

        // Akses halaman rekap proposal lolos
        $responseRekap = $this->actingAs($dosenKoor->user)->get('/koordinator/rekap-proposal');
        $responseRekap->assertStatus(200);

        // Toggle publikasi topik
        $topik = TopikTa::first();
        $statusAwal = $topik->status;

        $responseToggle = $this->actingAs($dosenKoor->user)
            ->from(route('koordinator.index'))
            ->withSession(['_token' => 'test-csrf-token'])
            ->patch("/topik/{$topik->id}/publikasi", [
                '_token' => 'test-csrf-token',
            ]);

        $responseToggle->assertSessionHas('success');

        $expectedStatus = ($statusAwal === 'dipublikasikan') ? 'draft' : 'dipublikasikan';
        $this->assertEquals($expectedStatus, $topik->fresh()->status);
    }
}
