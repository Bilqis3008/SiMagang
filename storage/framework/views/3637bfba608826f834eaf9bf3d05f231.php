<?php $__env->startSection('title', 'Pesan'); ?>

<?php $__env->startSection('body'); ?>
<div class="container mt-5 pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Daftar Pesan & Saran</h3>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if($siswas->isEmpty()): ?>
                <div class="alert alert-info">Belum ada siswa yang dapat didiskusikan (memerlukan ketersediaan guru pembimbing dan pembimbing lapangan pada siswa).</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Siswa / NISN</th>
                                <th>Lawan Bicara</th>
                                <th>Pesan Baru</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $lawanBicara = $role === 'guru' ? $siswa->pembimbing->nama : $siswa->guru->nama;
                                    $lawanRole = $role === 'guru' ? 'Pembimbing Lapangan' : 'Guru Pembimbing';
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?php echo e($siswa->nama); ?></div>
                                        <small class="text-muted"><?php echo e($siswa->nisn); ?></small>
                                    </td>
                                    <td>
                                        <div><?php echo e($lawanBicara); ?></div>
                                        <span class="badge bg-secondary"><?php echo e($lawanRole); ?></span>
                                    </td>
                                    <td>
                                        <?php if($siswa->unread_count > 0): ?>
                                            <span class="badge bg-danger rounded-pill"><?php echo e($siswa->unread_count); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route($role . '.pesan.show', $siswa->nisn)); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-comments"></i> Buka Obrolan
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.nav.' . $role, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\SiswaMagang\resources\views/pesan/index.blade.php ENDPATH**/ ?>