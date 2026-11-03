{{-- Partials: Interactive Modals & Drawers --}}

<!-- MODAL 1: Isi Logbook Aktivitas Baru (Mahasiswa TA) -->
<div x-show="$store.app.modals.logbook" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
  <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs transition-opacity" @click="$store.app.closeModal('logbook')"></div>
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="relative w-full max-w-xl bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl shadow-xl overflow-hidden p-6 z-10" @click.away="$store.app.closeModal('logbook')">
      
      <div class="flex items-center justify-between pb-3 border-b border-[#E0E0E0] mb-4">
        <div>
          <h3 class="text-base font-bold text-[#0A0A0B]">Isi Logbook Aktivitas & Progres TA</h3>
          <p class="text-xs text-[#5D5D5F]">Catatan kegiatan pengerjaan tugas akhir & asistensi dengan dosen pembimbing</p>
        </div>
        <button @click="$store.app.closeModal('logbook')" class="text-[#5D5D5F] hover:text-[#0A0A0B]">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <form @submit.prevent="$store.app.closeModal('logbook'); $store.app.showToast('Entri logbook baru berhasil disimpan dan dikirim ke Dosen Pembimbing!')" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Tanggal Kegiatan</label>
            <input type="date" value="2026-11-02" required class="w-full text-sm px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Fokus Bab Progres</label>
            <select required class="w-full text-sm px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]">
              <option value="Proposal">Proposal TA</option>
              <option value="BAB 1">BAB 1 - Pendahuluan</option>
              <option value="BAB 2">BAB 2 - Tinjauan Pustaka</option>
              <option value="BAB 3">BAB 3 - Metodologi</option>
              <option value="BAB 4" selected>BAB 4 - Implementasi & Evaluasi</option>
              <option value="BAB 5">BAB 5 - Kesimpulan</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Deskripsi Aktivitas & Progres yang Dicapai</label>
          <textarea rows="3" required placeholder="Tuliskan poin pengerjaan, bab/subbab yang disusun, atau hasil eksperimen terbaru..." class="w-full text-sm px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]"></textarea>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Kendala / Blocker yang Dihadapi (Opsional)</label>
          <input type="text" placeholder="Contoh: Kesulitan mencari dataset seimbang, error library CUDA..." class="w-full text-sm px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]">
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Unggah Dokumen Progres (PDF / DOCX)</label>
          <div class="border-2 border-dashed border-[#CCCCCC] hover:border-[#0A0A0B] rounded-lg p-5 text-center bg-[#F5F5F5] cursor-pointer transition-colors">
            <svg class="mx-auto h-7 w-7 text-[#5D5D5F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="17 8 12 3 7 8"></polyline>
              <line x1="12" y1="3" x2="12" y2="15"></line>
            </svg>
            <p class="text-xs font-medium text-[#0A0A0B] mt-1.5">Klik atau seret draf dokumen TA ke sini</p>
            <p class="text-[10px] text-[#5D5D5F]">Format: .pdf atau .docx (Maksimal 25 MB)</p>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#E0E0E0]">
          <button type="button" @click="$store.app.closeModal('logbook')" class="btn-secondary text-xs">Batal</button>
          <button type="submit" class="btn-primary text-xs">Simpan Logbook</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL 2: Perbarui Capaian Bab TA -->
