<?php

namespace Database\Seeders;

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

        // 3. Dosen Pembimbing 2
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

        // 4. Kaprodi
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

        // 7. Mahasiswa TA Aktif
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

        // Tugas Akhir Samuel Siahaan
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

        // 3 Riwayat Logbook
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
            ]
        );

        // Ujian TA
        UjianTa::updateOrCreate(
            ['tugas_akhir_id' => $tugasAkhir->id, 'jenis_ujian' => 'seminar_ta'],
            [
                'file_persyaratan' => 'uploads/persyaratan/Bundel_Seminar_TA_11S21004.zip',
                'status_dokumen' => 'berkas_lengkap',
                'verified_by_ta_id' => $userTA->id,
                'catatan_verifikasi_ta' => 'Semua berkas fisik dan digital telah diperiksa lengkap & valid.',
                'tanggal_ujian' => now()->addDays(8)->toDateString(),
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
                'prodi' => 'S1 Teknik Komputer',
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
