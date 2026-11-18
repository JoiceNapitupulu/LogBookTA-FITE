<?php

namespace Tests\Feature;

use App\Models\AnggotaTugasAkhir;
use App\Models\DosenProfile;
use App\Models\MahasiswaProfile;
use App\Models\TugasAkhir;
use App\Models\UjianTa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UjianSiapMajuTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_denial_records_feedback_and_blocks_scheduling(): void
    {
        [, $ta] = $this->createStudentWithTa();
        $coordinator = $this->createCoordinator();
        $baak = User::factory()->create(['role' => 'baak']);
        $ujian = $this->createExam($ta);

        $this->actingAs($coordinator)->patch(route('ujian.verifikasi', $ujian), [
            'status_dokumen' => 'berkas_lengkap',
            'status_persetujuan_pembimbing' => 'tidak_diizinkan',
            'catatan_persetujuan_pembimbing' => 'Perbaiki bab pembahasan sesuai masukan pembimbing.',
        ])->assertSessionHas('success');

        $ujian->refresh();
        $this->assertSame('tidak_diizinkan', $ujian->status_persetujuan_pembimbing);
        $this->assertSame('Perbaiki bab pembahasan sesuai masukan pembimbing.', $ujian->catatan_persetujuan_pembimbing);
        $this->assertTrue($ujian->pengajuan_ulang_setelah->isFuture());

        $this->actingAs($baak)->patch(route('ujian.jadwal', $ujian))
            ->assertSessionHas('error');
    }

    public function test_reapplication_is_blocked_until_wait_period_ends_then_uses_batch_two(): void
    {
        Storage::fake('public');
        [$student, $ta] = $this->createStudentWithTa();
        $ujian = $this->createExam($ta, [
            'status_persetujuan_pembimbing' => 'tidak_diizinkan',
            'pengajuan_ulang_setelah' => now()->addWeek(),
        ]);
        $payload = [
            'jenis_ujian' => 'seminar_ta_1',
            'file_persyaratan' => UploadedFile::fake()->create('persyaratan.pdf', 100, 'application/pdf'),
            'konfirmasi_persetujuan_pembimbing' => '1',
        ];

        $this->actingAs($student)->post(route('ujian.store'), $payload)
            ->assertSessionHas('error');
        $this->assertDatabaseCount('ujian_ta', 1);

        $this->travel(8)->days();
        $this->actingAs($student)->post(route('ujian.store'), $payload)
            ->assertSessionHas('success');

        $this->assertDatabaseHas('ujian_ta', [
            'tugas_akhir_id' => $ta->id,
            'jenis_ujian' => 'seminar_ta_1',
            'batch_pengajuan' => 2,
            'status_persetujuan_pembimbing' => 'menunggu_verifikasi',
        ]);
    }

    public function test_coordinator_approval_allows_baak_to_schedule_exam(): void
    {
        [, $ta] = $this->createStudentWithTa();
        $coordinator = $this->createCoordinator();
        $secondExaminer = $this->createLecturer();
        $baak = User::factory()->create(['role' => 'baak']);
        $ujian = $this->createExam($ta);

        $this->actingAs($coordinator)->patch(route('ujian.verifikasi', $ujian), [
            'status_dokumen' => 'berkas_lengkap',
            'status_persetujuan_pembimbing' => 'diizinkan',
        ])->assertSessionHas('success');

        $this->actingAs($baak)->patch(route('ujian.jadwal', $ujian), [
            'tanggal_ujian' => now()->addDays(10)->toDateString(),
            'dosen_penguji_1_id' => $coordinator->dosenProfile->id,
            'dosen_penguji_2_id' => $secondExaminer->id,
            'ruangan' => 'Ruang Seminar 1',
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('ujian_ta', [
            'id' => $ujian->id,
            'status_persetujuan_pembimbing' => 'diizinkan',
            'status_ujian' => 'terjadwal',
        ]);
    }

    public function test_student_and_coordinator_can_view_readiness_status(): void
    {
        $this->withoutVite();
        [$student, $ta] = $this->createStudentWithTa();
        $coordinator = $this->createCoordinator();
        $this->createExam($ta);

        $this->actingAs($student)->get(route('ujian.index'))
            ->assertOk()
            ->assertSee('Form Siap Maju')
            ->assertSee('Batch 1');

        $this->actingAs($coordinator)->get(route('ujian.index'))
            ->assertOk()
            ->assertSee('Keputusan Form Siap Maju (Persetujuan Pembimbing)')
            ->assertSee('Menunggu izin pembimbing');
    }

    private function createStudentWithTa(): array
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $profile = MahasiswaProfile::create([
            'user_id' => $student->id,
            'nim' => 'TEST'.$student->id,
            'prodi' => 'S1 Informatika',
            'angkatan' => 2022,
            'is_ta' => true,
        ]);
        $ta = TugasAkhir::create([
            'mahasiswa_id' => $profile->id,
            'judul' => 'Pengujian Alur Siap Maju',
            'prodi' => 'S1 Informatika',
            'tahun_ajaran' => '2025/2026',
            'status' => 'seminar_ta',
        ]);
        AnggotaTugasAkhir::create([
            'tugas_akhir_id' => $ta->id,
            'mahasiswa_id' => $profile->id,
        ]);

        return [$student, $ta];
    }

    private function createCoordinator(): User
    {
        $user = User::factory()->create(['role' => 'dosen']);
        $this->createDosenProfile($user, true);

        return $user;
    }

    private function createLecturer(): DosenProfile
    {
        $user = User::factory()->create(['role' => 'dosen']);

        return $this->createDosenProfile($user);
    }

    private function createDosenProfile(User $user, bool $isCoordinator = false): DosenProfile
    {
        return DosenProfile::create([
            'user_id' => $user->id,
            'nidn' => 'N'.$user->id,
            'kode_dosen' => 'D'.$user->id,
            'prodi' => 'S1 Informatika',
            'is_koordinator_ta' => $isCoordinator,
        ]);
    }

    private function createExam(TugasAkhir $ta, array $attributes = []): UjianTa
    {
        return UjianTa::create(array_merge([
            'tugas_akhir_id' => $ta->id,
            'jenis_ujian' => 'seminar_ta_1',
            'file_persyaratan' => 'uploads/persyaratan/test.pdf',
            'status_dokumen' => 'menunggu_verifikasi',
            'status_persetujuan_pembimbing' => 'menunggu_verifikasi',
            'batch_pengajuan' => 1,
            'status_ujian' => 'diajukan',
        ], $attributes));
    }
}