<div x-show="$store.app.modals.chapterProgress" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
  <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs transition-opacity" @click="$store.app.closeModal('chapterProgress')"></div>
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="relative w-full max-w-md bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl shadow-xl overflow-hidden p-6 z-10" @click.away="$store.app.closeModal('chapterProgress')">
      
      <div class="flex items-center justify-between pb-3 border-b border-[#E0E0E0] mb-4">
        <div>
          <h3 class="text-base font-bold text-[#0A0A0B]">Perbarui Capaian Bab TA</h3>
          <p class="text-xs text-[#5D5D5F]">Ajukan pembaruan tahap bab pengerjaan tugas akhir Anda</p>
        </div>
        <button @click="$store.app.closeModal('chapterProgress')" class="text-[#5D5D5F] hover:text-[#0A0A0B]">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <form @submit.prevent="$store.app.closeModal('chapterProgress'); $store.app.showToast('Pengajuan capaian Bab berhasil diperbarui!')" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Pilih Tahap Bab Terkini</label>
          <select class="w-full text-sm px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]">
            <option>Proposal TA (Lulus Sempro)</option>
            <option>BAB 1 - Pendahuluan (Selesai)</option>
            <option>BAB 2 - Tinjauan Pustaka (Selesai)</option>
            <option>BAB 3 - Metodologi (Selesai)</option>
            <option selected>BAB 4 - Implementasi & Eksperimen (Revisi/In Progress)</option>
            <option>BAB 5 - Kesimpulan & Saran</option>
            <option>Sidang Akhir (Pendaftaran)</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Catatan Kemajuan Ringkas</label>
          <textarea rows="3" placeholder="Jelaskan ringkasan bagian bab yang telah tuntas disusun..." class="w-full text-sm px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]"></textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#E0E0E0]">
          <button type="button" @click="$store.app.closeModal('chapterProgress')" class="btn-secondary text-xs">Batal</button>
          <button type="submit" class="btn-primary text-xs">Konfirmasi Pembaruan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL 3: Ajukan Jadwal Bimbingan Dua Arah (Mahasiswa / Dosen) -->
<div x-show="$store.app.modals.appointment" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
  <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs transition-opacity" @click="$store.app.closeModal('appointment')"></div>
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl shadow-xl overflow-hidden p-6 z-10" @click.away="$store.app.closeModal('appointment')">
      
      <div class="flex items-center justify-between pb-3 border-b border-[#E0E0E0] mb-4">
        <div>
          <h3 class="text-base font-bold text-[#0A0A0B]">Ajukan Janji Bimbingan Dua Arah</h3>
          <p class="text-xs text-[#5D5D5F]">Bisa diajukan oleh Mahasiswa kepada Dosen atau diundang langsung oleh Dosen</p>
        </div>
        <button @click="$store.app.closeModal('appointment')" class="text-[#5D5D5F] hover:text-[#0A0A0B]">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <form @submit.prevent="$store.app.closeModal('appointment'); $store.app.showToast('Undangan janji bimbingan berhasil diajukan dan terkirim via in-app alert!')" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Tanggal</label>
            <input type="date" value="2026-11-06" required class="w-full text-sm px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Waktu / Jam</label>
            <input type="time" value="10:00" required class="w-full text-sm px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]">
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Topik Bahasan Asistensi</label>
          <input type="text" required placeholder="Contoh: Konsultasi Evaluasi Confusion Matrix Bab 4..." class="w-full text-sm px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]">
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Metode Pertemuan</label>
          <select class="w-full text-sm px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]">
            <option>Tatap Muka (Offline di Kampus IT Del)</option>
            <option>Daring (Online via Zoom / Google Meet)</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Lokasi Fisik / Tautan Link Pertemuan</label>
          <input type="text" value="Ruang Dosen GD 512 / https://zoom.us/j/itdel-fite" class="w-full text-sm px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]">
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#E0E0E0]">
          <button type="button" @click="$store.app.closeModal('appointment')" class="btn-secondary text-xs">Batal</button>
          <button type="submit" class="btn-primary text-xs">Kirimkan Pengajuan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- DRAWER SPLIT-VIEW: Dosen Review Progres & Coretan Mahasiswa -->
