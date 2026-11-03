@extends('layouts.notion')

@section('title', 'Panel Eksekutif Kaprodi')
@section('breadcrumb', 'Eksekutif Kaprodi')

@section('content')
<div class="space-y-4 md:space-y-5">

  <!-- Header Notion -->
  <div class="border-b border-[#E9E9E7] pb-3 mb-3 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <h1 class="text-2xl font-bold tracking-tight text-[#37352F]">Panel Eksekutif & Statistik Kelulusan TA</h1>
      </div>
      <p class="text-xs text-[#787774]">Otoritas Ketua Program Studi FITE IT Del: Pengawasan mutu kelulusan dan penetapan Koordinator TA.</p>
    </div>
  </div>

  <!-- Notion Monochrome Metric Cards -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="p-4 bg-[#F7F6F3] rounded-xl border border-[#E9E9E7]">
      <p class="text-xs text-[#787774]">Total Mhs TA Aktif</p>
      <p class="text-3xl font-extrabold text-[#37352F] mt-1">{{ $totalMhsAktif }}</p>
      <p class="text-[10px] text-[#787774] mt-0.5">3 Program Studi FITE</p>
    </div>

    <div class="p-4 bg-[#EBF5EE] rounded-xl border border-[#C4E5D1]">
      <p class="text-xs text-[#2A7E4B]">Tahap Akhir & Sidang</p>
      <p class="text-3xl font-extrabold text-[#2A7E4B] mt-1">{{ $siapSidang }}</p>
      <p class="text-[10px] text-[#2A7E4B] mt-0.5">{{ $persentaseSiapLulus }}% dari total TA aktif</p>
    </div>

    <div class="p-4 bg-[#F7F6F3] rounded-xl border border-[#E9E9E7]">
      <p class="text-xs text-[#787774]">Rasio Beban Dosen</p>
      <p class="text-3xl font-extrabold text-[#37352F] mt-1">1 : {{ $rasioDosen }}</p>
      <p class="text-[10px] text-[#787774] mt-0.5">Mhs per Dosen Pembimbing</p>
    </div>

    <div class="p-4 bg-[#2C2C2F] text-white rounded-xl border border-[#2C2C2F]">
      <p class="text-xs text-gray-300">Koordinator TA Ditugaskan</p>
      <p class="text-3xl font-extrabold text-white mt-1">{{ $totalKoordinator }}</p>
      <p class="text-[10px] text-gray-300 mt-0.5">Dari total {{ $totalDosen }} dosen FITE</p>
    </div>
  </div>

  <!-- Tabel Manajemen Penetapan Koordinator TA (Khusus Kaprodi) -->
  <div class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF] shadow-xs">
    <div class="p-4 border-b border-[#E9E9E7] flex items-center justify-between">
      <div>
        <h3 class="text-sm font-bold text-[#37352F]">Penetapan & Penugasan Koordinator Tugas Akhir</h3>
        <p class="text-xs text-[#787774]">Kaprodi berwenang menetapkan dosen sebagai Koordinator TA program studi</p>
      </div>
      <span class="text-xs text-[#787774]">Total Dosen: {{ $totalDosen }} Orang ({{ $totalKoordinator }} Koordinator)</span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-[#F7F7F5] text-[#787774] uppercase text-[10px] tracking-wider border-b border-[#E9E9E7]">
          <tr>
            <th class="py-2.5 px-4 font-semibold">Nama Dosen & NIDN</th>
            <th class="py-2.5 px-4 font-semibold">Kode & Prodi</th>
            <th class="py-2.5 px-4 font-semibold">Beban Bimbingan (P1 / P2)</th>
            <th class="py-2.5 px-4 font-semibold">Status Koordinator TA</th>
            <th class="py-2.5 px-4 text-right font-semibold">Aksi Toggle Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E9E9E7]">
          @forelse ($dosenList as $dosen)
            <tr class="hover:bg-[#F7F7F5] transition-colors">
              <td class="py-3 px-4">
                <p class="font-bold text-[#37352F]">{{ $dosen->user->name }}</p>
                <p class="text-[10px] text-[#787774]">NIDN: {{ $dosen->nidn ?? '-' }}</p>
              </td>
              <td class="py-3 px-4">
                <span class="notion-pill-gray text-[10px] block mb-0.5">{{ $dosen->kode_dosen }}</span>
                <span class="text-[11px] text-[#787774]">{{ $dosen->prodi }}</span>
              </td>
              <td class="py-3 px-4">
                {{ $dosen->tugasAkhirPembimbing1->count() }} Utama / {{ $dosen->tugasAkhirPembimbing2->count() }} Pendamping
              </td>
              <td class="py-3 px-4">
                @if ($dosen->is_koordinator_ta)
                  <span class="notion-pill-green">Koordinator Aktif</span>
                @else
                  <span class="notion-pill-gray">Dosen Pengajar</span>
                @endif
              </td>
              <td class="py-3 px-4 text-right whitespace-nowrap">
                <form action="{{ route('kaprodi.toggle-koordinator', $dosen->id) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="{{ $dosen->is_koordinator_ta ? 'notion-btn-secondary text-[11px] py-1 px-2.5' : 'notion-btn-primary text-[11px] py-1 px-2.5' }}">
                    {{ $dosen->is_koordinator_ta ? 'Nonaktifkan' : 'Tetapkan Koordinator' }}
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="py-8 text-center text-[#787774]">Belum ada data dosen terdaftar.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Grafik & Analitik Statistik Kelulusan TA -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    
    <!-- Distribusi TA per Program Studi -->
    <div class="border border-[#E9E9E7] rounded-xl p-4 bg-[#FFFFFF] shadow-xs">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h3 class="text-sm font-bold text-[#37352F]">Distribusi Tugas Akhir per Program Studi</h3>
          <p class="text-xs text-[#787774]">Proporsi mahasiswa bimbingan pada fakultas FITE</p>
        </div>
        <span class="notion-pill-gray text-[10px]">Real-time Database</span>
      </div>

      <div class="space-y-3.5 text-xs">
        @foreach ($sebaranProdi as $namaProdi => $jumlahMhs)
          @php
            $persenProdi = $totalMhsAktif > 0 ? round(($jumlahMhs / $totalMhsAktif) * 100, 1) : 0;
          @endphp
          <div>
            <div class="flex justify-between font-semibold text-[#37352F] mb-1">
              <span>{{ $namaProdi }}</span>
              <span>{{ $jumlahMhs }} Mhs ({{ $persenProdi }}%)</span>
            </div>
            <div class="w-full bg-[#E9E9E7] h-2.5 rounded-full overflow-hidden">
              <div class="bg-[#2C2C2F] h-2.5 rounded-full" style="width: {{ $persenProdi }}%"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Sebaran Progres Bab Mahasiswa TA -->
    <div class="border border-[#E9E9E7] rounded-xl p-4 bg-[#FFFFFF] shadow-xs">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h3 class="text-sm font-bold text-[#37352F]">Rekapitulasi Sebaran Status Pengerjaan TA</h3>
          <p class="text-xs text-[#787774]">Distribusi tahapan mahasiswa pada semester berjalan</p>
        </div>
        <span class="notion-pill-gray text-[10px]">Data Terkini</span>
      </div>

      <div class="grid grid-cols-2 gap-2.5 text-xs">
        <div class="p-3 bg-[#F7F6F3] rounded border border-[#E9E9E7]">
          <span class="text-[#787774] block">Proposal & Pengerjaan</span>
          <p class="text-xl font-bold text-[#37352F] mt-1">{{ ($sebaranBab['proposal'] ?? 0) + ($sebaranBab['pengerjaan'] ?? 0) }} Mhs</p>
        </div>
        <div class="p-3 bg-[#F7F6F3] rounded border border-[#E9E9E7]">
          <span class="text-[#787774] block">Seminar Proposal (Sempro)</span>
          <p class="text-xl font-bold text-[#37352F] mt-1">{{ $sebaranBab['sempro'] ?? 0 }} Mhs</p>
        </div>
        <div class="p-3 bg-[#F7F6F3] rounded border border-[#E9E9E7]">
          <span class="text-[#787774] block">Seminar TA (Progres)</span>
          <p class="text-xl font-bold text-[#37352F] mt-1">{{ $sebaranBab['seminar_ta'] ?? 0 }} Mhs</p>
        </div>
        <div class="p-3 bg-[#EBF5EE] rounded border border-[#C4E5D1]">
          <span class="text-[#2A7E4B] font-semibold block">Siap Sidang & Selesai</span>
          <p class="text-xl font-bold text-[#2A7E4B] mt-1">{{ ($sebaranBab['sidang_akhir'] ?? 0) + ($sebaranBab['selesai'] ?? 0) }} Mhs</p>
        </div>
      </div>
    </div>

  </div>

</div>
@endsection
