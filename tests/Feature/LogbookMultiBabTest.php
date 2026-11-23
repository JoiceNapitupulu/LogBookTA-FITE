<?php

namespace Tests\Feature;

use App\Models\MahasiswaProfile;
use App\Models\TugasAkhir;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogbookMultiBabTest extends TestCase
{
    use RefreshDatabase;

    public function test_mahasiswa_can_submit_multiple_chapters_and_filter_by_one_chapter(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $profile = MahasiswaProfile::create([
            'user_id' => $user->id,
            'nim' => '11S21001',
            'prodi' => 'S1 Informatika',
            'angkatan' => 2021,
            'is_ta' => true,
        ]);
        $tugasAkhir = TugasAkhir::create([
            'mahasiswa_id' => $profile->id,
            'judul' => 'Penelitian Tugas Akhir',
            'prodi' => 'S1 Informatika',
            'tahun_ajaran' => '2025/2026',
        ]);
        $tugasAkhir->anggota()->attach($profile->id);

        $this->actingAs($user)
            ->post(route('logbook.store'), [
                'tanggal' => '2026-11-06',
                'bab_progresses' => ['BAB 1', 'BAB 2'],
                'deskripsi_kegiatan' => 'Multi bab activity',
            ])
            ->assertRedirect();

        $multiBabLogbook = $tugasAkhir->logbooks()->firstOrFail();
        $this->assertSame(['BAB 1', 'BAB 2'], $multiBabLogbook->bab_progresses);
        $this->assertSame('BAB 1', $multiBabLogbook->bab_progress);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('logbook.index', ['bab' => 'BAB 1']), false);

        $tugasAkhir->logbooks()->create([
            'tanggal' => '2026-11-05',
            'bab_progress' => 'BAB 3',
            'bab_progresses' => ['BAB 3'],
            'deskripsi_kegiatan' => 'Different chapter activity',
            'status_verifikasi' => 'pending',
        ]);

        $this->get(route('logbook.index', ['bab' => '2']))
            ->assertOk()
            ->assertSee('Multi bab activity')
            ->assertDontSee('Different chapter activity')
            ->assertSee('Semua Bab');
    }
}
