<?php $__env->startSection('title', 'Dashboard Dosen - Monitoring Siswa'); ?>
<?php $__env->startSection('body-class', 'dosen-dashboard-page'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/dosen/style-dosen.css')); ?>">
    <style>
        .progress-card-stat {
            background: #ffffff;
            border-radius: 18px;
            padding: 22px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .progress-card-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        }
        .progress-circle-wrapper {
            position: relative;
            width: 76px;
            height: 76px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            flex-shrink: 0;
        }
        .radial-progress-circle {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            display: grid;
            place-items: center;
        }
        .radial-progress-inner {
            width: 60px;
            height: 60px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 800;
            color: #1e293b;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }
        .bar-gradient-blue {
            background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
        }
        .bar-gradient-green {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        }
        .bar-gradient-amber {
            background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
        }
        .bar-gradient-purple {
            background: linear-gradient(90deg, #8b5cf6 0%, #6d28d9 100%);
        }
        .student-progress-row {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 14px;
            margin-bottom: 10px;
            transition: all 0.2s ease;
        }
        .student-progress-row:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        .chart-box-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            height: 100%;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?php echo e(asset('assets/js/pembimbing/home-pembimbing.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: Donut Modul & Status
            const ctxDonut = document.getElementById('chartModulDonut');
            if (ctxDonut) {
                new Chart(ctxDonut, {
                    type: 'doughnut',
                    data: {
                        labels: ['Modul Selesai', 'Belum Selesai', 'Logbook Pending'],
                        datasets: [{
                            data: [
                                <?php echo e($stats['modul_selesai'] ?? 0); ?>,
                                <?php echo e($stats['modul_belum_selesai'] ?? 0); ?>,
                                <?php echo e($stats['logbook_pending'] ?? 0); ?>

                            ],
                            backgroundColor: ['#10b981', '#cbd5e1', '#f59e0b'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 15,
                                    font: { size: 12, weight: '600' }
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
            }

            // Chart 2: Bar Tren Kehadiran
            const ctxBar = document.getElementById('chartKehadiranBar');
            if (ctxBar) {
                const labels = <?php echo json_encode(array_column($stats['grafik_absensi'] ?? [], 'label'), 512) ?>;
                const hadirData = <?php echo json_encode(array_column($stats['grafik_absensi'] ?? [], 'hadir'), 512) ?>;
                const izinData = <?php echo json_encode(array_column($stats['grafik_absensi'] ?? [], 'izin'), 512) ?>;

                new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Hadir / Terlambat',
                                data: hadirData,
                                backgroundColor: '#3b82f6',
                                borderRadius: 6
                            },
                            {
                                label: 'Izin / Sakit',
                                data: izinData,
                                backgroundColor: '#f59e0b',
                                borderRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 15,
                                    font: { size: 12, weight: '600' }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, precision: 0 },
                                grid: { color: '#f1f5f9' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('body'); ?>

    <div class="body-style">

        <div class="container">
            <!-- Hero Greeting -->
            <div class="welcome-banner">
                <div class="welcome-text d-flex align-items-center gap-4">
                    <div class="welcome-avatar">
                        <img src="<?php echo e($user->foto_profil ? asset('storage/' . $user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama) . '&background=f6c23e&color=fff'); ?>" alt="Profile" class="rounded-circle" width="80" height="80" style="object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    </div>
                    <div>
                        <h1 class="welcome-title">Halo, <?php echo e($user->nama); ?>! 👋</h1>
                        <p class="welcome-subtitle">Selamat datang kembali di Website Magang BPS Kabupaten Muara Enim.</p>
                    </div>
                </div>
                <div class="welcome-status">
                    <span class="status-indicator-light">
                        <i class="fas fa-circle"></i> Sesi Aktif
                    </span>
                </div>
            </div>

            <!-- GRAFIS PROGRES & MONITORING BIMBINGAN -->
            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                            <i class="fas fa-chart-line text-warning"></i> Progres & Monitoring Siswa Bimbingan
                        </h4>
                        <p class="text-muted small mb-0">Pantau kemajuan magang, modul pembelajaran, dan aktivitas presensi siswa secara real-time.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('pembimbing.siswa')); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                            <i class="fas fa-users me-1"></i> Daftar Siswa
                        </a>
                        <a href="<?php echo e(route('pembimbing.modul.index')); ?>" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-semibold shadow-sm">
                            <i class="fas fa-book-reader me-1"></i> Kelola Modul
                        </a>
                    </div>
                </div>

                <!-- 4 KPI Progres Cards -->
                <div class="row g-3 mb-4">
                    <!-- KPI 1: Rata-rata Progres Magang Siswa -->
                    <div class="col-xl-3 col-md-6">
                        <div class="progress-card-stat h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="text-muted fw-bold small text-uppercase letter-spacing-1">Progres Magang</span>
                                    <h2 class="fw-black mb-0 text-dark mt-1"><?php echo e($stats['avg_progress']); ?>%</h2>
                                </div>
                                <div class="radial-progress-circle" style="background: conic-gradient(#3b82f6 <?php echo e($stats['avg_progress'] * 3.6); ?>deg, #e2e8f0 0deg);">
                                    <div class="radial-progress-inner">
                                        <i class="fas fa-user-graduate text-primary"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="progress rounded-pill mb-2" style="height: 7px; background-color: #f1f5f9;">
                                <div class="progress-bar bar-gradient-blue rounded-pill" role="progressbar" style="width: <?php echo e($stats['avg_progress']); ?>%;" aria-valuenow="<?php echo e($stats['avg_progress']); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span><strong class="text-dark"><?php echo e($stats['siswa_aktif']); ?></strong> Siswa Aktif</span>
                                <span><strong class="text-success"><?php echo e($stats['siswa_selesai']); ?></strong> Selesai</span>
                            </div>
                        </div>
                    </div>

                    <!-- KPI 2: Modul & Kuis Pembelajaran -->
                    <div class="col-xl-3 col-md-6">
                        <div class="progress-card-stat h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="text-muted fw-bold small text-uppercase letter-spacing-1">Modul & Kuis</span>
                                    <h2 class="fw-black mb-0 text-dark mt-1"><?php echo e($stats['modul_progress_rate']); ?>%</h2>
                                </div>
                                <div class="radial-progress-circle" style="background: conic-gradient(#10b981 <?php echo e($stats['modul_progress_rate'] * 3.6); ?>deg, #e2e8f0 0deg);">
                                    <div class="radial-progress-inner">
                                        <i class="fas fa-tasks text-success"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="progress rounded-pill mb-2" style="height: 7px; background-color: #f1f5f9;">
                                <div class="progress-bar bar-gradient-green rounded-pill" role="progressbar" style="width: <?php echo e($stats['modul_progress_rate']); ?>%;" aria-valuenow="<?php echo e($stats['modul_progress_rate']); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span><strong class="text-dark"><?php echo e($stats['modul_selesai']); ?>/<?php echo e($stats['total_modul_ditugaskan']); ?></strong> Selesai</span>
                                <span>Nilai: <strong class="text-primary"><?php echo e($stats['avg_nilai_kuis']); ?></strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- KPI 3: Validasi Logbook -->
                    <div class="col-xl-3 col-md-6">
                        <div class="progress-card-stat h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="text-muted fw-bold small text-uppercase letter-spacing-1">Logbook Siswa</span>
                                    <h2 class="fw-black mb-0 text-dark mt-1"><?php echo e($stats['logbook_pending']); ?></h2>
                                </div>
                                <div class="icon-box-stat" style="width: 50px; height: 50px; border-radius: 14px; background: rgba(245, 158, 11, 0.12); display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 22px;">
                                    <i class="fas fa-book-open"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill small">
                                    <i class="fas fa-clock me-1"></i> <?php echo e($stats['logbook_pending']); ?> Perlu Validasi
                                </span>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Total Terverifikasi</span>
                                <span class="fw-bold text-success"><?php echo e($stats['logbook_verified']); ?> Logbook</span>
                            </div>
                        </div>
                    </div>

                    <!-- KPI 4: Presensi Hari Ini -->
                    <div class="col-xl-3 col-md-6">
                        <div class="progress-card-stat h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="text-muted fw-bold small text-uppercase letter-spacing-1">Presensi Hari Ini</span>
                                    <h2 class="fw-black mb-0 text-dark mt-1"><?php echo e($stats['hadir_hari_ini']); ?> <span class="fs-6 fw-normal text-muted">/ <?php echo e($stats['siswa_aktif']); ?></span></h2>
                                </div>
                                <div class="icon-box-stat" style="width: 50px; height: 50px; border-radius: 14px; background: rgba(59, 130, 246, 0.12); display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 22px;">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <?php if($stats['absensi_pending'] > 0): ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill small">
                                        <i class="fas fa-exclamation-circle me-1"></i> <?php echo e($stats['absensi_pending']); ?> Menunggu Approval
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                                        <i class="fas fa-check-circle me-1"></i> Seluruh Presensi Terverifikasi
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Kehadiran Hari Ini</span>
                                <span class="fw-bold text-primary"><?php echo e($stats['siswa_aktif'] > 0 ? round(($stats['hadir_hari_ini'] / $stats['siswa_aktif']) * 100) : 0); ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafis Visual (Charts & Progres List) -->
                <div class="row g-4 mb-4">
                    <!-- Chart 1: Donut Diagram Progres Modul & Logbook -->
                    <div class="col-lg-5">
                        <div class="chart-box-card">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="fas fa-chart-pie text-primary me-2"></i> Distribusi Modul & Kegiatan
                                </h6>
                                <span class="badge bg-light text-muted border px-2 py-1">Real-time</span>
                            </div>
                            <div style="height: 240px; position: relative;">
                                <canvas id="chartModulDonut"></canvas>
                            </div>
                            <div class="row g-2 mt-2 pt-2 border-top text-center small text-muted">
                                <div class="col-4">
                                    <span class="d-block fw-bold text-success"><?php echo e($stats['modul_selesai']); ?></span>
                                    <span>Modul Selesai</span>
                                </div>
                                <div class="col-4">
                                    <span class="d-block fw-bold text-secondary"><?php echo e($stats['modul_belum_selesai']); ?></span>
                                    <span>Belum Selesai</span>
                                </div>
                                <div class="col-4">
                                    <span class="d-block fw-bold text-warning"><?php echo e($stats['logbook_pending']); ?></span>
                                    <span>Logbook Pending</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 2: Batang Tren Presensi 7 Hari Terakhir -->
                    <div class="col-lg-7">
                        <div class="chart-box-card">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="fas fa-chart-bar text-success me-2"></i> Tren Presensi Siswa (7 Hari Terakhir)
                                </h6>
                                <span class="badge bg-light text-muted border px-2 py-1">Harian</span>
                            </div>
                            <div style="height: 240px; position: relative;">
                                <canvas id="chartKehadiranBar"></canvas>
                            </div>
                            <div class="d-flex justify-content-between mt-2 pt-2 border-top small text-muted">
                                <span><i class="fas fa-info-circle me-1"></i> Data diperbarui otomatis berdasarkan absensi harian siswa bimbingan.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progres Individual Siswa Bimbingan Aktif -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #ffffff; border: 1px solid #e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">
                                <i class="fas fa-tasks text-warning me-2"></i> Progres Siswa Bimbingan Aktif
                            </h6>
                            <small class="text-muted">Kemajuan waktu magang dan status penyelesaian setiap siswa binaan.</small>
                        </div>
                        <a href="<?php echo e(route('pembimbing.siswa')); ?>" class="btn btn-sm btn-link text-decoration-none fw-semibold">
                            Lihat Semua Siswa <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>

                    <?php $__empty_1 = true; $__currentLoopData = $stats['siswas_top']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="student-progress-row">
                            <div class="d-flex align-items-center gap-3" style="min-width: 260px;">
                                <img src="<?php echo e($s->foto_profil ? asset('storage/' . $s->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($s->nama) . '&background=0d6efd&color=fff'); ?>" 
                                     alt="<?php echo e($s->nama); ?>" class="rounded-circle" width="42" height="42" style="object-fit: cover; border: 2px solid #e2e8f0;">
                                <div>
                                    <a href="<?php echo e(route('pembimbing.siswa')); ?>" class="fw-bold text-dark text-decoration-none d-block">
                                        <?php echo e($s->nama); ?>

                                    </a>
                                    <small class="text-muted"><?php echo e($s->sekolah ?: 'Sekolah / Instansi'); ?> &bull; NISN: <?php echo e($s->nisn); ?></small>
                                </div>
                            </div>

                            <div class="flex-grow-1 px-4 d-none d-md-block">
                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                    <span class="text-muted">
                                        Periode: <?php echo e($s->tgl_mulai_magang ? \Carbon\Carbon::parse($s->tgl_mulai_magang)->translatedFormat('d M') : '-'); ?> s/d <?php echo e($s->tgl_selesai_magang ? \Carbon\Carbon::parse($s->tgl_selesai_magang)->translatedFormat('d M Y') : '-'); ?>

                                    </span>
                                    <strong class="text-primary"><?php echo e($s->progress_percent); ?>% Selesai</strong>
                                </div>
                                <div class="progress rounded-pill" style="height: 8px; background-color: #f1f5f9;">
                                    <div class="progress-bar rounded-pill <?php echo e($s->progress_percent >= 80 ? 'bar-gradient-green' : ($s->progress_percent >= 40 ? 'bar-gradient-blue' : 'bar-gradient-amber')); ?>" 
                                         role="progressbar" style="width: <?php echo e($s->progress_percent); ?>%;" 
                                         aria-valuenow="<?php echo e($s->progress_percent); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 ms-auto">
                                <span class="badge rounded-pill <?php echo e($s->progress_percent >= 100 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle'); ?> px-3 py-2">
                                    <?php echo e($s->progress_percent >= 100 ? 'Siap Selesai' : 'Sedang Magang'); ?>

                                </span>
                                <a href="<?php echo e(route('pembimbing.siswa')); ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    Detail
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-user-friends fa-2x mb-2 text-secondary opacity-50"></i>
                            <p class="mb-0">Belum ada siswa bimbingan aktif saat ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="home-premium-section">
                
                <!-- BPS Banner -->
                <div class="fasilkom-banner cursor-pointer" onclick="openSejarah()">
                    <div class="banner-icon-wrapper">
                        <img src="<?php echo e(asset('images/loogo.png')); ?>" alt="Logo BPS">
                    </div>
                    <div class="banner-content">
                        <h2 class="banner-title">Badan Pusat Statistik</h2>
                        <p class="banner-description">
                            Badan Pusat Statistik Kabupaten Muara Enim berkomitmen dalam penyediaan data statistik berkualitas untuk Indonesia Maju. 
                            Sistem ini membantu monitoring kegiatan magang siswa secara terpadu.
                        </p>
                        <div class="banner-badge">
                            <i class="fas fa-check-circle"></i>
                            Sistem Monitoring Magang Aktif
                        </div>
                    </div>
                    <div class="banner-more"><i class="fas fa-ellipsis-h"></i></div>
                </div>

                <!-- Tentang BPS -->
                <span class="section-title-sm">Tentang BPS Muara Enim</span>
                <div class="info-grid">
                    <div class="info-card" onclick="openVisiMisi()">
                        <div class="info-icon-box"><i class="fas fa-layer-group"></i></div>
                        <h3 class="info-card-title">Visi & Misi</h3>
                        <p class="info-card-text">
                            <?php echo e(Str::limit($informasi->visi, 100)); ?>

                        </p>
                    </div>

                    <div class="info-card">
                        <div class="info-icon-box"><i class="far fa-clock"></i></div>
                        <h3 class="info-card-title">Jam Operasional</h3>
                        <p class="info-card-text">
                            <?php echo nl2br(e($informasi->jam_operasional)); ?><br>
                            <?php echo e($informasi->deskripsi_jam_operasional); ?>

                        </p>
                    </div>

                    <a href="<?php echo e($informasi->link_maps); ?>" target="_blank" class="info-card-link">
                        <div class="info-card">
                            <div class="info-icon-box"><i class="fas fa-map-marker-alt"></i></div>
                            <h3 class="info-card-title">Lokasi</h3>
                            <p class="info-card-text">
                                <?php echo e($informasi->alamat_lokasi); ?>

                            </p>
                        </div>
                    </a>

                    <div class="info-card">
                        <div class="info-icon-box"><i class="fas fa-phone-alt"></i></div>
                        <h3 class="info-card-title">Kontak</h3>
                        <p class="info-card-text">
                            Email: <?php echo e($informasi->email_kontak); ?><br>
                            Telp: <?php echo e($informasi->telp_kontak); ?><br>
                            Website: <?php echo e($informasi->website_kontak); ?>

                        </p>
                    </div>
                </div>

                <span class="section-title-sm">Divisi</span>
                <div class="prodi-list-container">
                    <?php $__empty_1 = true; $__currentLoopData = $programStudis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="prodi-item-simple">
                            <div class="prodi-dot" style="background-color: <?php echo e($prodi->warna_dot); ?>"></div>
                            <span class="prodi-name"><?php echo e($prodi->nama); ?></span>
                            <span class="prodi-badge" style="color: <?php echo e($prodi->warna_dot); ?>; background-color: <?php echo e($prodi->warna_dot); ?>15; border-color: <?php echo e($prodi->warna_dot); ?>30">
                                <?php echo e($prodi->jenjang); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted small">Belum ada data program studi.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Visi Misi Modal -->
    <div id="visiMisiModal" class="modal-overlay" onclick="closeModal('visiMisiModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-close" onclick="closeModal('visiMisiModal')">&times;</div>
            <h2 class="modal-title">Visi & Misi <?php echo e($informasi->nama_fakultas); ?></h2>
            <div class="modal-body">
                <h4>Visi</h4>
                <p><?php echo e($informasi->visi); ?></p>

                <h4>Misi</h4>
                <ul>
                    <?php $__currentLoopData = $informasi->misi_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $misi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($misi); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sejarah Modal -->
    <div id="sejarahModal" class="modal-overlay" onclick="closeModal('sejarahModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-close" onclick="closeModal('sejarahModal')">&times;</div>
            <h2 class="modal-title">Sejarah <?php echo e($informasi->nama_fakultas); ?></h2>
            <div class="modal-body">
                <p>
                    <?php echo nl2br(e($informasi->sejarah)); ?>

                </p>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.nav.pembimbing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\SiswaMagang\resources\views/pembimbing/pembimbing.blade.php ENDPATH**/ ?>