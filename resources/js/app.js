import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('app', {
  currentRole: 'mahasiswa_ta', // mahasiswa_non_ta, mahasiswa_ta, dosen, koordinator, kaprodi, ta, baak
  currentScreen: 'mahasiswa_dashboard', // login, mahasiswa_dashboard, dosen_workspace, janji_bimbingan, ujian_ta, pustaka_ta, panel_eksekutif
  sidebarCollapsed: false,
  notificationOpen: false,
  roleDropdownOpen: false,

  // Toast feedback
  toast: {
    show: false,
    message: '',
    type: 'success'
  },
  showToast(message, type = 'success') {
    this.toast.message = message;
    this.toast.type = type;
    this.toast.show = true;
    setTimeout(() => {
      this.toast.show = false;
    }, 3500);
  },

  // Switch Role Helper
  setRole(role) {
    this.currentRole = role;
    this.roleDropdownOpen = false;

    // Automatic smart screen redirection based on role
    if (role === 'mahasiswa_non_ta') {
      this.currentScreen = 'pustaka_ta';
      this.showToast('Beralih ke Role: Mahasiswa Non-TA (Akses Pustaka Arsip Read-only)');
    } else if (role === 'mahasiswa_ta') {
      this.currentScreen = 'mahasiswa_dashboard';
      this.showToast('Beralih ke Role: Mahasiswa TA (Akses Penuh Logbook & Progres)');
    } else if (role === 'dosen') {
      this.currentScreen = 'dosen_workspace';
      this.showToast('Beralih ke Role: Dosen Pembimbing (Workspace Review Mahasiswa)');
    } else if (role === 'koordinator') {
      this.currentScreen = 'ujian_ta';
      this.showToast('Beralih ke Role: Koordinator TA (Penetapan Pembimbing & Ujian)');
    } else if (role === 'kaprodi') {
      this.currentScreen = 'panel_eksekutif';
      this.showToast('Beralih ke Role: Kaprodi (Statistik & Penetapan Koordinator)');
    } else if (role === 'ta') {
      this.currentScreen = 'ujian_ta';
      this.showToast('Beralih ke Role: Teaching Assistant (Verifikator Dokumen & Arsip)');
    } else if (role === 'baak') {
      this.currentScreen = 'ujian_ta';
      this.showToast('Beralih ke Role: BAAK (Penjadwalan Ruangan & Link Zoom)');
    }
  },

  getRoleLabel(role = null) {
    const r = role || this.currentRole;
    const labels = {
      mahasiswa_non_ta: 'Mahasiswa Non-TA',
      mahasiswa_ta: 'Mahasiswa TA',
      dosen: 'Dosen Pembimbing',
      koordinator: 'Koordinator TA',
      kaprodi: 'Ketua Program Studi (Kaprodi)',
      ta: 'Teaching Assistant (TA)',
      baak: 'Biro Administrasi Akademik (BAAK)'
    };
    return labels[r] || r;
  },

  getUserName() {
    const names = {
      mahasiswa_non_ta: 'Yosua Tambunan (11S22014)',
      mahasiswa_ta: 'Samuel Siahaan (11S21004)',
      dosen: 'Dr. Johannes Harungguan, S.T., M.T.',
      koordinator: 'Marlina Valentina, S.Kom., M.Sc.',
      kaprodi: 'Dr. Arnaldo Marulitua Sinaga, S.T., M.InfoTech.',
      ta: 'Grace Panjaitan, S.Kom. (TA FITE)',
      baak: 'Staf Layanan Akademik FITE - BAAK'
    };
    return names[this.currentRole];
  },

  getUserProdi() {
    if (this.currentRole === 'mahasiswa_ta' || this.currentRole === 'mahasiswa_non_ta') {
      return 'S1 Informatika - Angkatan 2021';
    }
    return 'Fakultas Informatika & Teknik Elektro (FITE)';
  },

  // Notifications Store
  notifications: [
    {
      id: 1,
      sender: 'Dr. Johannes Harungguan',
      role: 'Dosen Pembimbing 1',
      title: 'Catatan Revisi Logbook Bab 4 Diperbarui',
      time: '15 menit yang lalu',
      read: false,
      type: 'review',
      linkText: 'Lihat Coretan Dokumen'
    },
    {
      id: 2,
      sender: 'Grace Panjaitan (TA)',
      role: 'Teaching Assistant',
      title: 'Verifikasi Berkas Pendaftaran Seminar TA: Lengkap',
      time: '2 jam yang lalu',
      read: false,
      type: 'verification',
      linkText: 'Cek Status Pendaftaran'
    },
    {
      id: 3,
      sender: 'BAAK Institut Teknologi Del',
      role: 'BAAK',
      title: 'Ruangan Ujian Sempro dialokasikan ke GD 512 & Zoom Room 3',
      time: 'Kemarin, 14:20 WIB',
      read: true,
      type: 'exam',
      linkText: 'Detail Jadwal'
    },
    {
      id: 4,
      sender: 'Samuel Siahaan',
      role: 'Mahasiswa TA',
      title: 'Pengajuan Janji Bimbingan Baru: Evaluasi Algoritma Model',
      time: '2 hari yang lalu',
      read: true,
      type: 'appointment',
      linkText: 'Tinjau Pengajuan'
    }
  ],

  markAllNotificationsRead() {
    this.notifications.forEach(n => n.read = true);
    this.showToast('Semua notifikasi ditandai telah dibaca');
  },

  get unreadNotificationCount() {
    return this.notifications.filter(n => !n.read).length;
  },

  // Modals Controller
  modals: {
    logbook: false,
    chapterProgress: false,
    appointment: false,
    dosenReviewDrawer: false,
    examRegister: false,
    verifyArtifact: false,
    assignExaminer: false,
    baakSchedule: false,
    uploadArchive: false,
    assignCoordinator: false,
    assignSupervisor: false
  },

  openModal(name) {
    this.modals[name] = true;
  },
  closeModal(name) {
    this.modals[name] = false;
  },

  // Selected Data for Detail Viewers
  selectedMahasiswa: null,
  selectedLogbookItem: null,
  selectedExamApplication: null
});

// Start Alpine
Alpine.start();
