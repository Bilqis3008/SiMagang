

<?php $__env->startSection('title', 'Daftar Siswa Bimbingan - SIM Magang'); ?>
<?php $__env->startSection('body-class', 'pembimbing-page'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/dosen/daftarSiswa.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/dosen/detail-modal.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('body'); ?>
    <div class="dashboard-container mt-4 mb-5">
        
        <div class="page-header">
            <div class="header-content">
                <h2 class="page-title"><i class="fas fa-users text-primary me-2"></i>Daftar Siswa Bimbingan</h2>
                <p class="page-subtitle">Kelola dan pantau seluruh siswa magang di bawah bimbingan Anda.</p>
            </div>
            <div class="header-actions">
                <div class="search-box">
                    <form id="searchForm" class="search-form">
                        <span class="search-icon"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchInput" value="<?php echo e($search); ?>" class="search-input"
                            placeholder="Cari Nama, NISN, atau Perusahaan..." autocomplete="off">
                    </form>
                </div>
            </div>
        </div>

        
        <div class="tabs-wrapper mb-4">
            <div class="tabs-nav">
                <button class="tab-button active btn-tab-trigger" data-target="active-students">
                    <i class="fas fa-user-clock"></i>
                    <span>Siswa Bimbingan (<?php echo e($siswasActive->count()); ?>)</span>
                </button>
                <button class="tab-button btn-tab-trigger" data-target="history-students" id="btnTabHistory">
                    <i class="fas fa-history"></i>
                    <span>Riwayat Siswa (<?php echo e($siswasHistory->count()); ?>)</span>
                </button>
            </div>
        </div>

        <div class="tab-contents">
            
            <div class="tab-pane-content" id="active-students">
                <div class="content-card">
                    <div class="custom-table-wrapper">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th width="4%" class="text-center">No</th>
                                    <th width="24%">Info Siswa</th>
                                    <th width="18%">Sekolah / Perusahaan</th>
                                    <th width="28%">Modul Pembelajaran</th>
                                    <th width="10%" class="text-center">Presensi</th>
                                    <th width="16%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="activeTableBody">
                                <?php $__empty_1 = true; $__currentLoopData = $siswasActive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="student-row">
                                        <td class="text-center"><?php echo e($index + 1); ?></td>
                                        <td>
                                            <div class="user-info-cell">
                                                <?php if($siswa->foto_profil): ?>
                                                    <img src="<?php echo e(asset('storage/' . $siswa->foto_profil)); ?>" alt="<?php echo e($siswa->nama); ?>" class="avatar-sm">
                                                <?php else: ?>
                                                    <div class="avatar-sm-placeholder bg-blue-light text-primary">
                                                        <?php echo e(strtoupper(substr($siswa->nama, 0, 1))); ?>

                                                    </div>
                                                <?php endif; ?>
                                                <div class="user-details">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="fw-bold text-dark"><?php echo e($siswa->nama); ?></span>
                                                        <span class="badge bg-success text-white" style="font-size: 0.6rem; padding: 1px 6px; border-radius: 50px;">AKTIF</span>
                                                    </div>
                                                    <span class="text-muted small">NISN: <?php echo e($siswa->nisn); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <span class="badge-role bg-purple-light text-purple"><i class="fas fa-school me-1"></i> <?php echo e($siswa->sekolah); ?></span>
                                                <span class="small text-muted"><i class="fas fa-building me-1"></i> <?php echo e($siswa->perusahaan); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($siswa->modulSiswas && $siswa->modulSiswas->count() > 0): ?>
                                                <div class="d-flex flex-column gap-2">
                                                    <?php $__currentLoopData = $siswa->modulSiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="p-2 px-3 rounded-3 border bg-light d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <div class="fw-bold text-dark small"><?php echo e($ms->modul->judul ?? 'Modul'); ?></div>
                                                                <div class="text-muted" style="font-size: 0.72rem;">
                                                                    <i class="fas fa-calendar-alt text-primary me-1"></i>
                                                                    <?php echo e($ms->tgl_mulai ? \Carbon\Carbon::parse($ms->tgl_mulai)->format('d/m/Y') : '-'); ?> s/d <?php echo e($ms->tgl_selesai ? \Carbon\Carbon::parse($ms->tgl_selesai)->format('d/m/Y') : '-'); ?>

                                                                </div>
                                                                <div class="mt-1">
                                                                    <?php if($ms->kuis_selesai): ?>
                                                                        <span class="badge bg-success" style="font-size: 0.65rem;"><i class="fas fa-check-circle me-1"></i> Selesai (Nilai: <?php echo e(number_format($ms->nilai_kuis, 0)); ?>)</span>
                                                                    <?php elseif($ms->video_selesai): ?>
                                                                        <span class="badge bg-warning text-dark" style="font-size: 0.65rem;"><i class="fas fa-pen-alt me-1"></i> Kuis</span>
                                                                    <?php elseif($ms->pdf_selesai): ?>
                                                                        <span class="badge bg-info text-dark" style="font-size: 0.65rem;"><i class="fas fa-play me-1"></i> Video</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-secondary" style="font-size: 0.65rem;"><i class="fas fa-book-open me-1"></i> Baca PDF</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-1">
                                                                <button type="button" class="btn btn-xs btn-outline-warning rounded-pill px-2 py-1 btn-edit-modul-siswa"
                                                                    data-bs-toggle="modal" data-bs-target="#modalEditModulSiswa"
                                                                    data-id="<?php echo e($ms->id_modul_siswa); ?>"
                                                                    data-judul="<?php echo e($ms->modul->judul ?? 'Modul'); ?>"
                                                                    data-siswa="<?php echo e($siswa->nama); ?>"
                                                                    data-mulai="<?php echo e($ms->tgl_mulai ? \Carbon\Carbon::parse($ms->tgl_mulai)->format('Y-m-d') : ''); ?>"
                                                                    data-selesai="<?php echo e($ms->tgl_selesai ? \Carbon\Carbon::parse($ms->tgl_selesai)->format('Y-m-d') : ''); ?>"
                                                                    data-selesai-kuis="<?php echo e($ms->kuis_selesai ? '1' : '0'); ?>"
                                                                    data-nilai="<?php echo e($ms->nilai_kuis ?? '-'); ?>"
                                                                    data-update-action="<?php echo e(route('pembimbing.modulSiswa.update', $ms->id_modul_siswa)); ?>"
                                                                    data-delete-action="<?php echo e(route('pembimbing.modulSiswa.delete', $ms->id_modul_siswa)); ?>"
                                                                    title="Edit / Revisi Penugasan Modul">
                                                                    <i class="fas fa-pencil-alt"></i> Revisi
                                                                </button>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted border">Belum ada modul</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if($siswa->absen_hari_ini): ?>
                                                <span class="status-badge status-hadir text-success"><i class="fas fa-check-circle"></i> Hadir</span>
                                            <?php else: ?>
                                                <span class="status-badge status-absen text-danger"><i class="fas fa-times-circle"></i> Belum Absen</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button class="btn-small btn-preview-pdf"
                                                    style="background: rgba(15, 23, 42, 0.04); color: #64748b;"
                                                    data-bs-toggle="modal" data-bs-target="#modalDetailSiswa"
                                                    data-nisn="<?php echo e($siswa->nisn); ?>" data-nama="<?php echo e($siswa->nama); ?>"
                                                    data-email="<?php echo e($siswa->email); ?>"
                                                    data-no_hp="<?php echo e($siswa->no_hp); ?>"
                                                    data-jk="<?php echo e($siswa->jenis_kelamin); ?>"
                                                    data-kelas="<?php echo e($siswa->kelas); ?>"
                                                    data-jurusan="<?php echo e($siswa->jurusan); ?>"
                                                    data-sekolah="<?php echo e($siswa->sekolah); ?>"
                                                    data-npsn="<?php echo e($siswa->npsn); ?>"
                                                    data-perusahaan="<?php echo e($siswa->perusahaan); ?>"
                                                    data-tipe_magang="<?php echo e($siswa->tipe_magang); ?>"
                                                    data-nisn_ketua="<?php echo e($siswa->nisn_ketua); ?>"
                                                    data-surat_balasan="<?php echo e($siswa->surat_balasan); ?>"
                                                    data-tahun_ajaran="<?php echo e($siswa->tahunAjaran->tahun_ajaran ?? '-'); ?>"
                                                    data-mulai="<?php echo e($siswa->tgl_mulai_magang ? \Carbon\Carbon::parse($siswa->tgl_mulai_magang)->format('d M Y') : '-'); ?>"
                                                    data-selesai="<?php echo e($siswa->tgl_selesai_magang ? \Carbon\Carbon::parse($siswa->tgl_selesai_magang)->format('d M Y') : '-'); ?>"
                                                    data-guru-nama="<?php echo e($siswa->guru->nama ?? '-'); ?>"
                                                    data-guru-nip="<?php echo e($siswa->guru->id_guru ?? '-'); ?>"
                                                    data-guru-hp="<?php echo e($siswa->guru->no_hp ?? '-'); ?>"
                                                    data-pl-nama="<?php echo e($siswa->pembimbing->nama ?? '-'); ?>"
                                                    data-pl-nip="<?php echo e($siswa->id_pembimbing ?? '-'); ?>"
                                                    data-pl-hp="<?php echo e($siswa->pembimbing->no_telp ?? '-'); ?>"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-id-card"></i>
                                                </button>
                                                <a href="<?php echo e(route('pembimbing.logbook', $siswa->nisn)); ?>" class="btn-small btn-preview-pdf"
                                                    style="background: var(--primary-light); color: var(--primary);"
                                                    title="Logbook">
                                                    <i class="fas fa-book"></i>
                                                </a>
                                                <a href="<?php echo e(route('pembimbing.absensi', $siswa->nisn)); ?>" class="btn-small btn-preview-pdf"
                                                    style="background: var(--warning-light); color: #92400e;"
                                                    title="Absensi">
                                                    <i class="fas fa-calendar-check"></i>
                                                </a>
                                                <button class="btn-small btn-preview-pdf btn-assign-modul"
                                                    data-bs-toggle="modal" data-bs-target="#modalAssignModul"
                                                    data-nisn="<?php echo e($siswa->nisn); ?>"
                                                    data-nama="<?php echo e($siswa->nama); ?>"
                                                    data-assigned="<?php echo e($siswa->modulSiswas ? $siswa->modulSiswas->pluck('id_modul')->toJson() : '[]'); ?>"
                                                    data-modul-dates="<?php echo e($siswa->modulSiswas ? $siswa->modulSiswas->mapWithKeys(fn($ms) => [$ms->id_modul => ['tgl_mulai' => $ms->tgl_mulai, 'tgl_selesai' => $ms->tgl_selesai]])->toJson() : '{}'); ?>"
                                                    style="background: #e0f2fe; color: #0369a1;"
                                                    title="Tugaskan Modul Pembelajaran">
                                                    <i class="fas fa-book-reader"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr class="empty-row">
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-users-slash empty-icon"></i>
                                                <h4>Belum Ada Siswa</h4>
                                                <p class="text-muted">Tidak ditemukan siswa bimbingan yang aktif.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr id="noResultsActive" style="display: none;">
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-search empty-icon"></i>
                                            <h4>Tidak Ditemukan</h4>
                                            <p class="text-muted">Tidak ada siswa yang cocok dengan pencarian di tab ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane-content" id="history-students" style="display: none;">

                
                <div class="history-filter-bar">
                    <div class="filter-label">
                        <i class="fas fa-filter"></i>
                        <span>Filter Periode:</span>
                    </div>
                    <form id="periodeFilterForm" method="GET" action="<?php echo e(route('pembimbing.siswa')); ?>" class="filter-form">
                        <?php if($search): ?>
                            <input type="hidden" name="search" value="<?php echo e($search); ?>">
                        <?php endif; ?>
                        
                        <input type="hidden" name="tab" value="history">
                        <select name="periode" id="periodeSelect" class="filter-select" onchange="document.getElementById('periodeFilterForm').submit()">
                            <option value="">-- Semua Periode --</option>
                            <?php $__currentLoopData = $periodeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($opt->id_tahun_ajaran); ?>"
                                    <?php echo e($periodeId == $opt->id_tahun_ajaran ? 'selected' : ''); ?>>
                                    <?php echo e($opt->tahun_ajaran); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if($periodeId): ?>
                            <a href="<?php echo e(route('pembimbing.siswa', array_filter(['search' => $search, 'tab' => 'history']))); ?>"
                               class="btn-reset-filter" title="Hapus Filter">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        <?php endif; ?>
                    </form>
                    <?php if($periodeId): ?>
                        <?php $selectedPeriode = $periodeOptions->firstWhere('id_tahun_ajaran', $periodeId); ?>
                        <?php if($selectedPeriode): ?>
                            <span class="filter-active-badge">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo e($selectedPeriode->tahun_ajaran); ?>

                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="content-card">
                    <div class="custom-table-wrapper">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="28%">Info Siswa</th>
                                    <th width="20%">Sekolah / Perusahaan</th>
                                    <th width="17%" class="text-center">Periode Magang</th>
                                    <th width="12%" class="text-center">Rata-rata Nilai</th>
                                    <th width="18%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                <?php $__empty_1 = true; $__currentLoopData = $siswasHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $penilaian = $siswa->penilaians->where('pemberi_nilai', 'Dosen Pembimbing')->first();
                                    ?>
                                    <tr class="student-row">
                                        <td class="text-center"><?php echo e($index + 1); ?></td>
                                        <td>
                                            <div class="user-info-cell">
                                                <?php if($siswa->foto_profil): ?>
                                                    <img src="<?php echo e(asset('storage/' . $siswa->foto_profil)); ?>" alt="<?php echo e($siswa->nama); ?>" class="avatar-sm">
                                                <?php else: ?>
                                                    <div class="avatar-sm-placeholder bg-success-light text-success">
                                                        <?php echo e(strtoupper(substr($siswa->nama, 0, 1))); ?>

                                                    </div>
                                                <?php endif; ?>
                                                <div class="user-details">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="fw-bold text-dark"><?php echo e($siswa->nama); ?></span>
                                                        <span class="badge bg-secondary text-white" style="font-size: 0.6rem; padding: 1px 6px; border-radius: 50px;">SELESAI</span>
                                                    </div>
                                                    <span class="text-muted small">NISN: <?php echo e($siswa->nisn); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <span class="badge-role bg-purple-light text-purple"><i class="fas fa-school me-1"></i> <?php echo e($siswa->sekolah); ?></span>
                                                <span class="small text-muted"><i class="fas fa-building me-1"></i> <?php echo e($siswa->perusahaan); ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if($siswa->tahunAjaran): ?>
                                                <span class="periode-badge">
                                                    <i class="fas fa-calendar-check me-1"></i>
                                                    <?php echo e($siswa->tahunAjaran->tahun_ajaran); ?>

                                                </span>
                                            <?php elseif($siswa->tgl_mulai_magang && $siswa->tgl_selesai_magang): ?>
                                                <span class="periode-badge periode-badge-plain">
                                                    <?php echo e(\Carbon\Carbon::parse($siswa->tgl_mulai_magang)->format('M Y')); ?>

                                                    &ndash;
                                                    <?php echo e(\Carbon\Carbon::parse($siswa->tgl_selesai_magang)->format('M Y')); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if($penilaian): ?>
                                                <span class="eval-badge bg-purple-light text-purple"><?php echo e(number_format($penilaian->rata_rata, 1)); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2 mb-1">
                                                <button class="btn-small btn-preview-pdf"
                                                    style="background: rgba(15, 23, 42, 0.04); color: #64748b;"
                                                    data-bs-toggle="modal" data-bs-target="#modalDetailSiswa"
                                                    data-nisn="<?php echo e($siswa->nisn); ?>"
                                                    data-nama="<?php echo e($siswa->nama); ?>"
                                                    data-email="<?php echo e($siswa->email); ?>"
                                                    data-no_hp="<?php echo e($siswa->no_hp); ?>"
                                                    data-jk="<?php echo e($siswa->jenis_kelamin); ?>"
                                                    data-kelas="<?php echo e($siswa->kelas); ?>"
                                                    data-jurusan="<?php echo e($siswa->jurusan); ?>"
                                                    data-sekolah="<?php echo e($siswa->sekolah); ?>"
                                                    data-npsn="<?php echo e($siswa->npsn); ?>"
                                                    data-perusahaan="<?php echo e($siswa->perusahaan); ?>"
                                                    data-tipe_magang="<?php echo e($siswa->tipe_magang); ?>"
                                                    data-nisn_ketua="<?php echo e($siswa->nisn_ketua); ?>"
                                                    data-surat_balasan="<?php echo e($siswa->surat_balasan); ?>"
                                                    data-tahun_ajaran="<?php echo e($siswa->tahunAjaran->tahun_ajaran ?? '-'); ?>"
                                                    data-mulai="<?php echo e($siswa->tgl_mulai_magang ? \Carbon\Carbon::parse($siswa->tgl_mulai_magang)->format('d M Y') : '-'); ?>"
                                                    data-selesai="<?php echo e($siswa->tgl_selesai_magang ? \Carbon\Carbon::parse($siswa->tgl_selesai_magang)->format('d M Y') : '-'); ?>"
                                                    data-guru-nama="<?php echo e($siswa->guru->nama ?? '-'); ?>"
                                                    data-guru-nip="<?php echo e($siswa->guru->id_guru ?? '-'); ?>"
                                                    data-guru-hp="<?php echo e($siswa->guru->no_hp ?? '-'); ?>"
                                                    data-pl-nama="<?php echo e($siswa->pembimbing->nama ?? '-'); ?>"
                                                    data-pl-nip="<?php echo e($siswa->id_pembimbing ?? '-'); ?>"
                                                    data-pl-hp="<?php echo e($siswa->pembimbing->no_telp ?? '-'); ?>"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-id-card"></i>
                                                </button>
                                                <button class="btn-small btn-preview-pdf" title="Cetak Jurnal"
                                                    data-url="<?php echo e(route('pembimbing.siswa.cetakJurnal', $siswa->nisn)); ?>"
                                                    style= "padding: 6px 16px 6px 16px;">
                                                    <i class="fas fa-book"></i>
                                                </button>
                                                <button class="btn-small btn-preview-pdf" title="Cetak Absensi"
                                                    data-url="<?php echo e(route('pembimbing.siswa.cetakAbsensi', $siswa->nisn)); ?>">
                                                    <i class="fas fa-file-signature"></i>
                                                </button>
                                            </div>
                                            <div class="d-flex justify-content-end gap-2">
                                                
                                                
                                                <button class="btn-small btn-preview-pdf" title="Laporan Akhir Siswa"
                                                    data-url="<?php echo e(route('pembimbing.siswa.cetakLaporan', $siswa->nisn)); ?>">
                                                    <i class="fas fa-file-pdf"></i>
                                                </button>
                                                <button class="btn-small btn-preview-pdf" title="Sertifikat Magang"
                                                    data-url="<?php echo e(route('pembimbing.siswa.cetakSertifikat', $siswa->nisn)); ?>">
                                                    <i class="fas fa-certificate"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr class="empty-row">
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-history empty-icon"></i>
                                                <h4>Belum Ada Riwayat</h4>
                                                <p class="text-muted">
                                                    <?php if($periodeId): ?>
                                                        Tidak ada siswa yang menyelesaikan magang pada periode ini.
                                                    <?php else: ?>
                                                        Belum ada siswa yang menyelesaikan bimbingan/penilaian.
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr id="noResultsHistory" style="display: none;">
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-search empty-icon"></i>
                                            <h4>Tidak Ditemukan</h4>
                                            <p class="text-muted">Tidak ada siswa yang cocok dengan pencarian di tab ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="modalDetailSiswa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-content-premium">
                <div class="modal-header modal-header-premium border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-premium">
                    
                    <div class="detail-header-card shadow">
                        <div class="d-flex align-items-center gap-4 flex-wrap flex-md-nowrap">
                            <div class="avatar-container-premium">
                                <div class="avatar-lg-placeholder bg-white text-primary" id="det_avatar_placeholder">
                                    S
                                </div>
                                <img id="det_avatar_img" src="" alt="Profile" class="avatar-img-premium" style="display: none;">
                            </div>
                            <div class="student-main-info">
                                <div class="text-uppercase fw-bold text-white-50 small mb-1" style="letter-spacing: 2px;">Data Profil Siswa</div>
                                <h4 class="text-white" id="det_name">-</h4>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="badge-nisn-premium" id="det_nisn">-</span>
                                    <span class="badge bg-white text-primary rounded-pill px-3 fw-bold" id="det_jk" style="font-size: 0.7rem;">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        
                        <div class="col-lg-6">
                            <div class="detail-section-block shadow-sm">
                                <div class="detail-section-title">
                                    <i class="fas fa-user"></i> Data Personal & Kontak
                                </div>
                                <div class="row g-3">
                                    <div class="col-12 detail-grid-item">
                                        <label class="detail-label">Alamat Email Resmi</label>
                                        <span class="detail-value text-break" id="det_email">-</span>
                                    </div>
                                    <div class="col-6 detail-grid-item">
                                        <label class="detail-label">No. WhatsApp</label>
                                        <span class="detail-value" id="det_hp">-</span>
                                    </div>
                                    <div class="col-6 detail-grid-item">
                                        <label class="detail-label">Kelas / Jurusan</label>
                                        <span class="detail-value" id="det_kelas_jurusan">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-lg-6">
                            <div class="detail-section-block shadow-sm">
                                <div class="detail-section-title">
                                    <i class="fas fa-graduation-cap"></i> Instansi & Akademik
                                </div>
                                <div class="row g-3">
                                    <div class="col-12 detail-grid-item">
                                        <label class="detail-label">Asal Sekolah</label>
                                        <span class="detail-value" id="det_sekolah">-</span>
                                        <div class="small text-muted mt-1">NPSN: <span id="det_npsn">-</span></div>
                                    </div>
                                    <div class="col-12 detail-grid-item">
                                        <label class="detail-label">Tahun Ajaran</label>
                                        <span class="detail-value text-info" id="det_tahun_ajaran">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-lg-7">
                            <div class="detail-section-block shadow-sm">
                                <div class="detail-section-title">
                                    <i class="fas fa-building"></i> Penempatan & Tipe
                                </div>
                                <div class="row g-3">
                                    <div class="col-12 detail-grid-item">
                                        <label class="detail-label">Instansi / Lokasi Magang</label>
                                        <span class="detail-value" id="det_perusahaan">-</span>
                                    </div>
                                    <div class="col-6 detail-grid-item">
                                        <label class="detail-label">Tipe Magang</label>
                                        <span class="detail-value" id="det_tipe_magang">-</span>
                                    </div>
                                    <div class="col-6 detail-grid-item">
                                        <label class="detail-label">NISN Ketua (Jika Kelompok)</label>
                                        <span class="detail-value" id="det_nisn_ketua">-</span>
                                    </div>
                                    <div class="col-12 detail-grid-item">
                                        <label class="detail-label">Durasi Waktu Magang</label>
                                        <span class="detail-value text-primary" id="det_periode">-</span>
                                    </div>
                                    <div class="col-12 detail-grid-item">
                                        <label class="detail-label">Surat Balasan / Referensi</label>
                                        <div id="det_surat_balasan_wrapper" class="mt-1 d-flex align-items-center gap-3">
                                            <span class="detail-value text-muted italic" id="det_surat_balasan">Belum diunggah</span>
                                            <button class="btn btn-sm btn-outline-primary rounded-pill d-none" id="btn_view_surat">
                                                <i class="fas fa-file-invoice me-1"></i> Preview
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-lg-5">
                            <div class="detail-section-block shadow-sm">
                                <div class="detail-section-title">
                                    <i class="fas fa-chalkboard-teacher"></i> Tim Pembimbing
                                </div>
                                <div class="d-flex flex-column gap-3 mt-2">
                                    <div class="p-3 rounded-4 border bg-white shadow-sm">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-box-small bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-school"></i>
                                            </div>
                                            <div>
                                                <div class="text-uppercase fw-bold text-primary" style="font-size: 0.6rem; letter-spacing: 1px;">Guru Pembimbing</div>
                                                <h6 class="mb-0 fw-bold text-dark" id="det_guru_nama">-</h6>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                                            <small class="text-muted" id="det_guru_nip">-</small>
                                            <a href="#" id="det_guru_wa_btn" target="_blank" class="btn btn-xs btn-success rounded-pill px-3 py-1 fw-bold d-none" style="font-size: 0.7rem;">
                                                <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                            </a>
                                        </div>
                                    </div>
                                    <div class="p-3 rounded-4 border bg-white shadow-sm">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-box-small bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div>
                                                <div class="text-uppercase fw-bold text-info" style="font-size: 0.6rem; letter-spacing: 1px;">Pembimbing Lapangan</div>
                                                <h6 class="mb-0 fw-bold text-dark" id="det_pl_nama">-</h6>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                                            <small class="text-muted" id="det_pl_nip">-</small>
                                            <a href="#" id="det_pl_wa_btn" target="_blank" class="btn btn-xs btn-success rounded-pill px-3 py-1 fw-bold d-none" style="font-size: 0.7rem;">
                                                <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 bg-light d-flex justify-content-center">
                    <button type="button" class="btn btn-dark rounded-pill px-5 py-2 fw-bold shadow" data-bs-dismiss="modal">
                        Tutup Informasi
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modalAssignModul" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header bg-primary text-white p-3 px-4" style="border-radius: 20px 20px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-book-reader fs-5"></i>
                        <h6 class="modal-title fw-bold mb-0">Tugaskan Modul Pembelajaran</h6>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formAssignModul" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <span class="text-muted small">Siswa:</span>
                            <h6 class="fw-bold text-dark mb-0" id="assign_siswa_nama">-</h6>
                        </div>
                        <p class="text-muted small mb-3">Pilih modul pembelajaran yang harus dipelajari dan diselesaikan oleh siswa ini:</p>
                        
                        <div class="d-flex flex-column gap-2 mb-3">
                            <?php $__empty_1 = true; $__currentLoopData = $daftarModul; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <label class="border p-3 rounded-4 d-flex align-items-center gap-3 cursor-pointer modul-option-label" data-modul-id="<?php echo e($mod->id_modul); ?>" style="background: #f8fafc;">
                                    <input type="checkbox" name="id_modul[]" value="<?php echo e($mod->id_modul); ?>" class="form-check-input modul-checkbox" style="width: 20px; height: 20px;">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark"><?php echo e($mod->judul); ?></div>
                                        <small class="text-muted"><i class="fas fa-stopwatch me-1"></i> <?php echo e($mod->durasi_kuis); ?> Menit | <?php echo e($mod->soals->count()); ?> Soal</small>
                                    </div>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="alert alert-warning rounded-4 small mb-0">
                                    <i class="fas fa-exclamation-circle me-1"></i> Anda belum memiliki modul pembelajaran. Silakan buat modul terlebih dahulu pada menu <a href="<?php echo e(route('pembimbing.modul.create')); ?>" class="fw-bold">Modul Pembelajaran</a>.
                                </div>
                            <?php endif; ?>

                            <div id="assignModulAllAssignedAlert" class="alert alert-info rounded-4 small mb-0 d-none">
                                <i class="fas fa-check-circle me-1"></i> Semua modul pembelajaran yang Anda miliki sudah ditugaskan kepada siswa ini. Anda dapat mengedit / merevisi modul yang sudah ditugaskan langsung pada tabel daftar siswa.
                            </div>
                        </div>

                        
                        <div id="dateRangeFields" class="d-none">
                            <hr class="my-2">
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Tanggal Mulai Akses Modul</label>
                                <input type="date" name="tgl_mulai" class="form-control form-control-sm rounded-3">
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold small">Tanggal Selesai Akses Modul</label>
                                <input type="date" name="tgl_selesai" class="form-control form-control-sm rounded-3">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 px-4 bg-light" style="border-radius: 0 0 20px 20px;">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" id="btnSubmitAssignModul" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">Simpan Penugasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="modalEditModulSiswa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header bg-warning text-dark p-3 px-4" style="border-radius: 20px 20px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-edit fs-5"></i>
                        <h6 class="modal-title fw-bold mb-0">Revisi Penugasan Modul Siswa</h6>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditModulSiswa" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body p-4">
                        <div class="mb-3 p-3 bg-light rounded-4 border">
                            <div class="small text-muted mb-1">Siswa: <strong class="text-dark" id="edit_modul_siswa_nama">-</strong></div>
                            <div class="small text-muted">Modul: <strong class="text-primary" id="edit_modul_judul">-</strong></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Tanggal Mulai Akses Modul <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_mulai" id="edit_modul_tgl_mulai" class="form-control rounded-3" required>
                            <small class="text-muted">Siswa baru dapat membuka modul pada tanggal ini.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Tanggal Selesai Akses Modul <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_selesai" id="edit_modul_tgl_selesai" class="form-control rounded-3" required>
                            <small class="text-muted">Setelah tanggal ini, siswa tidak dapat lagi mengerjakan modul.</small>
                        </div>

                        <div id="wrapperResetKuis" class="mb-3 p-3 rounded-4 border bg-warning-subtle d-none">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="reset_kuis" value="1" id="checkResetKuis">
                                <label class="form-check-label fw-bold small text-dark" for="checkResetKuis">
                                    <i class="fas fa-redo me-1"></i> Berikan Kesempatan Mengulang (Reset Nilai & Kuis)
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1">Siswa sudah menyelesaikan kuis dengan nilai: <strong id="edit_modul_nilai_label">-</strong>. Centang opsi ini jika Anda ingin siswa mengulang kuis sebagai revisi.</small>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Ingin membatalkan modul ini?</span>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" id="btnHapusPenugasanModul">
                                <i class="fas fa-trash me-1"></i> Hapus Penugasan
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 px-4 bg-light" style="border-radius: 0 0 20px 20px;">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 shadow-sm fw-bold">Simpan Revisi</button>
                    </div>
                </form>

                
                <form id="formDeleteModulSiswa" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade preview-pdf-modal" id="previewPdfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-pdf-viewer modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="pdf-viewer-header">
                    <div class="pdf-viewer-title">
                        <div class="pdf-icon-wrapper">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <h6 class="modal-title">Preview Laporan</h6>
                    </div>

                    <div class="pdf-viewer-actions">
                        <div class="pdf-desktop-actions">
                            <a id="downloadPdfBtn" href="#" class="btn-pdf-action" title="Unduh File" download>
                                <i class="fas fa-download"></i> <span>Unduh Laporan</span>
                            </a>
                        </div>
                        <div class="vr opacity-10"></div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body pdf-viewer-body">
                    <div id="pdfCanvasContainer">
                        <div id="pdfLoadingIndicator">
                            <div class="loader-logo-container">
                                <img src="<?php echo e(asset('images/loogo.png')); ?>" alt="BPS">
                            </div>
                            
                        </div>

                        <div id="pdfErrorMsg" class="d-none">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                            <p>Gagal memuat file PDF.<br><small>Coba gunakan tombol Unduh.</small></p>
                        </div>
                    </div>
                </div
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
        <script src="<?php echo e(asset('assets/js/pembimbing/daftarSiswa.js')); ?>"></script>
        <script>
            // Fungsi untuk toggle field tanggal berdasarkan checkbox
            function toggleDateFields() {
                const anyChecked = Array.from(document.querySelectorAll('.modul-checkbox')).some(cb => cb.checked);
                const dateFields = document.getElementById('dateRangeFields');
                if (anyChecked) {
                    dateFields.classList.remove('d-none');
                    dateFields.querySelectorAll('input[type="date"]').forEach(inp => inp.setAttribute('required', 'required'));
                } else {
                    dateFields.classList.add('d-none');
                    dateFields.querySelectorAll('input[type="date"]').forEach(inp => inp.removeAttribute('required'));
                }
            }

            document.querySelectorAll('.btn-assign-modul').forEach(btn => {
                btn.addEventListener('click', function() {
                    const nisn     = this.dataset.nisn;
                    const nama     = this.dataset.nama;
                    const assigned = JSON.parse(this.dataset.assigned || '[]');

                    document.getElementById('assign_siswa_nama').textContent = nama + ' (' + nisn + ')';
                    document.getElementById('formAssignModul').action = '/pembimbing/siswa/' + nisn + '/assign-modul';

                    let availableCount = 0;
                    document.querySelectorAll('.modul-option-label').forEach(label => {
                        const modId = parseInt(label.dataset.modulId);
                        const cb = label.querySelector('.modul-checkbox');
                        cb.checked = false;
                        if (assigned.includes(modId)) {
                            // Modul yang sudah ditambahkan sebelumnya TIDAK ADA DISITU LAGI
                            label.classList.add('d-none');
                        } else {
                            label.classList.remove('d-none');
                            availableCount++;
                        }
                    });

                    const emptyAlert = document.getElementById('assignModulAllAssignedAlert');
                    const submitBtn  = document.getElementById('btnSubmitAssignModul');
                    if (availableCount === 0) {
                        if (emptyAlert) emptyAlert.classList.remove('d-none');
                        if (submitBtn) submitBtn.classList.add('d-none');
                    } else {
                        if (emptyAlert) emptyAlert.classList.add('d-none');
                        if (submitBtn) submitBtn.classList.remove('d-none');
                    }

                    // Reset field tanggal saat modal dibuka
                    const tglMulaiInput   = document.querySelector('#dateRangeFields input[name="tgl_mulai"]');
                    const tglSelesaiInput = document.querySelector('#dateRangeFields input[name="tgl_selesai"]');
                    if (tglMulaiInput)   tglMulaiInput.value   = '';
                    if (tglSelesaiInput) tglSelesaiInput.value = '';

                    toggleDateFields();
                });
            });

            // Listener untuk tombol revisi modul siswa
            document.querySelectorAll('.btn-edit-modul-siswa').forEach(btn => {
                btn.addEventListener('click', function() {
                    const judul        = this.dataset.judul;
                    const siswa        = this.dataset.siswa;
                    const mulai        = this.dataset.mulai;
                    const selesai      = this.dataset.selesai;
                    const selesaiKuis  = this.dataset.selesaiKuis;
                    const nilai        = this.dataset.nilai;
                    const updateAction = this.dataset.updateAction;
                    const deleteAction = this.dataset.deleteAction;

                    document.getElementById('edit_modul_siswa_nama').textContent = siswa;
                    document.getElementById('edit_modul_judul').textContent = judul;
                    document.getElementById('edit_modul_tgl_mulai').value = mulai;
                    document.getElementById('edit_modul_tgl_selesai').value = selesai;

                    const wrapperReset = document.getElementById('wrapperResetKuis');
                    const checkReset   = document.getElementById('checkResetKuis');
                    if (checkReset) checkReset.checked = false;

                    if (selesaiKuis === '1') {
                        if (wrapperReset) wrapperReset.classList.remove('d-none');
                        const nilaiEl = document.getElementById('edit_modul_nilai_label');
                        if (nilaiEl) nilaiEl.textContent = nilai;
                    } else {
                        if (wrapperReset) wrapperReset.classList.add('d-none');
                    }

                    document.getElementById('formEditModulSiswa').action = updateAction;
                    document.getElementById('formDeleteModulSiswa').action = deleteAction;
                });
            });

            const btnHapus = document.getElementById('btnHapusPenugasanModul');
            if (btnHapus) {
                btnHapus.addEventListener('click', function() {
                    if (confirm('Apakah Anda yakin ingin membatalkan / menghapus modul ini dari siswa?')) {
                        document.getElementById('formDeleteModulSiswa').submit();
                    }
                });
            }

            // Listener untuk setiap checkbox modul
            document.querySelectorAll('.modul-checkbox').forEach(cb => {
                cb.addEventListener('change', toggleDateFields);
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.nav.pembimbing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\SiswaMagang\resources\views/pembimbing/daftarSiswa.blade.php ENDPATH**/ ?>