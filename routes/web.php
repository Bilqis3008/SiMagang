<?php

use App\Http\Controllers\AdminPembimbingController;
use App\Http\Controllers\AdminSiswaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\SiswaKegiatanController;
use App\Http\Controllers\SiswaPengajuanController;
use App\Http\Controllers\SiswaProfilController;
use App\Http\Controllers\SiswaLaporanController;
use App\Http\Controllers\PembimbingSiswaController;
use App\Http\Controllers\PembimbingEvaluasiController;
use App\Http\Controllers\PembimbingPengajuanController;
use App\Http\Controllers\PembimbingLaporanController;
use App\Http\Controllers\PembimbingProfilController;
use App\Http\Controllers\AdminMasterDataController;
use App\Http\Controllers\PimpinanDashboardController;
use App\Http\Controllers\PimpinanAdminController;
use App\Http\Controllers\PimpinanMonitoringController;
use App\Http\Controllers\PimpinanRekapController;
use App\Http\Controllers\PimpinanProfilController;
use App\Http\Controllers\Api\SchoolApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])
    ->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/api/schools/{npsn}', [SchoolApiController::class, 'getSchoolByNpsn'])->name('api.schools.show');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Halaman peran (siswa, guru, pembimbing, admin)
Route::middleware('auth')->group(function () {
    Route::get('/siswa/siswa', [SiswaDashboardController::class, 'dashboard'])->name('siswa.siswa');
    Route::get('/siswa/absensi', [SiswaKegiatanController::class, 'absensiKegiatan'])->name('siswa.absensi');
    Route::get('/siswa/absensi/detail', [SiswaKegiatanController::class, 'getAbsensiDetail'])->name('siswa.absensi.detail');
    Route::get('/siswa/logbook/detail', [SiswaKegiatanController::class, 'getLogbookDetail'])->name('siswa.logbook.detail');
    Route::post('/siswa/absensi', [SiswaKegiatanController::class, 'storeAbsensi'])->name('siswa.absensi.store');
    Route::post('/siswa/logbook', [SiswaKegiatanController::class, 'storeLogbook'])->name('siswa.logbook.store');

    // Pengajuan Lupa Absensi / Kegiatan
    Route::get('/siswa/pengajuan', [SiswaPengajuanController::class, 'pengajuan'])->name('siswa.pengajuan');
    Route::post('/siswa/pengajuan', [SiswaPengajuanController::class, 'storePengajuan'])->name('siswa.pengajuan.store');

    // Profil Siswa
    Route::get('/siswa/profil', [SiswaProfilController::class, 'showProfile'])->name('siswa.profil');
    Route::put('/siswa/profil', [SiswaProfilController::class, 'updateProfile'])->name('siswa.profil.update');
    Route::put('/siswa/profil/password', [SiswaProfilController::class, 'updatePassword'])->name('siswa.profil.password');

    // Laporan
    Route::get('/siswa/laporan', [SiswaLaporanController::class, 'laporan'])->name('siswa.laporan');
    Route::post('/siswa/laporan/upload', [SiswaLaporanController::class, 'uploadLaporanAkhir'])->name('siswa.laporan.upload');
    Route::get('/siswa/laporan/download-akhir', [SiswaLaporanController::class, 'previewLaporanAkhir'])->name('siswa.laporan.downloadAkhir');
    Route::get('/siswa/laporan/download/jurnal-mingguan', [SiswaLaporanController::class, 'downloadJurnalMingguan'])->name('siswa.rekap.jurnal');
    Route::get('/siswa/laporan/download/rekap-individu', [SiswaLaporanController::class, 'downloadRekapAbsensiIndividu'])->name('siswa.rekap.individu');
    Route::get('/siswa/laporan/download/rekap-kelompok', [SiswaLaporanController::class, 'downloadRekapAbsensiKelompok'])->name('siswa.rekap.kelompok');
    Route::get('/siswa/penilaian/cetak', [SiswaLaporanController::class, 'cetakPenilaian'])->name('siswa.penilaian.cetak');
    Route::get('/siswa/laporan/sertifikat', [SiswaLaporanController::class, 'cetakSertifikat'])->name('siswa.sertifikat.cetak');

    // Modul Pembelajaran Siswa
    Route::get('/siswa/modul', [\App\Http\Controllers\SiswaModulController::class, 'index'])->name('siswa.modul.index');
    Route::get('/siswa/modul/{id}', [\App\Http\Controllers\SiswaModulController::class, 'belajar'])->name('siswa.modul.belajar');
    Route::get('/siswa/modul/{id}/pdf', [\App\Http\Controllers\SiswaModulController::class, 'viewPdf'])->name('siswa.modul.viewPdf');
    Route::post('/siswa/modul/{id}/selesai-pdf', [\App\Http\Controllers\SiswaModulController::class, 'selesaikanPdf'])->name('siswa.modul.selesaiPdf');
    Route::post('/siswa/modul/{id}/selesai-video', [\App\Http\Controllers\SiswaModulController::class, 'selesaikanVideo'])->name('siswa.modul.selesaiVideo');
    Route::get('/siswa/modul/{id}/kuis', [\App\Http\Controllers\SiswaModulController::class, 'kuis'])->name('siswa.modul.kuis');
    Route::post('/siswa/modul/{id}/kuis', [\App\Http\Controllers\SiswaModulController::class, 'submitKuis'])->name('siswa.modul.submitKuis');
    Route::get('/siswa/modul/{id}/hasil', [\App\Http\Controllers\SiswaModulController::class, 'hasilKuis'])->name('siswa.modul.hasil');

    Route::get('/pembimbing/pembimbing', [AuthController::class, 'pembimbing'])->name('pembimbing.pembimbing');
    Route::get('/pembimbing/siswa', [PembimbingSiswaController::class, 'daftarSiswa'])->name('pembimbing.siswa');
    Route::post('/pembimbing/siswa/{nisn}/assign-modul', [PembimbingSiswaController::class, 'assignModul'])->name('pembimbing.siswa.assignModul');
    Route::put('/pembimbing/modul-siswa/{id}', [PembimbingSiswaController::class, 'updateModulSiswa'])->name('pembimbing.modulSiswa.update');
    Route::delete('/pembimbing/modul-siswa/{id}', [PembimbingSiswaController::class, 'deleteModulSiswa'])->name('pembimbing.modulSiswa.delete');
    Route::get('/pembimbing/siswa/{nisn}/absensi', [PembimbingSiswaController::class, 'absensiSiswa'])->name('pembimbing.absensi');
    Route::post('/pembimbing/absensi/{id}/validasi', [PembimbingSiswaController::class, 'validasiAbsensi'])->name('pembimbing.absensi.validasi');
    Route::get('/pembimbing/siswa/{nisn}/logbook', [PembimbingSiswaController::class, 'logbookSiswa'])->name('pembimbing.logbook');
    Route::post('/pembimbing/logbook/{id}/validasi', [PembimbingSiswaController::class, 'validasiLogbook'])->name('pembimbing.logbook.validasi');
    
    // Bulk Validation
    Route::post('/pembimbing/logbook/siswa/{nisn}/validasi-semua', [PembimbingSiswaController::class, 'validasiSemuaLogbook'])->name('pembimbing.logbook.validasi-semua');
    Route::post('/pembimbing/absensi/siswa/{nisn}/validasi-semua', [PembimbingSiswaController::class, 'validasiSemuaAbsensi'])->name('pembimbing.absensi.validasi-semua');

    // Modul Pembelajaran Pembimbing
    Route::get('/pembimbing/modul', [\App\Http\Controllers\PembimbingModulController::class, 'index'])->name('pembimbing.modul.index');
    Route::get('/pembimbing/modul/tambah', [\App\Http\Controllers\PembimbingModulController::class, 'create'])->name('pembimbing.modul.create');
    Route::post('/pembimbing/modul', [\App\Http\Controllers\PembimbingModulController::class, 'store'])->name('pembimbing.modul.store');
    Route::get('/pembimbing/modul/{id}', [\App\Http\Controllers\PembimbingModulController::class, 'show'])->name('pembimbing.modul.show');
    Route::get('/pembimbing/modul/{id}/edit', [\App\Http\Controllers\PembimbingModulController::class, 'edit'])->name('pembimbing.modul.edit');
    Route::put('/pembimbing/modul/{id}', [\App\Http\Controllers\PembimbingModulController::class, 'update'])->name('pembimbing.modul.update');
    Route::get('/pembimbing/modul/{id}/pdf', [\App\Http\Controllers\PembimbingModulController::class, 'viewPdf'])->name('pembimbing.modul.viewPdf');
    Route::delete('/pembimbing/modul/{id}', [\App\Http\Controllers\PembimbingModulController::class, 'destroy'])->name('pembimbing.modul.destroy');
    
    Route::get('/pembimbing/evaluasi', [PembimbingEvaluasiController::class, 'evaluasiSiswa'])->name('pembimbing.evaluasi');
    Route::get('/pembimbing/evaluasi/input/{nisn}', [PembimbingEvaluasiController::class, 'inputEvaluasi'])->name('pembimbing.evaluasi.input');
    Route::post('/pembimbing/evaluasi', [PembimbingEvaluasiController::class, 'storeEvaluasi'])->name('pembimbing.evaluasi.store');
    
    // Pengajuan Siswa
    Route::get('/pembimbing/pengajuan', [PembimbingPengajuanController::class, 'pengajuanSiswa'])->name('pembimbing.pengajuan');
    Route::post('/pembimbing/pengajuan/{id}', [PembimbingPengajuanController::class, 'updatePengajuan'])->name('pembimbing.pengajuan.update');

    // Reporting
    Route::get('/pembimbing/siswa/{nisn}/cetak-jurnal', [PembimbingLaporanController::class, 'cetakJurnalSiswa'])->name('pembimbing.siswa.cetakJurnal');
    Route::get('/pembimbing/siswa/{nisn}/cetak-absensi', [PembimbingLaporanController::class, 'cetakAbsensiSiswa'])->name('pembimbing.siswa.cetakAbsensi');
    
    Route::get('/pembimbing/laporan/{nisn}/cetak', [PembimbingLaporanController::class, 'cetakLaporanSiswa'])->name('pembimbing.laporan.cetak');
    Route::get('/pembimbing/siswa/{nisn}/cetak-laporan-akhir', [PembimbingLaporanController::class, 'cetakLaporanAkhir'])->name('pembimbing.siswa.cetakLaporan');
    Route::get('/pembimbing/siswa/{nisn}/cetak-sertifikat', [PembimbingLaporanController::class, 'cetakSertifikatSiswa'])->name('pembimbing.siswa.cetakSertifikat');

    Route::get('/pembimbing/profil', [PembimbingProfilController::class, 'profil'])->name('pembimbing.profil');
    Route::post('/pembimbing/profil', [PembimbingProfilController::class, 'updateProfil'])->name('pembimbing.profil.update');

    // Manajemen Kriteria Penilaian (Dynamic)
    Route::get('/pembimbing/kriteria', [PembimbingEvaluasiController::class, 'kriteriaPenilaian'])->name('pembimbing.kriteria');
    Route::post('/pembimbing/kriteria', [PembimbingEvaluasiController::class, 'storeKriteria'])->name('pembimbing.kriteria.store');
    Route::put('/pembimbing/kriteria/{id}', [PembimbingEvaluasiController::class, 'updateKriteria'])->name('pembimbing.kriteria.update');
    Route::delete('/pembimbing/kriteria/{id}', [PembimbingEvaluasiController::class, 'destroyKriteria'])->name('pembimbing.kriteria.destroy');


    Route::get('/admin/admin', [AuthController::class, 'admin'])->name('admin.admin');

    Route::get('/admin/pembimbing', [AdminPembimbingController::class, 'kelolaPembimbing'])->name('admin.kelolaPembimbing');
    Route::post('/admin/pembimbing', [AdminPembimbingController::class, 'store'])->name('admin.storePembimbing');
    Route::put('/admin/pembimbing/{id}', [AdminPembimbingController::class, 'update'])->name('admin.updatePembimbing');
    Route::delete('/admin/pembimbing/{id}', [AdminPembimbingController::class, 'destroy'])->name('admin.destroyPembimbing');

    // Manajemen siswa oleh admin
    Route::get('/admin/siswa', [AdminSiswaController::class, 'kelolaSiswa'])->name('admin.kelolaSiswa');
    Route::post('/admin/siswa', [AdminSiswaController::class, 'store'])->name('admin.storeSiswa');
    Route::put('/admin/siswa/{id}', [AdminSiswaController::class, 'update'])->name('admin.updateSiswa');
    Route::delete('/admin/siswa/{id}', [AdminSiswaController::class, 'destroy'])->name('admin.destroySiswa');
    Route::get('/admin/siswa/{nisn}/absensi', [AdminSiswaController::class, 'absensiSiswa'])->name('admin.siswa.absensi');
    Route::get('/admin/siswa/{nisn}/logbook', [AdminSiswaController::class, 'logbookSiswa'])->name('admin.siswa.logbook');

    // Admin Rekap Routes
    Route::get('/admin/siswa/{nisn}/download-jurnal', [AdminSiswaController::class, 'downloadJurnalMingguan'])->name('admin.rekap.jurnal');
    Route::get('/admin/siswa/{nisn}/download-absensi', [AdminSiswaController::class, 'downloadRekapAbsensiIndividu'])->name('admin.rekap.absensi');
    Route::get('/admin/siswa/{nisn}/download-rekap-kelompok', [AdminSiswaController::class, 'downloadRekapAbsensiKelompok'])->name('admin.rekap.kelompok');
    
    // Tambahan Rekap Riwayat (Pembimbing, Laporan, Sertifikat)
    Route::get('/admin/siswa/{nisn}/cetak-penilaian-pembimbing', [AdminSiswaController::class, 'cetakPenilaianPembimbing'])->name('admin.rekap.nilaiPembimbing');
    Route::get('/admin/siswa/{nisn}/cetak-laporan-akhir', [AdminSiswaController::class, 'cetakLaporanAkhir'])->name('admin.rekap.laporanAkhir');
    Route::get('/admin/siswa/{nisn}/cetak-sertifikat', [AdminSiswaController::class, 'cetakSertifikatSiswa'])->name('admin.rekap.sertifikat');

    // Manajemen Lokasi Absensi oleh admin
    Route::get('/admin/lokasi', [App\Http\Controllers\AdminSettingController::class, 'index'])->name('admin.kelolaLokasi');
    Route::post('/admin/lokasi', [App\Http\Controllers\AdminSettingController::class, 'store'])->name('admin.storeLokasi');
    Route::put('/admin/lokasi/{id}', [App\Http\Controllers\AdminSettingController::class, 'update'])->name('admin.updateLokasi');
    Route::delete('/admin/lokasi/{id}', [App\Http\Controllers\AdminSettingController::class, 'destroy'])->name('admin.destroyLokasi');

    // Manajemen Rekap Baru oleh admin
    Route::get('/admin/rekap', [App\Http\Controllers\AdminRekapController::class, 'index'])->name('admin.rekap');
    Route::get('/admin/rekap/stats', [App\Http\Controllers\AdminRekapController::class, 'getStats'])->name('admin.rekap.stats');
    Route::get('/admin/rekap/siswa-aktif', [App\Http\Controllers\AdminRekapController::class, 'rekapSiswaAktif'])->name('admin.rekap.siswaAktif');
    Route::get('/admin/rekap/siswa-selesai', [App\Http\Controllers\AdminRekapController::class, 'rekapSiswaSelesai'])->name('admin.rekap.siswaSelesai');
    Route::get('/admin/rekap/siswa-total', [App\Http\Controllers\AdminRekapController::class, 'rekapSiswaTotal'])->name('admin.rekap.siswaTotal');

    // Master Data (Sekolah & Periode)
    Route::get('/admin/master-data', [AdminMasterDataController::class, 'index'])->name('admin.masterData');
    Route::post('/admin/sekolah', [AdminMasterDataController::class, 'storeSekolah'])->name('admin.storeSekolah');
    Route::put('/admin/sekolah/{id}', [AdminMasterDataController::class, 'updateSekolah'])->name('admin.updateSekolah');
    Route::delete('/admin/sekolah/{id}', [AdminMasterDataController::class, 'destroySekolah'])->name('admin.destroySekolah');

    Route::post('/admin/periode', [AdminMasterDataController::class, 'storePeriode'])->name('admin.storePeriode');
    Route::put('/admin/periode/{id}', [AdminMasterDataController::class, 'updatePeriode'])->name('admin.updatePeriode');
    Route::delete('/admin/periode/{id}', [AdminMasterDataController::class, 'destroyPeriode'])->name('admin.destroyPeriode');

    // Informasi Dashboard
    Route::put('/admin/informasi', [AdminMasterDataController::class, 'updateInformasi'])->name('admin.updateInformasi');

    // Program Studi
    Route::post('/admin/prodi', [AdminMasterDataController::class, 'storeProdi'])->name('admin.storeProdi');
    Route::put('/admin/prodi/{id}', [AdminMasterDataController::class, 'updateProdi'])->name('admin.updateProdi');
    Route::delete('/admin/prodi/{id}', [AdminMasterDataController::class, 'destroyProdi'])->name('admin.destroyProdi');
    Route::put('/admin/konfigurasi-laporan', [AdminMasterDataController::class, 'updateKonfigurasiLaporan'])->name('admin.updateKonfigurasiLaporan');
    Route::get('/admin/konfigurasi-laporan/preview-sertifikat', [AdminMasterDataController::class, 'previewSertifikat'])->name('admin.konfigurasiLaporan.previewSertifikat');
    
    // Profil Admin
    Route::get('/admin/profil', [AuthController::class, 'adminProfil'])->name('admin.profil');
    Route::put('/admin/profil/update', [AuthController::class, 'updateAdminProfil'])->name('admin.profil.update');
    Route::put('/admin/profil/password', [AuthController::class, 'updateAdminPassword'])->name('admin.profil.password');
});

