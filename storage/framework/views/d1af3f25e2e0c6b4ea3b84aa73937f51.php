<?php $__env->startSection('title', 'Modul Pembelajaran - Pembimbing Lapangan'); ?>
<?php $__env->startSection('body-class', 'dashboard-page'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/admin/kelola-siswa.css')); ?>">
    <style>
        .module-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            border: 1px solid #f1f5f9;
            transition: all 0.25s ease;
            overflow: hidden;
        }
        .module-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(37, 99, 235, 0.1);
            border-color: #bfdbfe;
        }
        .badge-video {
            background: #fee2e2;
            color: #b91c1c;
            border-radius: 30px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-pdf {
            background: #eff6ff;
            color: #1d4ed8;
            border-radius: 30px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 700;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('body'); ?>
<div class="management-container">
    <div class="admin-content-wrapper">
        <div class="management-header d-flex justify-content-between align-items-center mb-4">
            <div class="header-title d-flex align-items-center gap-3">
                <div class="header-logo-icon" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Modul Pembelajaran Siswa</h5>
                    <p class="text-muted mb-0">Kelola materi PDF, video pembelajaran, dan kuis pilihan ganda.</p>
                </div>
            </div>
            <div>
                <a href="<?php echo e(route('pembimbing.modul.create')); ?>" class="btn btn-primary rounded-pill px-4 shadow-sm py-2">
                    <i class="fas fa-plus me-2"></i> Tambah Modul Baru
                </a>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
                <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $moduls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-lg-4 col-md-6">
                    <div class="module-card h-100 d-flex flex-column p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge-pdf"><i class="fas fa-file-pdf me-1"></i> Materi PDF</span>
                            <span class="badge-video">
                                <i class="fas <?php echo e($m->tipe_video == 'youtube' ? 'fa-brands fa-youtube' : 'fa-video'); ?> me-1"></i>
                                <?php echo e($m->tipe_video == 'youtube' ? 'YouTube' : 'Video Upload'); ?>

                            </span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2"><?php echo e($m->judul); ?></h5>
                        <p class="text-muted small mb-3 flex-grow-1">
                            <?php echo e(Str::limit($m->deskripsi ?? 'Tidak ada deskripsi modul.', 110)); ?>

                        </p>

                        <div class="bg-light p-3 rounded-4 mb-3">
                            <div class="d-flex justify-content-between text-muted small mb-1">
                                <span><i class="fas fa-question-circle text-primary me-1"></i> Jumlah Soal</span>
                                <strong class="text-dark"><?php echo e($m->soals->count()); ?> Butir</strong>
                            </div>
                            <div class="d-flex justify-content-between text-muted small mb-1">
                                <span><i class="fas fa-stopwatch text-warning me-1"></i> Durasi Kuis</span>
                                <strong class="text-dark"><?php echo e($m->durasi_kuis); ?> Menit</strong>
                            </div>
                            <div class="d-flex justify-content-between text-muted small">
                                <span><i class="fas fa-users text-success me-1"></i> Siswa Ditugaskan</span>
                                <strong class="text-dark"><?php echo e($m->modulSiswas->count()); ?> Siswa</strong>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('pembimbing.modul.show', $m->id_modul)); ?>" class="btn btn-outline-primary rounded-pill flex-fill py-2 btn-sm fw-bold">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                            <a href="<?php echo e(route('pembimbing.modul.edit', $m->id_modul)); ?>" class="btn btn-outline-warning text-dark rounded-pill px-3 py-2 btn-sm fw-bold" title="Revisi / Edit Modul">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <form action="<?php echo e(route('pembimbing.modul.destroy', $m->id_modul)); ?>" method="POST" onsubmit="return confirm('Hapus modul pembelajaran ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-light text-danger rounded-circle p-2" title="Hapus Modul">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
                        <i class="fas fa-book-open fa-3x text-muted opacity-50 mb-3"></i>
                        <h5 class="fw-bold text-dark">Belum Ada Modul Pembelajaran</h5>
                        <p class="text-muted">Buat modul baru yang berisi PDF materi, link video, dan kuis pilihan ganda.</p>
                        <a href="<?php echo e(route('pembimbing.modul.create')); ?>" class="btn btn-primary rounded-pill px-4 mt-2">
                            <i class="fas fa-plus me-2"></i> Tambah Modul Sekarang
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.nav.pembimbing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\SiswaMagang\resources\views/pembimbing/modul/index.blade.php ENDPATH**/ ?>