<div x-show="$store.app.modals.dosenReviewDrawer" x-cloak class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
  <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs transition-opacity" @click="$store.app.closeModal('dosenReviewDrawer')"></div>
  
  <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
    <div class="w-screen max-w-4xl bg-[#FFFFFF] border-l border-[#E0E0E0] shadow-2xl flex flex-col">
      
      <!-- Drawer Topbar -->
      <div class="p-5 border-b border-[#E0E0E0] flex items-center justify-between bg-[#F5F5F5]">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <span class="pill-revision text-xs">Perlu Evaluasi Dosen</span>
            <span class="text-xs text-[#5D5D5F]">Logbook Entri #8</span>
          </div>
          <h2 class="text-lg font-bold text-[#0A0A0B]">Review Progres: Samuel Siahaan (11S21004)</h2>
        </div>
        <button @click="$store.app.closeModal('dosenReviewDrawer')" class="btn-icon">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <!-- Split View Body (Kiri Dokumen & Catatan Mhs, Kanan Form Feedback Dosen) -->
      <div class="flex-1 grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-[#E0E0E0] overflow-y-auto">
        
        <!-- Kolom Kiri: Detail Logbook & Preview Dokumen Draf Mahasiswa -->
        <div class="p-6 space-y-4 bg-[#FFFFFF]">
          <div>
            <h4 class="text-xs font-semibold uppercase tracking-wider text-[#5D5D5F]">Aktivitas & Catatan Mahasiswa</h4>
            <div class="mt-2 p-3.5 rounded-lg bg-[#F5F5F5] border border-[#E0E0E0] text-xs text-[#2C2C2F] space-y-2">
              <p class="font-semibold text-[#0A0A0B]">Fokus: BAB 4 (Pengujian Model Deep Learning)</p>
              <p class="text-[#3D3D3F] leading-relaxed">"Telah melakukan pengujian arsitektur 1D-CNN dan Bi-LSTM menggunakan dataset lalu lintas jaringan kampus IT Del. Mengamati akurasi 94.2% namun precision menurun pada attack DoS UDP flood."</p>
            </div>
          </div>

          <div>
            <h4 class="text-xs font-semibold uppercase tracking-wider text-[#5D5D5F]">Kendala yang Dihadapi Mahasiswa</h4>
            <div class="mt-1 p-3 rounded-lg bg-amber-50 border border-amber-200 text-xs text-amber-900">
              Overfitting pada epoch ke-35 akibat ketidakseimbangan kelas serangan DoS. Membutuhkan arahan pembimbing untuk teknik oversampling.
            </div>
          </div>

          <div>
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-xs font-semibold uppercase tracking-wider text-[#5D5D5F]">Draf Dokumen Mahasiswa Terunggah</h4>
              <span class="text-[11px] text-[#5D5D5F]">PDF • 3.4 MB</span>
            </div>
            
            <!-- Dokumen Preview Card -->
            <div class="border border-[#E0E0E0] rounded-lg p-4 bg-[#F5F5F5] hover:border-[#0A0A0B] transition-colors">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded bg-red-100 text-red-700 flex items-center justify-center font-bold text-xs">
                    PDF
                  </div>
                  <div>
                    <p class="font-semibold text-xs text-[#0A0A0B]">Draf_Bab4_Model_Samuel_v2.pdf</p>
                    <p class="text-[10px] text-[#5D5D5F]">Diunggah 28 Okt 2026 • 32 Halaman</p>
                  </div>
                </div>
                <button @click="$store.app.showToast('Membuka pratinjau dokumen PDF di tab baru...')" class="btn-secondary text-xs py-1 px-2.5">
                  Buka Dokumen
                </button>
              </div>

              <div class="mt-3 p-3 bg-[#FFFFFF] rounded border border-[#E0E0E0] text-[11px] text-[#4D4D4F] font-mono leading-relaxed">
                [Halaman 35 - Subbab 4.2.1 Parameter Arsitektur]:<br>
                Model Conv1D menggunakan filter ukuran 64 dengan kernel 3x1. Evaluasi dilakukan dengan epoch=50 dan learning rate=0.001 Adam Optimizer...
              </div>
            </div>
          </div>
        </div>

        <!-- Kolom Kanan: Form Ulasan Feedback & Upload Coretan Dosen -->
        <div class="p-6 space-y-4 bg-[#FFFFFF]">
          <div>
            <h4 class="text-xs font-semibold uppercase tracking-wider text-[#5D5D5F] mb-1">Berikan Komentar / Catatan Revisi Dosen</h4>
            <textarea rows="4" class="w-full text-xs p-3 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]" 
              placeholder="Tuliskan catatan perbaikan naskah atau arahan eksperimen untuk mahasiswa...">Perbaiki metodologi sampling pada subbab 4.2. Gunakan teknik SMOTE untuk menangani kelas DoS yang imbalanced. Coretan garis merah sudah saya sematkan di file revisi terlampir.</textarea>
          </div>

          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#5D5D5F] mb-1">Unggah File Revisi (Coretan / Markup Dosen)</label>
            <div class="border-2 border-dashed border-[#CCCCCC] hover:border-[#0A0A0B] rounded-lg p-4 text-center bg-[#F5F5F5] cursor-pointer transition-colors">
              <svg class="mx-auto h-6 w-6 text-[#5D5D5F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="17 8 12 3 7 8"></polyline>
                <line x1="12" y1="3" x2="12" y2="15"></line>
              </svg>
              <p class="text-xs font-medium text-[#0A0A0B] mt-1">Unggah Coretan_Revisi_Bab4.pdf</p>
              <p class="text-[10px] text-[#5D5D5F]">Format PDF beranotasi coretan dosen</p>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#5D5D5F] mb-1.5">Ubah Status Verifikasi Logbook</label>
            <div class="grid grid-cols-3 gap-2">
              <label class="p-2 border border-[#C4E5D1] bg-[#EBF5EE] rounded cursor-pointer text-center flex flex-col items-center">
                <input type="radio" name="logbook_status" value="approved" class="text-emerald-600">
                <span class="text-xs font-semibold text-[#1F7A4C] mt-1">Disetujui</span>
              </label>
              <label class="p-2 border-2 border-amber-600 bg-amber-50 rounded cursor-pointer text-center flex flex-col items-center">
                <input type="radio" name="logbook_status" value="revision" checked class="text-amber-600">
                <span class="text-xs font-semibold text-amber-900 mt-1">Perlu Revisi</span>
              </label>
              <label class="p-2 border border-[#E0E0E0] bg-[#F5F5F5] rounded cursor-pointer text-center flex flex-col items-center">
                <input type="radio" name="logbook_status" value="pending" class="text-gray-600">
                <span class="text-xs font-semibold text-[#4D4D4F] mt-1">Pending</span>
              </label>
            </div>
          </div>

          <div class="pt-4 border-t border-[#E0E0E0]">
            <button @click="$store.app.closeModal('dosenReviewDrawer'); $store.app.showToast('Feedback revisi & coretan dosen berhasil dikirimkan ke mahasiswa!')" class="w-full btn-primary text-xs py-2.5 justify-center">
              Simpan & Kirimkan Feedback ke Mahasiswa
            </button>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- MODAL 5: Verifikasi Dokumen Persyaratan Ujian (Teaching Assistant) -->
<div x-show="$store.app.modals.verifyArtifact" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
  <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs transition-opacity" @click="$store.app.closeModal('verifyArtifact')"></div>
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl shadow-xl overflow-hidden p-6 z-10" @click.away="$store.app.closeModal('verifyArtifact')">
      
      <div class="flex items-center justify-between pb-3 border-b border-[#E0E0E0] mb-4">
        <div>
          <h3 class="text-base font-bold text-[#0A0A0B]">Verifikasi Kelengkapan Dokumen Fisik / Artefak</h3>
          <p class="text-xs text-[#5D5D5F]">Verifikator: Grace Panjaitan, S.Kom. (Teaching Assistant FITE)</p>
        </div>
        <button @click="$store.app.closeModal('verifyArtifact')" class="text-[#5D5D5F] hover:text-[#0A0A0B]">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <form @submit.prevent="$store.app.closeModal('verifyArtifact'); $store.app.showToast('Hasil verifikasi berkas disimpan! Diteruskan ke Koordinator TA.')" class="space-y-4">
        <div class="space-y-2 text-xs">
          <label class="flex items-center gap-2 p-2 bg-[#F5F5F5] rounded border border-[#E0E0E0] cursor-pointer">
            <input type="checkbox" checked class="text-[#0A0A0B] rounded">
            <span>Draf Lengkap Naskah Tugas Akhir (Sesuai Format FITE IT Del)</span>
          </label>
          <label class="flex items-center gap-2 p-2 bg-[#F5F5F5] rounded border border-[#E0E0E0] cursor-pointer">
            <input type="checkbox" checked class="text-[#0A0A0B] rounded">
            <span>Rekapitulasi Logbook Asistensi Minimal 8 Pertemuan Terverifikasi</span>
          </label>
          <label class="flex items-center gap-2 p-2 bg-[#F5F5F5] rounded border border-[#E0E0E0] cursor-pointer">
            <input type="checkbox" checked class="text-[#0A0A0B] rounded">
            <span>Lembar Persetujuan Pendaftaran Seminar / Sidang Ditandatangani Pembimbing</span>
          </label>
          <label class="flex items-center gap-2 p-2 bg-[#F5F5F5] rounded border border-[#E0E0E0] cursor-pointer">
            <input type="checkbox" checked class="text-[#0A0A0B] rounded">
            <span>Transkrip Nilai Sementara Disahkan BAAK IT Del</span>
          </label>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Keputusan Verifikasi TA</label>
          <div class="grid grid-cols-2 gap-3">
            <label class="p-2.5 rounded border border-[#C4E5D1] bg-[#EBF5EE] text-center cursor-pointer flex items-center justify-center gap-2">
              <input type="radio" name="verify_decision" checked class="text-emerald-700">
              <span class="text-xs font-bold text-[#1F7A4C]">Lengkap & Valid</span>
            </label>
            <label class="p-2.5 rounded border border-red-300 bg-red-50 text-center cursor-pointer flex items-center justify-center gap-2">
              <input type="radio" name="verify_decision" class="text-red-700">
              <span class="text-xs font-bold text-red-800">Kurang Lengkap</span>
            </label>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Catatan Tambahan Verifikator</label>
          <input type="text" placeholder="Semua berkas fisik dan digital telah diperiksa lengkap..." class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#E0E0E0]">
          <button type="button" @click="$store.app.closeModal('verifyArtifact')" class="btn-secondary text-xs">Batal</button>
          <button type="submit" class="btn-primary text-xs">Simpan Verifikasi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL 6: Atur Tanggal Ujian & Penguji 1 & 2 (Koordinator TA) -->
<div x-show="$store.app.modals.assignExaminer" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
  <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs transition-opacity" @click="$store.app.closeModal('assignExaminer')"></div>
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl shadow-xl overflow-hidden p-6 z-10" @click.away="$store.app.closeModal('assignExaminer')">
      
      <div class="flex items-center justify-between pb-3 border-b border-[#E0E0E0] mb-4">
        <div>
          <h3 class="text-base font-bold text-[#0A0A0B]">Penetapan Tanggal & Dosen Penguji Ujian</h3>
          <p class="text-xs text-[#5D5D5F]">Otoritas Koordinator TA FITE: Marlina Valentina, M.Sc.</p>
        </div>
        <button @click="$store.app.closeModal('assignExaminer')" class="text-[#5D5D5F] hover:text-[#0A0A0B]">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <form @submit.prevent="$store.app.closeModal('assignExaminer'); $store.app.showToast('Tanggal ujian & tim penguji berhasil ditetapkan! Disposisi dikirim ke BAAK.')" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Tanggal Pelaksanaan Ujian</label>
          <input type="date" value="2026-11-10" required class="w-full text-sm px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Dosen Penguji 1</label>
            <select class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
              <option>Dr. Arnaldo Marulitua Sinaga</option>
              <option>Dr. Johannes Harungguan</option>
              <option>Samuel P. Pandiangan, M.Kom.</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Dosen Penguji 2</label>
            <select class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
              <option>Samuel P. Pandiangan, M.Kom.</option>
              <option>Dr. Johannes Harungguan</option>
              <option>Dr. Arnaldo Marulitua Sinaga</option>
            </select>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#E0E0E0]">
          <button type="button" @click="$store.app.closeModal('assignExaminer')" class="btn-secondary text-xs">Batal</button>
          <button type="submit" class="btn-primary text-xs">Simpan & Teruskan ke BAAK</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL 7: Pengaturan Jadwal Ruangan, Jam & Zoom (BAAK) -->
<div x-show="$store.app.modals.baakSchedule" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
  <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs transition-opacity" @click="$store.app.closeModal('baakSchedule')"></div>
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl shadow-xl overflow-hidden p-6 z-10" @click.away="$store.app.closeModal('baakSchedule')">
      
      <div class="flex items-center justify-between pb-3 border-b border-[#E0E0E0] mb-4">
        <div>
          <h3 class="text-base font-bold text-[#0A0A0B]">Alokasi Ruangan, Jam & Link Zoom (BAAK)</h3>
          <p class="text-xs text-[#5D5D5F]">Biro Administrasi Akademik - Institut Teknologi Del</p>
        </div>
        <button @click="$store.app.closeModal('baakSchedule')" class="text-[#5D5D5F] hover:text-[#0A0A0B]">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <form @submit.prevent="$store.app.closeModal('baakSchedule'); $store.app.showToast('Jadwal ruangan, jam dan Zoom berhasil diterbitkan oleh BAAK!')" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Ruangan Ujian FITE</label>
          <select class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
            <option>Ruang Sidang FITE GD 514 (Lantai 5)</option>
            <option>Lab Komputer GD 512</option>
            <option>Ruang Rapat FITE GD 510</option>
            <option>Ruang Dosen Gedung 9</option>
          </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Jam Mulai</label>
            <input type="time" value="09:00" required class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Jam Selesai</label>
            <input type="time" value="11:00" required class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Penyematan Link Zoom (Hybrid/Daring)</label>
          <input type="url" value="https://zoom.us/j/itdel-sidang-room1" class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#E0E0E0]">
          <button type="button" @click="$store.app.closeModal('baakSchedule')" class="btn-secondary text-xs">Batal</button>
          <button type="submit" class="btn-primary text-xs">Terbitkan Jadwal Final</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL 8: Unggah Arsip Tugas Akhir Alumni (Teaching Assistant) -->
<div x-show="$store.app.modals.uploadArchive" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
  <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs transition-opacity" @click="$store.app.closeModal('uploadArchive')"></div>
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl shadow-xl overflow-hidden p-6 z-10" @click.away="$store.app.closeModal('uploadArchive')">
      
      <div class="flex items-center justify-between pb-3 border-b border-[#E0E0E0] mb-4">
        <div>
          <h3 class="text-base font-bold text-[#0A0A0B]">Unggah Dokumen Soft-Copy Arsip Tugas Akhir</h3>
          <p class="text-xs text-[#5D5D5F]">Teaching Assistant mengarsipkan karya naskah final alumni FITE IT Del</p>
        </div>
        <button @click="$store.app.closeModal('uploadArchive')" class="text-[#5D5D5F] hover:text-[#0A0A0B]">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <form @submit.prevent="$store.app.closeModal('uploadArchive'); $store.app.showToast('Naskah Tugas Akhir alumni berhasil diarsipkan ke Pustaka Digital!')" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Judul Lengkap Tugas Akhir</label>
          <input type="text" required placeholder="Judul naskah final..." class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Nama Penulis & NIM</label>
            <input type="text" required placeholder="Contoh: Grace Hutapea (11S20011)" class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Program Studi</label>
            <select class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
              <option>S1 Informatika</option>
              <option>S1 Sistem Informasi</option>
              <option>S1 Teknik Elektro</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Tahun Lulus</label>
            <input type="number" value="2025" class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Kata Kunci (Koma)</label>
            <input type="text" placeholder="IoT, Deep Learning, Cloud" class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Unggah Soft-Copy Naskah Lengkap (PDF Maks 50MB)</label>
          <div class="border-2 border-dashed border-[#CCCCCC] hover:border-[#0A0A0B] rounded-lg p-4 text-center bg-[#F5F5F5] cursor-pointer">
            <p class="text-xs font-medium text-[#0A0A0B]">Pilih File Naskah_Lengkap_Final_Alumni.pdf</p>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#E0E0E0]">
          <button type="button" @click="$store.app.closeModal('uploadArchive')" class="btn-secondary text-xs">Batal</button>
          <button type="submit" class="btn-primary text-xs">Simpan ke Pustaka</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL 9: Tetapkan Dosen Pembimbing (Koordinator TA) -->
<div x-show="$store.app.modals.assignSupervisor" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
  <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs transition-opacity" @click="$store.app.closeModal('assignSupervisor')"></div>
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="relative w-full max-w-md bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl shadow-xl overflow-hidden p-6 z-10" @click.away="$store.app.closeModal('assignSupervisor')">
      
      <div class="flex items-center justify-between pb-3 border-b border-[#E0E0E0] mb-4">
        <div>
          <h3 class="text-base font-bold text-[#0A0A0B]">Tetapkan Dosen Pembimbing</h3>
          <p class="text-xs text-[#5D5D5F]">Alokasikan Dosen Pembimbing 1 & 2 untuk mahasiswa</p>
        </div>
        <button @click="$store.app.closeModal('assignSupervisor')" class="text-[#5D5D5F] hover:text-[#0A0A0B]">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <form @submit.prevent="$store.app.closeModal('assignSupervisor'); $store.app.showToast('Penetapan Dosen Pembimbing berhasil disimpan!')" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Mahasiswa TA</label>
          <select class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
            <option>Samuel Siahaan (11S21004) - S1 Informatika</option>
            <option>Yohana Sinaga (11S21033) - S1 Informatika</option>
            <option>Rian Marpaung (14S21002) - S1 Teknik Elektro</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Dosen Pembimbing 1 (Utama)</label>
          <select class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
            <option>Dr. Johannes Harungguan, S.T., M.T.</option>
            <option>Dr. Arnaldo Marulitua Sinaga, S.T., M.InfoTech.</option>
            <option>Marlina Valentina, S.Kom., M.Sc.</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Dosen Pembimbing 2 (Pendamping)</label>
          <select class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
            <option>Marlina Valentina, S.Kom., M.Sc.</option>
            <option>Samuel P. Pandiangan, M.Kom.</option>
            <option>Dr. Johannes Harungguan, S.T., M.T.</option>
          </select>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#E0E0E0]">
          <button type="button" @click="$store.app.closeModal('assignSupervisor')" class="btn-secondary text-xs">Batal</button>
          <button type="submit" class="btn-primary text-xs">Tetapkan Pembimbing</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL 10: Angkat Koordinator TA Baru (Kaprodi) -->
<div x-show="$store.app.modals.assignCoordinator" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
  <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs transition-opacity" @click="$store.app.closeModal('assignCoordinator')"></div>
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="relative w-full max-w-md bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl shadow-xl overflow-hidden p-6 z-10" @click.away="$store.app.closeModal('assignCoordinator')">
      
      <div class="flex items-center justify-between pb-3 border-b border-[#E0E0E0] mb-4">
        <div>
          <h3 class="text-base font-bold text-[#0A0A0B]">Angkat Koordinator TA Baru</h3>
          <p class="text-xs text-[#5D5D5F]">Otoritas Khusus Ketua Program Studi (Kaprodi)</p>
        </div>
        <button @click="$store.app.closeModal('assignCoordinator')" class="text-[#5D5D5F] hover:text-[#0A0A0B]">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <form @submit.prevent="$store.app.closeModal('assignCoordinator'); $store.app.showToast('Surat Keputusan Koordinator TA berhasil diterbitkan!')" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Pilih Dosen FITE</label>
          <select class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
            <option>Marlina Valentina, S.Kom., M.Sc.</option>
            <option>Dr. Johannes Harungguan, S.T., M.T.</option>
            <option>Samuel P. Pandiangan, M.Kom.</option>
            <option>Dr. Arnaldo Marulitua Sinaga</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Program Studi Ditugaskan</label>
          <select class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
            <option>S1 Informatika</option>
            <option>S1 Sistem Informasi</option>
            <option>S1 Teknik Elektro</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1">Masa Penugasan</label>
          <input type="text" value="Tahun Akademik 2025/2026" class="w-full text-xs px-3 py-2 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B]">
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#E0E0E0]">
          <button type="button" @click="$store.app.closeModal('assignCoordinator')" class="btn-secondary text-xs">Batal</button>
          <button type="submit" class="btn-primary text-xs">Terbitkan Penugasan</button>
        </div>
      </form>
    </div>
  </div>
</div>
