@extends('layouts.notion')

@section('title', 'Dashboard Utama')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="space-y-6">

  <!-- Notion Cover / Page Title Header -->
  <div class="border-b border-[#E9E9E7] pb-5">
    <div class="flex items-center gap-3 mb-2">
      <span class="text-3xl">🎓</span>
      <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-[#37352F]">
        Workspace Tugas Akhir FITE
      </h1>
    </div>
    <p class="text-xs text-[#787774]">
      Sistem Logbook, Asistensi Dua Arah & Manajemen Ujian Tugas Akhir • Institut Teknologi Del
    </p>
  </div>

  <!-- ================================================================= -->
  <!-- KONDISI 1: MAHASISWA NON-TA -->
  <!-- ================================================================= -->
  @if ($user->isMahasiswa() && (!$mahasiswaProfile || !$mahasiswaProfile->is_ta))
    <div class="notion-callout">
      <span class="text-xl">ℹ️</span>
      <div class="text-xs">
        <p class="font-bold text-[#37352F]">Status: Mahasiswa Non-TA (Tahap Persiapan Tugas Akhir)</p>
        <p class="text-[#787774] mt-1 leading-relaxed">
          Akun Anda tercatat sebagai mahasiswa aktif <strong class="text-[#37352F]">{{ $mahasiswaProfile->prodi ?? 'FITE' }} (NIM: {{ $mahasiswaProfile->nim ?? '-' }})</strong> namun belum mengambil mata kuliah Tugas Akhir pada semester ini. Anda memiliki akses penuh untuk menelusuri katalog repositori naskah skripsi alumni FITE sebagai referensi topik penelitian Anda.
        </p>
        <div class="mt-3">
          <a href="{{ route('arsip.index') }}" class="notion-btn-primary text-xs">
            <span>📚</span> Buka Pustaka Arsip Tugas Akhir
          </a>
        </div>
      </div>
    </div>

    <!-- Arsip Terpopuler Preview -->
    <div class="border border-[#E9E9E7] rounded-xl p-5 bg-[#FFFFFF]">
      <h3 class="text-sm font-bold text-[#37352F] mb-3">Rekomendasi Naskah Tugas Akhir FITE Terdahulu</h3>
      <p class="text-xs text-[#787774] mb-4">Gunakan naskah alumni berikut sebagai rujukan metodologi, format penulisan, dan instrumen pengujian.</p>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="p-3 rounded bg-[#F7F6F3] border border-[#E9E9E7] flex flex-col justify-between">
          <div>
            <span class="notion-pill-gray text-[10px] mb-1">S1 Informatika • 2025</span>
            <p class="font-semibold text-xs text-[#37352F] line-clamp-2">Sistem Pemantauan Kualitas Air Danau Toba Berbasis IoT</p>
            <p class="text-[11px] text-[#787774] mt-1">Penulis: Grace Hutapea</p>
          </div>
          <a href="{{ route('arsip.index') }}" class="text-[11px] font-semibold text-[#37352F] underline mt-3">Detail Naskah &rarr;</a>
        </div>

        <div class="p-3 rounded bg-[#F7F6F3] border border-[#E9E9E7] flex flex-col justify-between">
          <div>
            <span class="notion-pill-gray text-[10px] mb-1">S1 Sistem Informasi • 2024</span>
            <p class="font-semibold text-xs text-[#37352F] line-clamp-2">Microservices Architecture Platform E-Commerce Pertanian Toba</p>
            <p class="text-[11px] text-[#787774] mt-1">Penulis: Bona Manurung</p>
          </div>
          <a href="{{ route('arsip.index') }}" class="text-[11px] font-semibold text-[#37352F] underline mt-3">Detail Naskah &rarr;</a>
        </div>

        <div class="p-3 rounded bg-[#F7F6F3] border border-[#E9E9E7] flex flex-col justify-between">
          <div>
            <span class="notion-pill-gray text-[10px] mb-1">S1 Teknik Elektro • 2024</span>
            <p class="font-semibold text-xs text-[#37352F] line-clamp-2">Node Sensor Nirkabel LoRaWAN Deteksi Longsor Sigumpar</p>
            <p class="text-[11px] text-[#787774] mt-1">Penulis: Tulus Simanjuntak</p>
          </div>
          <a href="{{ route('arsip.index') }}" class="text-[11px] font-semibold text-[#37352F] underline mt-3">Detail Naskah &rarr;</a>
        </div>
      </div>
    </div>
  @endif

  <!-- ================================================================= -->
  <!-- KONDISI 2: MAHASISWA TA AKTIF -->
  <!-- ================================================================= -->
  @if ($user->isMahasiswa() && $mahasiswaProfile && $mahasiswaProfile->is_ta)
    
    <!-- Header Card Judul TA -->
    @if ($tugasAkhir)
      <div class="border border-[#E9E9E7] rounded-xl p-6 bg-[#FFFFFF] shadow-xs">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="notion-pill-gray">{{ $tugasAkhir->prodi }}</span>
              <span class="notion-pill-green">TA Aktif • TA {{ $tugasAkhir->tahun_ajaran }}</span>
              <span class="text-xs text-[#787774]">NIM: {{ $mahasiswaProfile->nim }}</span>
            </div>
            <h2 class="text-lg md:text-xl font-bold text-[#37352F] leading-snug">
              {{ $tugasAkhir->judul }}
            </h2>
            <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-[#787774]">
              <div>
                Pembimbing 1: <strong class="text-[#37352F]">{{ $tugasAkhir->pembimbing1->user->name ?? '-' }}</strong>
              </div>
              <span>•</span>
              <div>
                Pembimbing 2: <strong class="text-[#37352F]">{{ $tugasAkhir->pembimbing2->user->name ?? '-' }}</strong>
              </div>
              <span>•</span>
              <div>
                Total Logbook: <span class="notion-pill-green">{{ $logbooks->count() }} Sesi Terdata</span>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('bimbingan.index') }}" class="notion-btn-secondary text-xs">
              <span>📅</span> Ajukan Jadwal
            </a>
            <a href="{{ route('logbook.index') }}" class="notion-btn-primary text-xs">
              <span>+</span> Isi Logbook Baru
            </a>
          </div>
        </div>
      </div>

      <!-- Stepper Horizontal Pencapaian BAB TA ala Notion -->
      <div class="border border-[#E9E9E7] rounded-xl p-6 bg-[#FFFFFF]">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="text-sm font-bold text-[#37352F]">Stepper Capaian Bab Tugas Akhir</h3>
            <p class="text-xs text-[#787774]">Pelacakan progres tahapan naskah dari Proposal hingga Sidang Akhir</p>
          </div>
          <span class="text-xs font-semibold text-[#37352F] bg-[#F7F7F5] px-2.5 py-1 rounded border border-[#E9E9E7]">
            Status: {{ strtoupper($tugasAkhir->status) }}
          </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2.5 text-xs">
          @php
            $steps = [
              [
                'step' => 'Tahap 1',
                'name' => 'Proposal',
                'status' => in_array($tugasAkhir->status, ['sempro', 'seminar_ta', 'sidang_akhir', 'selesai']) ? 'approved' : ($tugasAkhir->status === 'proposal' ? 'in_progress' : 'empty'),
                'label' => in_array($tugasAkhir->status, ['sempro', 'seminar_ta', 'sidang_akhir', 'selesai']) ? 'Lulus Sempro ✓' : ($tugasAkhir->status === 'proposal' ? 'Pengajuan' : 'Belum Mulai'),
              ],
            ];

            foreach (['BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5'] as $idx => $bName) {
              $logsForBab = $logbooks->where('bab_progress', $bName);
              if ($bName === 'BAB 5') {
                $logsForBab = $logbooks->filter(fn($l) => in_array($l->bab_progress, ['BAB 5', 'Pengujian']));
              }

              $isApproved = $logsForBab->where('status_verifikasi', 'disetujui')->isNotEmpty();
              $isRevisi = $logsForBab->where('status_verifikasi', 'revisi')->isNotEmpty();
              $isPending = $logsForBab->where('status_verifikasi', 'pending')->isNotEmpty();

              if ($isApproved) {
                $sStatus = 'approved';
                $sLabel = 'Disetujui ✓';
              } elseif ($isRevisi) {
                $sStatus = 'revision';
                $sLabel = 'Perlu Revisi ⚠️';
              } elseif ($isPending) {
                $sStatus = 'pending';
                $sLabel = 'Pending Review ⏳';
              } else {
                $sStatus = 'empty';
                $sLabel = 'Belum Ada Entri';
              }

              $steps[] = [
                'step' => 'Tahap ' . ($idx + 2),
                'name' => $bName,
                'status' => $sStatus,
                'label' => $sLabel,
              ];
            }

            // Final: Sidang Akhir
            $sidangStatus = 'empty';
            $sidangLabel = 'Terkunci';
            if ($tugasAkhir->status === 'selesai') {
              $sidangStatus = 'approved';
              $sidangLabel = 'Lulus Sidang ✓';
            } elseif ($tugasAkhir->status === 'sidang_akhir') {
              $sidangStatus = 'pending';
              $sidangLabel = 'Terdaftar Sidang';
            } elseif ($tugasAkhir->status === 'seminar_ta') {
              $sidangStatus = 'in_progress';
              $sidangLabel = 'Tahap Seminar TA';
            }
            $steps[] = [
              'step' => 'Final',
              'name' => 'Sidang Akhir',
              'status' => $sidangStatus,
              'label' => $sidangLabel,
            ];
          @endphp

          @foreach ($steps as $st)
            @if ($st['status'] === 'approved')
              <div class="p-3 rounded border border-[#C4E5D1] bg-[#EBF5EE]">
                <span class="text-[10px] font-bold text-[#2A7E4B] block uppercase">{{ $st['step'] }}</span>
                <p class="font-bold text-[#37352F] mt-1">{{ $st['name'] }}</p>
                <p class="text-[10px] text-[#2A7E4B]">{{ $st['label'] }}</p>
              </div>
            @elseif ($st['status'] === 'revision')
              <div class="p-3 rounded border border-amber-300 bg-amber-50">
                <span class="text-[10px] font-bold text-amber-800 block uppercase">{{ $st['step'] }}</span>
                <p class="font-bold text-[#37352F] mt-1">{{ $st['name'] }}</p>
                <p class="text-[10px] text-amber-800 font-semibold">{{ $st['label'] }}</p>
              </div>
            @elseif ($st['status'] === 'pending' || $st['status'] === 'in_progress')
              <div class="p-3 rounded border border-blue-200 bg-blue-50">
                <span class="text-[10px] font-bold text-blue-700 block uppercase">{{ $st['step'] }}</span>
                <p class="font-bold text-[#37352F] mt-1">{{ $st['name'] }}</p>
                <p class="text-[10px] text-blue-700">{{ $st['label'] }}</p>
              </div>
            @else
              <div class="p-3 rounded border border-[#E9E9E7] bg-[#F7F7F5] opacity-60">
                <span class="text-[10px] font-medium text-[#787774] block uppercase">{{ $st['step'] }}</span>
                <p class="font-medium text-[#787774] mt-1">{{ $st['name'] }}</p>
                <p class="text-[10px] text-[#787774]">{{ $st['label'] }}</p>
              </div>
            @endif
          @endforeach
        </div>
      </div>

      <!-- Ringkasan Logbook Terkini -->
      <div class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF]">
        <div class="p-4 border-b border-[#E9E9E7] flex items-center justify-between">
          <div>
            <h3 class="text-sm font-bold text-[#37352F]">Catatan Logbook Progres Terakhir</h3>
            <p class="text-xs text-[#787774]">Aktivitas riset dan catatan pembimbing</p>
          </div>
          <a href="{{ route('logbook.index') }}" class="text-xs font-semibold text-[#37352F] underline hover:opacity-80">
            Buka Halaman Logbook &rarr;
          </a>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-[#F7F7F5] text-[#787774] uppercase text-[10px] tracking-wider border-b border-[#E9E9E7]">
              <tr>
                <th class="py-2.5 px-4 font-semibold">Tanggal / Bab</th>
                <th class="py-2.5 px-4 font-semibold">Aktivitas Pengerjaan</th>
                <th class="py-2.5 px-4 font-semibold">Kendala Teknis</th>
                <th class="py-2.5 px-4 font-semibold">Catatan Evaluasi Dosen</th>
                <th class="py-2.5 px-4 font-semibold">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#E9E9E7]">
              @forelse ($logbooks->take(3) as $log)
                <tr class="hover:bg-[#F7F7F5] transition-colors">
                  <td class="py-3 px-4 whitespace-nowrap">
                    <span class="font-bold text-[#37352F] block">{{ $log->tanggal->format('d M Y') }}</span>
                    <span class="notion-pill-gray text-[10px] mt-0.5">{{ $log->bab_progress }}</span>
                  </td>
                  <td class="py-3 px-4 max-w-xs">
                    <p class="font-medium text-[#37352F] line-clamp-2">{{ $log->deskripsi_kegiatan }}</p>
                  </td>
                  <td class="py-3 px-4 max-w-xs text-[#787774]">
                    {{ $log->kendala ?? 'Tidak ada kendala' }}
                  </td>
                  <td class="py-3 px-4 max-w-xs italic text-[#37352F]">
                    {{ $log->catatan_dosen ?? 'Belum ada catatan' }}
                  </td>
                  <td class="py-3 px-4 whitespace-nowrap">
                    @if ($log->status_verifikasi === 'disetujui')
                      <span class="notion-pill-green">Disetujui</span>
                    @elseif ($log->status_verifikasi === 'revisi')
                      <span class="notion-pill-red">Perlu Revisi</span>
                    @else
                      <span class="notion-pill-orange">Pending</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="py-6 text-center text-[#787774]">
                    Belum ada catatan logbook yang terdata. Klik "+ Isi Logbook Baru" untuk memulai asistensi.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    @else
      <div class="notion-callout">
        <span class="text-xl">⚠️</span>
        <div class="text-xs">
          <p class="font-bold text-[#37352F]">Tugas Akhir Belum Terdaftar</p>
          <p class="text-[#787774] mt-1 leading-relaxed">
            Akun Anda berstatus Mahasiswa Tugas Akhir aktif, namun data judul dan penunjukan dosen pembimbing belum ditetapkan oleh Koordinator TA. Silakan hubungi Koordinator TA program studi Anda.
          </p>
        </div>
      </div>
    @endif

  @endif

  <!-- ================================================================= -->
  <!-- KONDISI 3: DOSEN PEMBIMBING & KAPRODI -->
  <!-- ================================================================= -->
  @if ($user->isDosen() || $user->isKaprodi())
    
    <!-- Quick Stats Cards ala Notion -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
      <div class="p-4 bg-[#F7F6F3] rounded-lg border border-[#E9E9E7]">
        <p class="text-xs text-[#787774]">Mahasiswa Bimbingan</p>
        <p class="text-2xl font-bold text-[#37352F] mt-1">{{ $mahasiswaBimbingan->count() }}</p>
        <p class="text-[10px] text-[#787774] mt-0.5">FITE IT Del</p>
      </div>

      <div class="p-4 bg-[#FDF5E6] rounded-lg border border-[#F3E0A2]">
        <p class="text-xs text-[#B86200]">Menunggu Review</p>
        <p class="text-2xl font-bold text-[#B86200] mt-1">{{ $pendingReviewsCount }}</p>
        <p class="text-[10px] text-[#B86200] mt-0.5">Entri logbook baru</p>
      </div>

      <div class="p-4 bg-[#EBF5EE] rounded-lg border border-[#C4E5D1]">
        <p class="text-xs text-[#2A7E4B]">Pertemuan Minggu Ini</p>
        <p class="text-2xl font-bold text-[#2A7E4B] mt-1">{{ $upcomingMeetingsCount }}</p>
        <p class="text-[10px] text-[#2A7E4B] mt-0.5">Asistensi terjadwal</p>
      </div>

      <div class="p-4 bg-[#F7F6F3] rounded-lg border border-[#E9E9E7]">
        <p class="text-xs text-[#787774]">Total TA FITE Aktif</p>
        <p class="text-2xl font-bold text-[#37352F] mt-1">{{ $totalTaAktif }}</p>
        <p class="text-[10px] text-[#787774] mt-0.5">3 Program Studi</p>
      </div>
    </div>

    @if ($user->isKoordinator())
      <!-- Panel Ringkas Koordinator TA -->
      <div class="border border-[#E9E9E7] rounded-xl p-5 bg-[#FAFAF9] shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-[#E9E9E7] gap-2 mb-4">
          <div class="flex items-center gap-2">
            <span class="text-lg">⚙️</span>
            <div>
              <h3 class="text-sm font-bold text-[#37352F]">Ringkasan Operasional Koordinator TA</h3>
              <p class="text-xs text-[#787774]">Penetapan pembimbing dan penjadwalan ujian program studi FITE</p>
            </div>
          </div>
          <a href="{{ route('koordinator.index') }}" class="notion-btn-secondary text-xs">
            Buka Panel Koordinator &rarr;
          </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
          <div class="p-4 rounded-lg border border-[#E9E9E7] bg-[#FFFFFF] flex items-center justify-between">
            <div>
              <p class="text-[#787774] text-[11px] font-medium uppercase tracking-wider">Menunggu Penunjukan Pembimbing</p>
              <p class="text-2xl font-bold text-[#37352F] mt-1">{{ $menungguAssignCount }} <span class="text-xs font-normal text-[#787774]">Mahasiswa</span></p>
            </div>
            <a href="{{ route('koordinator.index') }}" class="notion-btn-primary text-xs py-1.5 px-3">
              Plot Pembimbing
            </a>
          </div>

          <div class="p-4 rounded-lg border border-[#E9E9E7] bg-[#FFFFFF] flex items-center justify-between">
            <div>
              <p class="text-[#787774] text-[11px] font-medium uppercase tracking-wider">Ujian Siap Dijadwalkan (Berkas Lengkap)</p>
              <p class="text-2xl font-bold text-[#2A7E4B] mt-1">{{ $unassignedExamsCount }} <span class="text-xs font-normal text-[#787774]">Pengajuan</span></p>
            </div>
            <a href="{{ route('ujian.index') }}" class="notion-btn-primary text-xs py-1.5 px-3">
              Tetapkan Jadwal &rarr;
            </a>
          </div>
        </div>
      </div>
    @endif

    @if ($user->isKaprodi())
      <!-- Panel Ringkas Kaprodi -->
      <div class="border border-[#E9E9E7] rounded-xl p-5 bg-[#FAFAF9] shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-[#E9E9E7] gap-2 mb-4">
          <div class="flex items-center gap-2">
            <span class="text-lg">📊</span>
            <div>
              <h3 class="text-sm font-bold text-[#37352F]">Ringkasan Mutu & Statistik Kaprodi</h3>
              <p class="text-xs text-[#787774]">Pengawasan mutu kelulusan tugas akhir dan beban bimbingan dosen FITE</p>
            </div>
          </div>
          <a href="{{ route('kaprodi.index') }}" class="notion-btn-secondary text-xs">
            Buka Panel Kaprodi &rarr;
          </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
          <div class="p-4 rounded-lg border border-[#E9E9E7] bg-[#FFFFFF] flex items-center justify-between">
            <div>
              <p class="text-[#787774] text-[11px] font-medium uppercase tracking-wider">Total Tugas Akhir Berjalan</p>
              <p class="text-2xl font-bold text-[#37352F] mt-1">{{ $totalTaAktif }} <span class="text-xs font-normal text-[#787774]">Mahasiswa</span></p>
            </div>
            <a href="{{ route('kaprodi.index') }}" class="notion-btn-primary text-xs py-1.5 px-3">
              Pantau Mutu TA
            </a>
          </div>

          <div class="p-4 rounded-lg border border-[#E9E9E7] bg-[#FFFFFF] flex items-center justify-between">
            <div>
              <p class="text-[#787774] text-[11px] font-medium uppercase tracking-wider">Mahasiswa Siap Sidang</p>
              <p class="text-2xl font-bold text-[#2A7E4B] mt-1">{{ $totalMhsSiapSidang }} <span class="text-xs font-normal text-[#787774]">Mahasiswa</span></p>
            </div>
            <a href="{{ route('kaprodi.index') }}" class="notion-btn-primary text-xs py-1.5 px-3">
              Lihat Statistik &rarr;
            </a>
          </div>
        </div>
      </div>
    @endif

    <!-- Tabel Mahasiswa Bimbingan -->
    <div class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF]">
      <div class="p-4 border-b border-[#E9E9E7] flex items-center justify-between">
        <div>
          <h3 class="text-sm font-bold text-[#37352F]">Daftar Mahasiswa Asistensi Bimbingan Anda</h3>
          <p class="text-xs text-[#787774]">Kelola review draf naskah, revisi coretan, dan persetujuan bimbingan</p>
        </div>
        <a href="{{ route('logbook.index') }}" class="notion-btn-secondary text-xs">
          Tinjau Semua Logbook &rarr;
        </a>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-[#F7F7F5] text-[#787774] uppercase text-[10px] tracking-wider border-b border-[#E9E9E7]">
            <tr>
              <th class="py-2.5 px-4 font-semibold">Mahasiswa & NIM</th>
              <th class="py-2.5 px-4 font-semibold">Judul Tugas Akhir</th>
              <th class="py-2.5 px-4 font-semibold">Tahap Bab</th>
              <th class="py-2.5 px-4 font-semibold">Logbook Terakhir</th>
              <th class="py-2.5 px-4 text-right font-semibold">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#E9E9E7]">
            @forelse ($mahasiswaBimbingan as $ta)
              <tr class="hover:bg-[#F7F7F5] transition-colors">
                <td class="py-3 px-4">
                  <p class="font-bold text-[#37352F]">{{ $ta->mahasiswa->user->name }}</p>
                  <p class="text-[11px] text-[#787774]">{{ $ta->mahasiswa->nim }} • {{ $ta->prodi }}</p>
                </td>
                <td class="py-3 px-4 max-w-xs">
                  <p class="font-medium text-[#37352F] line-clamp-2">{{ $ta->judul }}</p>
                </td>
                <td class="py-3 px-4">
                  <span class="notion-pill-gray uppercase">{{ $ta->status }}</span>
                </td>
                <td class="py-3 px-4 text-[#787774]">
                  @if ($ta->logbooks->isNotEmpty())
                    <p class="font-medium text-[#37352F]">{{ $ta->logbooks->first()->tanggal->format('d M Y') }}</p>
                    <span class="text-[10px]">{{ $ta->logbooks->first()->bab_progress }}</span>
                  @else
                    Belum ada entri
                  @endif
                </td>
                <td class="py-3 px-4 text-right">
                  <a href="{{ route('logbook.index') }}" class="notion-btn-primary text-xs py-1 px-2.5">
                    Review Dokumen
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-6 text-center text-[#787774]">
                  Belum ada mahasiswa yang dialokasikan ke bimbingan Anda.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  @endif

  <!-- ================================================================= -->
  <!-- KONDISI 4: TEACHING ASSISTANT & BAAK -->
  <!-- ================================================================= -->
  @if ($user->isTA() || $user->isBaak())
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="p-5 rounded-xl border border-[#E9E9E7] bg-[#F7F6F3]">
        <h4 class="text-xs font-bold text-[#787774] uppercase">Antrean Verifikasi Berkas (TA)</h4>
        <p class="text-3xl font-extrabold text-[#37352F] mt-2">{{ $pendingUjianCount }}</p>
        <p class="text-xs text-[#787774] mt-1">Berkas ujian mahasiswa menunggu validasi fisik/soft-copy</p>
        <div class="mt-4">
          <a href="{{ route('ujian.index') }}" class="notion-btn-primary text-xs">Periksa Berkas &rarr;</a>
        </div>
      </div>

      <div class="p-5 rounded-xl border border-[#E9E9E7] bg-[#F7F6F3]">
        <h4 class="text-xs font-bold text-[#787774] uppercase">Alokasi Ruangan Ujian (BAAK)</h4>
        <p class="text-3xl font-extrabold text-[#37352F] mt-2">{{ $unscheduledBaakCount }}</p>
        <p class="text-xs text-[#787774] mt-1">Jadwal tanggal terbit menanti alokasi ruang GD & link Zoom</p>
        <div class="mt-4">
          <a href="{{ route('ujian.index') }}" class="notion-btn-primary text-xs">Kelola Fasilitas &rarr;</a>
        </div>
      </div>

      <div class="p-5 rounded-xl border border-[#E9E9E7] bg-[#F7F6F3]">
        <h4 class="text-xs font-bold text-[#787774] uppercase">Total Arsip Digital Alumni</h4>
        <p class="text-3xl font-extrabold text-[#37352F] mt-2">{{ $totalArsipCount }}</p>
        <p class="text-xs text-[#787774] mt-1">Karya naskah skripsi terunggah di repositori FITE</p>
        <div class="mt-4">
          <a href="{{ route('arsip.index') }}" class="notion-btn-secondary text-xs">Pustaka Naskah &rarr;</a>
        </div>
      </div>
    </div>
  @endif

</div>
@endsection
