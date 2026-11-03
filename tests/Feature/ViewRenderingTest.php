<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DosenProfile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ViewRenderingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_mahasiswa_can_view_all_authorized_pages(): void
    {
        $user = User::where('role', 'mahasiswa')
            ->whereHas('mahasiswaProfile', fn($q) => $q->where('is_ta', true))
            ->first();
        if (!$user) {
            $this->markTestSkipped('No mahasiswa TA user found.');
        }

        $this->actingAs($user)->get('/dashboard')->assertStatus(200);
        $this->actingAs($user)->get('/bimbingan')->assertStatus(200);
        $this->actingAs($user)->get('/logbook')->assertStatus(200);
        $this->actingAs($user)->get('/ujian')->assertStatus(200);
        $this->actingAs($user)->get('/arsip')->assertStatus(200);
    }

    public function test_dosen_can_view_all_authorized_pages(): void
    {
        $user = User::where('role', 'dosen')->first();
        if (!$user) {
            $this->markTestSkipped('No dosen user found.');
        }

        $this->actingAs($user)->get('/dashboard')->assertStatus(200);
        $this->actingAs($user)->get('/bimbingan')->assertStatus(200);
        $this->actingAs($user)->get('/logbook')->assertStatus(200);
        $this->actingAs($user)->get('/ujian')->assertStatus(200);
        $this->actingAs($user)->get('/arsip')->assertStatus(200);
    }

    public function test_koordinator_can_view_koordinator_panel(): void
    {
        $dosen = DosenProfile::where('is_koordinator_ta', true)->with('user')->first();
        if (!$dosen || !$dosen->user) {
            $this->markTestSkipped('No koordinator found.');
        }

        $this->actingAs($dosen->user)->get('/koordinator')->assertStatus(200);
    }

    public function test_kaprodi_can_view_kaprodi_panel(): void
    {
        $kaprodi = User::where('role', 'kaprodi')->first() 
            ?? User::where('role', 'dosen')->whereHas('dosenProfile', fn($q) => $q->where('is_kaprodi', true))->first();
        if (!$kaprodi) {
            $this->markTestSkipped('No kaprodi found.');
        }

        $this->actingAs($kaprodi)->get('/kaprodi')->assertStatus(200);
    }

    public function test_ta_and_baak_can_view_ujian_and_arsip(): void
    {
        $ta = User::where('role', 'ta')->first();
        if ($ta) {
            $this->actingAs($ta)->get('/dashboard')->assertStatus(200);
            $this->actingAs($ta)->get('/ujian')->assertStatus(200);
            $this->actingAs($ta)->get('/arsip')->assertStatus(200);
        }

        $baak = User::where('role', 'baak')->first();
        if ($baak) {
            $this->actingAs($baak)->get('/dashboard')->assertStatus(200);
            $this->actingAs($baak)->get('/ujian')->assertStatus(200);
            $this->actingAs($baak)->get('/arsip')->assertStatus(200);
        }
    }
}