// ─────────────────────────────────────────────────────────────────────────
// RUTE PIMPINAN
// ─────────────────────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
    Route::get('/home', [PimpinanDashboardController::class, 'index'])->name('home');
    
    // Kelola Akun (Admin, Siswa, Guru, Pembimbing)
    Route::get('/admin', [PimpinanAdminController::class, 'kelolaAdmin'])->name('admin');
    Route::post('/admin', [PimpinanAdminController::class, 'storeAdmin'])->name('storeAdmin');
    Route::put('/admin/{id}', [PimpinanAdminController::class, 'updateAdmin'])->name('updateAdmin');
    Route::delete('/admin/{id}', [PimpinanAdminController::class, 'destroyAdmin'])->name('destroyAdmin');

    Route::get('/siswa', [PimpinanMonitoringController::class, 'siswa'])->name('siswa');
    Route::get('/guru', [PimpinanMonitoringController::class, 'guru'])->name('guru');
    Route::get('/pembimbing', [PimpinanMonitoringController::class, 'pembimbing'])->name('pembimbing');
    
    Route::get('/rekap', [PimpinanRekapController::class, 'rekap'])->name('rekap');
    Route::get('/rekap/stats', [PimpinanRekapController::class, 'rekapStats'])->name('rekap.stats');
    
    // Profil Pimpinan
    Route::get('/profil', [PimpinanProfilController::class, 'profil'])->name('profil');
    Route::put('/profil/update', [PimpinanProfilController::class, 'updateProfil'])->name('profil.update');
    Route::put('/profil/password', [PimpinanProfilController::class, 'updatePassword'])->name('profil.password');
});