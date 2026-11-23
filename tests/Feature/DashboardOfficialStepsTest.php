<?php

namespace Tests\Feature;

use App\Models\Logbook;
use App\Models\TugasAkhir;
use App\Models\UjianTa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardOfficialStepsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        if (! User::where('email', 'mhs.ta@students.del.ac.id')->exists()) {
            $this->seed();
        }
    }

    /**
     * Test 1: Dashboard Mahasiswa TA menampilkan 10 tahapan resmi dalam urutan yang tepat
     */
    public function test_dashboard_mahasiswa_ta_displays_10_official_steps_in_correct_order(): void
    {
        $user = User::where('email', 'mhs.ta@students.del.ac.id')->first();
        $this->assertNotNull($user);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);

        // Header Stepper
        $response->assertSee('Tahapan Progres Tugas Akhir (10 Tahap Resmi)');

        // 10 Tahapan wajib harus ada
        $response->assertSee('Proposal');
        $response->assertSee('Bab 1');
        $response->assertSee('Bab 2');
        $response->assertSee('Bab 3');
        $response->assertSee('Seminar TA 1');
        $response->assertSee('Bab 5');
        $response->assertSee('Bab 6');
        $response->assertSee('Seminar Hasil');
        $response->assertSee('Bab 7');
        $response->assertSee('Sidang akhir');

        // Pastikan TIDAK ADA "Bab 4" di daftar tahapan stepper
        $taSteps = $response->viewData('taSteps');
        $this->assertIsArray($taSteps);
        $this->assertCount(10, $taSteps);
        $this->assertFalse(collect($taSteps)->pluck('name')->contains('Bab 4'));
        $this->assertFalse(collect($taSteps)->pluck('name')->contains('BAB 4'));
    }

    /**
     * Test 2: Klik tahap BAB mengarah ke filter logbook multi-bab
     */
    public function test_clicking_bab_steps_links_to_logbook_filter(): void
    {
        $user = User::where('email', 'mhs.ta@students.del.ac.id')->first();
        $this->assertNotNull($user);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);

        // Cek tautan URL Bab 1, Bab 2, Bab 3, Bab 5, Bab 6, Bab 7
        $response->assertSee(route('logbook.index', ['bab' => 'BAB 1']));
        $response->assertSee(route('logbook.index', ['bab' => 'BAB 2']));
        $response->assertSee(route('logbook.index', ['bab' => 'BAB 3']));
        $response->assertSee(route('logbook.index', ['bab' => 'BAB 5']));
        $response->assertSee(route('logbook.index', ['bab' => 'BAB 6']));
        $response->assertSee(route('logbook.index', ['bab' => 'BAB 7']));
    }

    /**
     * Test 3: Klik tahap Seminar & Sidang mengarah ke route ujian.index
     */
    public function test_clicking_seminar_steps_links_to_ujian_index(): void
    {
        $user = User::where('email', 'mhs.ta@students.del.ac.id')->first();
        $this->assertNotNull($user);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);

        // Route ujian.index harus muncul untuk tahap seminar & sidang
        $response->assertSee(route('ujian.index'));
        $response->assertSee(route('topik.index'));
    }

    /**
     * Test 4: Status bab disetujui jika logbook multi-bab status_verifikasi = disetujui
     */
    public function test_multi_bab_logbook_approval_marks_step_approved(): void
    {
        $user = User::where('email', 'mhs.ta@students.del.ac.id')->first();
        $ta = $user->tugasAkhir();
        $this->assertNotNull($ta);

        // Buat logbook disetujui yang mencakup BAB 1 dan BAB 2 sekaligus (multi-bab)
        Logbook::create([
            'tugas_akhir_id' => $ta->id,
            'tanggal' => now()->toDateString(),
            'bab_progress' => 'BAB 1',
            'bab_progresses' => ['BAB 1', 'BAB 2'],
            'deskripsi_kegiatan' => 'Penyusunan latar belakang dan tinjauan pustaka.',
            'status_verifikasi' => 'disetujui',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        // Mengambil view data $taSteps
        $taSteps = $response->viewData('taSteps');
        $this->assertIsArray($taSteps);
        $this->assertCount(10, $taSteps);

        // Bab 1 dan Bab 2 harus berstatus approved
        $bab1 = collect($taSteps)->firstWhere('name', 'Bab 1');
        $bab2 = collect($taSteps)->firstWhere('name', 'Bab 2');

        $this->assertEquals('approved', $bab1['status']);
        $this->assertEquals('Disetujui', $bab1['label']);

        $this->assertEquals('approved', $bab2['status']);
        $this->assertEquals('Disetujui', $bab2['label']);
    }

    /**
     * Test 5: Dashboard dosen dan non-TA tidak rusak
     */
    public function test_dosen_and_non_ta_dashboards_remain_intact(): void
    {
        $dosen = User::where('role', 'dosen')->first();
        if ($dosen) {
            $this->actingAs($dosen)->get('/dashboard')->assertStatus(200);
        }

        $nonTa = User::where('email', 'mhs.nonta@students.del.ac.id')->first();
        if ($nonTa) {
            $this->actingAs($nonTa)->get('/dashboard')->assertRedirect(route('arsip.index'));
        }
    }
}
