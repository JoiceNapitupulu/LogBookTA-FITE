<?php

namespace Database\Seeders;

use App\Models\AnggotaTugasAkhir;
use App\Models\ArsipTugasAkhir;
use App\Models\DosenProfile;
use App\Models\JanjiBimbingan;
use App\Models\Logbook;
use App\Models\MahasiswaProfile;
use App\Models\TugasAkhir;
use App\Models\UjianTa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');

        // 1. Mahasiswa Non-TA
        $userMhsNonTa = User::updateOrCreate(
            ['email' => 'mhs.nonta@students.del.ac.id'],
            [
                'name' => 'Yosua Tambunan',
                'password' => $defaultPassword,
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]
        );

        $mhsNonTaProfile = MahasiswaProfile::updateOrCreate(
            ['user_id' => $userMhsNonTa->id],
            [
                'nim' => '11S22014',
                'prodi' => 'S1 Informatika',
                'angkatan' => 2022,
                'is_ta' => false,
            ]
        );

        // 2. Dosen Pembimbing 1 (Koordinator TA)
        $userDosen1 = User::updateOrCreate(
            ['email' => 'dosen1@del.ac.id'],
            [
                'name' => 'Dr. Johannes Harungguan, S.T., M.T.',
                'password' => $defaultPassword,
                'role' => 'dosen',
                'email_verified_at' => now(),
            ]
        );

        $dosen1Profile = DosenProfile::updateOrCreate(
            ['user_id' => $userDosen1->id],
            [
                'nidn' => '0118048201',
                'kode_dosen' => 'JHH',
                'prodi' => 'S1 Informatika',
                'is_koordinator_ta' => true,
                'is_kaprodi' => false,
            ]
        );

        // 3. Dosen Pembimbing 2 (S1 Sistem Informasi)
        $userDosen2 = User::updateOrCreate(
            ['email' => 'dosen2@del.ac.id'],
            [
                'name' => 'Marlina Valentina, S.Kom., M.Sc.',
                'password' => $defaultPassword,
                'role' => 'dosen',
                'email_verified_at' => now(),
            ]
        );

        $dosen2Profile = DosenProfile::updateOrCreate(
            ['user_id' => $userDosen2->id],
            [
                'nidn' => '0124088701',
                'kode_dosen' => 'MVN',
                'prodi' => 'S1 Sistem Informasi',
                'is_koordinator_ta' => false,
                'is_kaprodi' => false,
            ]
        );

        // 3b. Dosen Tambahan (S1 Sistem Informasi - untuk uji pergantian 1 koor per prodi)
        $userDosen3 = User::updateOrCreate(
            ['email' => 'dosen3@del.ac.id'],
            [
                'name' => 'Togu Novriansyah, S.T., M.Kom.',
                'password' => $defaultPassword,
                'role' => 'dosen',
                'email_verified_at' => now(),
            ]
        );

        $dosen3Profile = DosenProfile::updateOrCreate(
            ['user_id' => $userDosen3->id],
            [
                'nidn' => '0128038902',
                'kode_dosen' => 'TNV',
                'prodi' => 'S1 Sistem Informasi',
                'is_koordinator_ta' => false,
                'is_kaprodi' => false,
            ]
        );

        // 4. Kaprodi S1 Sistem Informasi (1 Kaprodi per Prodi)
        $userKaprodi = User::updateOrCreate(
            ['email' => 'kaprodi@del.ac.id'],
            [
                'name' => 'Dr. Arnaldo Marulitua Sinaga, S.T., M.InfoTech.',
                'password' => $defaultPassword,
                'role' => 'kaprodi',
                'email_verified_at' => now(),
            ]
        );

        $kaprodiProfile = DosenProfile::updateOrCreate(
            ['user_id' => $userKaprodi->id],
            [
                'nidn' => '0102067801',
                'kode_dosen' => 'AMS',
                'prodi' => 'S1 Sistem Informasi',
                'is_koordinator_ta' => false,
                'is_kaprodi' => true,
            ]
        );

        // 4b. Kaprodi S1 Informatika (1 Kaprodi per Prodi)
        $userKaprodiIf = User::updateOrCreate(
            ['email' => 'kaprodi.if@del.ac.id'],
            [
                'name' => 'Dr. Samuel Indra Gunawan, S.T., M.T.',
                'password' => $defaultPassword,
                'role' => 'kaprodi',
                'email_verified_at' => now(),
            ]
        );

        $kaprodiIfProfile = DosenProfile::updateOrCreate(
            ['user_id' => $userKaprodiIf->id],
            [
                'nidn' => '0112057901',
                'kode_dosen' => 'SIG',
                'prodi' => 'S1 Informatika',
                'is_koordinator_ta' => false,
                'is_kaprodi' => true,
            ]
        );

        // 4c. Kaprodi S1 Teknik Elektro (1 Kaprodi per Prodi)
        $userKaprodiTe = User::updateOrCreate(
            ['email' => 'kaprodi.te@del.ac.id'],
            [
                'name' => 'Dr. Parman Sukses, S.T., M.Sc.',
                'password' => $defaultPassword,
                'role' => 'kaprodi',
                'email_verified_at' => now(),
            ]
        );

        $kaprodiTeProfile = DosenProfile::updateOrCreate(
            ['user_id' => $userKaprodiTe->id],
            [
                'nidn' => '0109048102',
                'kode_dosen' => 'PSS',
                'prodi' => 'S1 Teknik Elektro',
                'is_koordinator_ta' => false,
                'is_kaprodi' => true,
            ]
        );

        // 5. Teaching Assistant (TA FITE)
        $userTA = User::updateOrCreate(
            ['email' => 'ta@del.ac.id'],
            [
                'name' => 'Grace Panjaitan, S.Kom.',
                'password' => $defaultPassword,
                'role' => 'teaching_assistant',
                'email_verified_at' => now(),
            ]
        );

        // 6. BAAK IT Del
        $userBaak = User::updateOrCreate(
            ['email' => 'baak@del.ac.id'],
            [
                'name' => 'Biro Administrasi Akademik (BAAK)',
                'password' => $defaultPassword,
                'role' => 'baak',
                'email_verified_at' => now(),
            ]
        );

        // 7. Mahasiswa TA Aktif (S1 Informatika - Kelompok 3 Mahasiswa)
        $userMhsTa = User::updateOrCreate(
            ['email' => 'mhs.ta@students.del.ac.id'],
            [
                'name' => 'Samuel Siahaan',
                'password' => $defaultPassword,
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]
        );

        $mhsTaProfile = MahasiswaProfile::updateOrCreate(
            ['user_id' => $userMhsTa->id],
            [
                'nim' => '11S21004',
                'prodi' => 'S1 Informatika',
                'angkatan' => 2021,
                'is_ta' => true,
            ]
        );

        $userMhsTa3 = User::updateOrCreate(
            ['email' => 'mhs.ta3@students.del.ac.id'],
            [
                'name' => 'Daniel Siregar',
                'password' => $defaultPassword,
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]
        );

        $mhsTaProfile3 = MahasiswaProfile::updateOrCreate(
            ['user_id' => $userMhsTa3->id],
            [
                'nim' => '11S21015',
                'prodi' => 'S1 Informatika',
                'angkatan' => 2021,
                'is_ta' => true,
            ]
        );

        $userMhsTa4 = User::updateOrCreate(
            ['email' => 'mhs.ta4@students.del.ac.id'],
            [
                'name' => 'Joy Christine',
                'password' => $defaultPassword,
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]
        );

        $mhsTaProfile4 = MahasiswaProfile::updateOrCreate(
            ['user_id' => $userMhsTa4->id],
            [
                'nim' => '11S21028',
                'prodi' => 'S1 Informatika',
                'angkatan' => 2021,
                'is_ta' => true,
            ]
        );

        // Tugas Akhir Samuel Siahaan dkk (Kelompok 3 Mahasiswa)
        $tugasAkhir = TugasAkhir::updateOrCreate(
            ['mahasiswa_id' => $mhsTaProfile->id],
            [
                'dosen_pembimbing_1_id' => $dosen1Profile->id,
                'dosen_pembimbing_2_id' => $dosen2Profile->id,
                'judul' => 'Pengembangan Sistem Deteksi Intrusi Jaringan Berbasis Deep Learning pada Infrastruktur Jaringan Kampus IT Del',
                'abstrak' => 'Penelitian ini mengembangkan arsitektur Convolutional Neural Network (CNN) 1D dan Bidirectional LSTM untuk mendeteksi ancaman intrusi siber secara real-time pada jaringan internal Institut Teknologi Del.',
                'prodi' => 'S1 Informatika',
                'tahun_ajaran' => '2025/2026',
                'status' => 'seminar_ta',
            ]
        );

        // Pivot keanggotaan kelompok TA Samuel dkk (3 anggota)
        AnggotaTugasAkhir::updateOrCreate(
            ['mahasiswa_id' => $mhsTaProfile->id],
            ['tugas_akhir_id' => $tugasAkhir->id]
        );
        AnggotaTugasAkhir::updateOrCreate(
            ['mahasiswa_id' => $mhsTaProfile3->id],
            ['tugas_akhir_id' => $tugasAkhir->id]
        );
        AnggotaTugasAkhir::updateOrCreate(
            ['mahasiswa_id' => $mhsTaProfile4->id],
            ['tugas_akhir_id' => $tugasAkhir->id]
        );

        // 8. Mahasiswa TA Aktif (S1 Sistem Informasi - Solo TA 1 Mahasiswa)
        $userMhsTa2 = User::updateOrCreate(
            ['email' => 'mhs.ta2@students.del.ac.id'],
            [
                'name' => 'Ruth Simanjuntak',
                'password' => $defaultPassword,
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]
        );

        $mhsTaProfile2 = MahasiswaProfile::updateOrCreate(
            ['user_id' => $userMhsTa2->id],
            [
                'nim' => '12S21020',
                'prodi' => 'S1 Sistem Informasi',
                'angkatan' => 2021,
                'is_ta' => true,
            ]
        );

        // Tugas Akhir Ruth Simanjuntak (Solo TA)
        $tugasAkhir2 = TugasAkhir::updateOrCreate(
            ['mahasiswa_id' => $mhsTaProfile2->id],
            [
                'dosen_pembimbing_1_id' => $dosen2Profile->id,
                'dosen_pembimbing_2_id' => $dosen1Profile->id,
                'judul' => 'Perancangan Tata Kelola dan Evaluasi Kematangan Keamanan Siber Berdasarkan Kerangka Kerja NIST CSF',
                'abstrak' => 'Evaluasi tingkat maturitas sistem informasi akademik menggunakan framework NIST CSF untuk mengidentifikasi celah keamanan data pribadi mahasiswa.',
                'prodi' => 'S1 Sistem Informasi',
                'tahun_ajaran' => '2025/2026',
                'status' => 'pengerjaan',
            ]
        );

        // Pivot keanggotaan Solo TA Ruth Simanjuntak (1 anggota)
        AnggotaTugasAkhir::updateOrCreate(
            ['mahasiswa_id' => $mhsTaProfile2->id],
            ['tugas_akhir_id' => $tugasAkhir2->id]
        );

        // 9. Mahasiswa TA Aktif (S1 Teknik Elektro - Kelompok 2 Mahasiswa)
        $userMhsTa5 = User::updateOrCreate(
            ['email' => 'mhs.ta5@students.del.ac.id'],
            [
                'name' => 'Andreas Nababan',
                'password' => $defaultPassword,
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]
        );

        $mhsTaProfile5 = MahasiswaProfile::updateOrCreate(
            ['user_id' => $userMhsTa5->id],
            [
                'nim' => '14S21002',
                'prodi' => 'S1 Teknik Elektro',
                'angkatan' => 2021,
                'is_ta' => true,
            ]
        );

        $userMhsTa6 = User::updateOrCreate(
            ['email' => 'mhs.ta6@students.del.ac.id'],
            [
                'name' => 'Maria Nainggolan',
                'password' => $defaultPassword,
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]
        );

        $mhsTaProfile6 = MahasiswaProfile::updateOrCreate(
            ['user_id' => $userMhsTa6->id],
            [
                'nim' => '14S21009',
                'prodi' => 'S1 Teknik Elektro',
                'angkatan' => 2021,
                'is_ta' => true,
            ]
        );

        $tugasAkhir3 = TugasAkhir::updateOrCreate(
            ['mahasiswa_id' => $mhsTaProfile5->id],
            [
                'dosen_pembimbing_1_id' => $kaprodiProfile->id,
                'dosen_pembimbing_2_id' => $dosen1Profile->id,
                'judul' => 'Rancang Bangun Sistem Monitoring Daya Panel Surya Berbasis ESP32 dan Protokol MQTT pada Laboratorium Energi Terbarukan',
                'abstrak' => 'Implementasi sistem sensor telemetri tegangan dan arus sel surya untuk analisis efisiensi konversi daya secara real-time.',
                'prodi' => 'S1 Teknik Elektro',
                'tahun_ajaran' => '2025/2026',
                'status' => 'pengerjaan',
            ]
        );

        // Pivot keanggotaan kelompok TA Elektro (2 anggota)
        AnggotaTugasAkhir::updateOrCreate(
            ['mahasiswa_id' => $mhsTaProfile5->id],
            ['tugas_akhir_id' => $tugasAkhir3->id]
        );
        AnggotaTugasAkhir::updateOrCreate(
            ['mahasiswa_id' => $mhsTaProfile6->id],
            ['tugas_akhir_id' => $tugasAkhir3->id]
        );

        // Logbook Ruth Simanjuntak
        Logbook::updateOrCreate(
            ['tugas_akhir_id' => $tugasAkhir2->id, 'bab_progress' => 'BAB 1'],
            [
                'tanggal' => now()->subDays(20),
                'deskripsi_kegiatan' => 'Penyusunan latar belakang masalah, identifikasi risiko privasi data, dan perumusan pertanyaan penelitian Bab 1.',
                'kendala' => 'Menyelaraskan regulasi PDP UU No. 27/2022 dengan kontrol spesifik NIST CSF.',
                'rencana_berikutnya' => 'Melakukan pemetaan domain Protect dan Detect pada infrastruktur kampus.',
                'file_dokumen_mhs' => 'uploads/logbooks/Draf_Bab1_Ruth.pdf',
                'catatan_dosen' => 'Latar belakang sudah fokus dan relevan. Lanjutkan studi literatur Bab 2.',
                'file_revisi_dosen' => null,
                'status_verifikasi' => 'disetujui',
                'verified_by_dosen_id' => $dosen2Profile->id,
            ]
        );

        Logbook::updateOrCreate(
            ['tugas_akhir_id' => $tugasAkhir2->id, 'bab_progress' => 'BAB 2'],
            [
                'tanggal' => now()->subDays(3),
                'deskripsi_kegiatan' => 'Analisis komparatif standar ISO 27001 vs NIST CSF v2.0 untuk institusi pendidikan tinggi.',
                'kendala' => 'Dokumentasi acuan maturity model NIST terbaru masih sangat minim berbahasa Indonesia.',
                'rencana_berikutnya' => 'Menyelesaikan sintesis matriks perbandingan kontrol keamanan.',
                'file_dokumen_mhs' => 'uploads/logbooks/Draf_Bab2_Ruth_v1.pdf',
                'catatan_dosen' => null,
                'file_revisi_dosen' => null,
                'status_verifikasi' => 'pending',
                'verified_by_dosen_id' => null,
            ]
        );

        // 3 Riwayat Logbook (Samuel Siahaan)
        Logbook::updateOrCreate(
            ['tugas_akhir_id' => $tugasAkhir->id, 'bab_progress' => 'BAB 2'],
            [
                'tanggal' => now()->subDays(28),
                'deskripsi_kegiatan' => 'Kajian literatur komparasi algoritma CNN vs Transformer untuk klasifikasi lalu lintas paket jaringan time-series.',
                'kendala' => 'Keterbatasan akses jurnal IEEE terbaru untuk tahun terbit 2024-2026.',
                'rencana_berikutnya' => 'Melanjutkan perancangan alur diagram sistem metodologi Bab 3.',
                'file_dokumen_mhs' => 'uploads/logbooks/Draf_Bab2_Literature_Review.pdf',
                'catatan_dosen' => 'Format sitasi IEEE telah disetujui. Silakan lanjut ke Bab 3.',
                'file_revisi_dosen' => null,
                'status_verifikasi' => 'disetujui',
                'verified_by_dosen_id' => $dosen1Profile->id,
            ]
        );

        Logbook::updateOrCreate(
            ['tugas_akhir_id' => $tugasAkhir->id, 'bab_progress' => 'BAB 3'],
            [
                'tanggal' => now()->subDays(14),
                'deskripsi_kegiatan' => 'Penyusunan arsitektur data pipeline preprocessing PCAP, pembersihan noise, dan pembagian dataset rasio 80:20.',
                'kendala' => 'Ketidakseimbangan kelas serangan DoS UDP flood pada dataset lokal.',
                'rencana_berikutnya' => 'Melakukan augmentasi teknik SMOTE dan uji coba training epoch awal.',
                'file_dokumen_mhs' => 'uploads/logbooks/Draf_Bab3_Metodologi_v2.pdf',
                'catatan_dosen' => 'Desain metodologi terstruktur dengan baik. Disetujui untuk implementasi coding.',
                'file_revisi_dosen' => null,
                'status_verifikasi' => 'disetujui',
                'verified_by_dosen_id' => $dosen2Profile->id,
            ]
        );

        Logbook::updateOrCreate(
            ['tugas_akhir_id' => $tugasAkhir->id, 'bab_progress' => 'BAB 4'],
            [
                'tanggal' => now()->subDays(5),
                'deskripsi_kegiatan' => 'Pengujian performa model deep learning pada testbed lab GD 512 dengan metrik akurasi, precision, recall, dan F1-Score.',
                'kendala' => 'Terjadi overfitting ringan pada epoch ke-35.',
                'rencana_berikutnya' => 'Menambahkan dropout layer 0.3 dan regularisasi L2.',
                'file_dokumen_mhs' => 'uploads/logbooks/Draf_Bab4_Model_v3.pdf',
                'catatan_dosen' => 'Perbaiki confusion matrix pada subbab 4.2 dan tambahkan grafik ROC-AUC.',
                'file_revisi_dosen' => 'uploads/revisi/Coretan_Dosen_Bab4_JHH.pdf',
                'status_verifikasi' => 'revisi',
                'verified_by_dosen_id' => $dosen1Profile->id,
            ]
        );

        // Janji Bimbingan
        JanjiBimbingan::updateOrCreate(
            ['tugas_akhir_id' => $tugasAkhir->id, 'topik_pembahasan' => 'Evaluasi Hasil Revisi Confusion Matrix Bab 4 & Kesiapan Seminar TA'],
            [
                'dosen_id' => $dosen1Profile->id,
                'inisiator' => 'mahasiswa',
                'tanggal_waktu' => now()->addDays(3)->setTime(10, 0),
                'metode' => 'offline',
                'lokasi_atau_link' => 'Ruang Dosen Gedung 5 Lantai 1 (GD 512)',
                'status' => 'menunggu_persetujuan',
                'alasan_penolakan' => null,
            ]
        );

        JanjiBimbingan::updateOrCreate(
            ['tugas_akhir_id' => $tugasAkhir->id, 'topik_pembahasan' => 'Verifikasi Format Penulisan Naskah Bab 1 s/d 4'],
            [
                'dosen_id' => $dosen2Profile->id,
                'inisiator' => 'dosen',
                'tanggal_waktu' => now()->subDays(7)->setTime(14, 0),
                'metode' => 'online',
                'lokasi_atau_link' => 'https://zoom.us/j/itdel-fite-dosen2',
                'status' => 'disetujui',
                'alasan_penolakan' => null,
                'catatan_hasil' => null,
            ]
        );

        JanjiBimbingan::updateOrCreate(
            ['tugas_akhir_id' => $tugasAkhir->id, 'topik_pembahasan' => 'Diskusi Perancangan Diagram Metodologi dan Dataset PCAP Bab 3'],
            [
                'dosen_id' => $dosen1Profile->id,
                'inisiator' => 'mahasiswa',
                'tanggal_waktu' => now()->subDays(18)->setTime(13, 30),
                'metode' => 'offline',
                'lokasi_atau_link' => 'Ruang Dosen Gedung 5 Lantai 1 (GD 512)',
                'status' => 'selesai',
                'alasan_penolakan' => null,
                'catatan_hasil' => 'Mahasiswa telah memaparkan alur diagram metodologi. Disetujui menggunakan pipeline PCAP capture. Action item: lengkapi skema data preprocessing sebelum implementasi coding model.',
            ]
        );

        // Ujian TA (Samuel Siahaan: Seminar TA 1)
        UjianTa::updateOrCreate(
            ['tugas_akhir_id' => $tugasAkhir->id, 'jenis_ujian' => 'seminar_ta_1'],
            [
                'file_persyaratan' => 'uploads/persyaratan/Bundel_Seminar_TA_11S21004.zip',
                'status_dokumen' => 'berkas_lengkap',
                'verified_by_ta_id' => $userDosen1->id,
                'catatan_verifikasi_ta' => 'Semua berkas fisik dan digital telah diperiksa lengkap & valid oleh Koordinator TA FITE.',
                'tanggal_ujian' => now()->addDays(8)->toDateString(),
                'dosen_penguji_1_id' => $kaprodiProfile->id,
                'dosen_penguji_2_id' => $dosen2Profile->id,
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:00:00',
                'ruangan' => 'Ruang Sidang FITE GD 514',
                'link_zoom' => 'https://zoom.us/j/itdel-sidang-fite1',
                'status_ujian' => 'terjadwal',
            ]
        );

        // Arsip Tugas Akhir Alumni FITE
        ArsipTugasAkhir::updateOrCreate(
            ['nim' => '11S20011'],
            [
                'tugas_akhir_id' => null,
                'judul' => 'Sistem Pemantauan Kualitas Air Danau Toba Berbasis Internet of Things dan Dashboard Interaktif',
                'penulis' => 'Grace Hutapea',
                'prodi' => 'S1 Informatika',
                'tahun_lulus' => 2025,
                'nama_dospem_1' => 'Dr. Johannes Harungguan, S.T., M.T.',
                'nama_dospem_2' => 'Marlina Valentina, S.Kom., M.Sc.',
                'kata_kunci' => 'IoT, MQTT, Danau Toba, Telemetri',
                'abstrak' => 'Penelitian ini mengembangkan alat telemetri berbasis sensor mikrokontroler ESP32 untuk memonitor parameter pH, kekeruhan, dan suhu air Danau Toba secara kontinu.',
                'file_dokumen' => 'uploads/arsip/Naskah_TA_11S20011_Grace.pdf',
                'uploaded_by_user_id' => $userTA->id,
            ]
        );

        ArsipTugasAkhir::updateOrCreate(
            ['nim' => '12S20042'],
            [
                'tugas_akhir_id' => null,
                'judul' => 'Implementasi Microservices Architecture pada Platform E-Commerce Produk Pertanian Toba',
                'penulis' => 'Bona Manurung',
                'prodi' => 'S1 Sistem Informasi',
                'tahun_lulus' => 2024,
                'nama_dospem_1' => 'Marlina Valentina, S.Kom., M.Sc.',
                'nama_dospem_2' => 'Dr. Arnaldo Marulitua Sinaga, S.T., M.InfoTech.',
                'kata_kunci' => 'Microservices, Docker, Kafka, E-Commerce',
                'abstrak' => 'Membangun arsitektur backend terdistribusi untuk mengatasi lonjakan beban transaksi produk UMKM kopi dan andaliman Toba.',
                'file_dokumen' => 'uploads/arsip/Naskah_TA_12S20042_Bona.pdf',
                'uploaded_by_user_id' => $userTA->id,
            ]
        );

        ArsipTugasAkhir::updateOrCreate(
            ['nim' => '14S20005'],
            [
                'tugas_akhir_id' => null,
                'judul' => 'Rancang Bangun Node Sensor Nirkabel Berdaya Rendah untuk Deteksi Tanah Longsor di Kawasan Sigumpar',
                'penulis' => 'Tulus Simanjuntak',
                'prodi' => 'S1 Teknik Elektro',
                'tahun_lulus' => 2024,
                'nama_dospem_1' => 'Dr. Arnaldo Marulitua Sinaga, S.T., M.InfoTech.',
                'nama_dospem_2' => 'Dr. Johannes Harungguan, S.T., M.T.',
                'kata_kunci' => 'LoRaWAN, MEMS Accelerometer, Early Warning',
                'abstrak' => 'Implementasi sensor getaran dan inklinasi lereng tanah dengan transmisi data nirkabel jarak jauh LoRaWAN hemat daya baterai surya.',
                'file_dokumen' => 'uploads/arsip/Naskah_TA_14S20005_Tulus.pdf',
                'uploaded_by_user_id' => $userTA->id,
            ]
        );
    }
}
