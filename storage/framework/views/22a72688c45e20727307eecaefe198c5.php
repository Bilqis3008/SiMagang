<?php $__env->startSection('title', 'Obrolan Pesan'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .chat-container {
        height: 60vh;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        padding: 1rem;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
    }
    .chat-bubble {
        max-width: 75%;
        padding: 10px 15px;
        border-radius: 15px;
        margin-bottom: 10px;
        position: relative;
    }
    .chat-bubble.sent {
        align-self: flex-end;
        background-color: #0d6efd;
        color: white;
        border-bottom-right-radius: 0;
    }
    .chat-bubble.received {
        align-self: flex-start;
        background-color: #ffffff;
        color: black;
        border: 1px solid #dee2e6;
        border-bottom-left-radius: 0;
    }
    .chat-time {
        font-size: 0.75rem;
        opacity: 0.8;
        display: block;
        margin-top: 5px;
        text-align: right;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('body'); ?>
<div class="container mt-5 pt-4 mb-5">
    <div class="d-flex align-items-center mb-4">
        <a href="<?php echo e(route($role . '.pesan')); ?>" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div>
            <h4 class="mb-0">Obrolan dengan <?php echo e($lawanBicara->nama); ?></h4>
            <small class="text-muted">Konteks Siswa: <?php echo e($siswa->nama); ?> (<?php echo e($siswa->nisn); ?>)</small>
        </div>
    </div>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="chat-container bg-light" id="chatContainer">
                <?php if($pesans->isEmpty()): ?>
                    <div class="text-center text-muted my-auto">
                        <p>Belum ada pesan. Mulai diskusikan perkembangan atau laporan siswa.</p>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $pesans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="chat-bubble <?php echo e($pesan->sender_role === $role ? 'sent' : 'received'); ?>">
                            <div><?php echo e($pesan->pesan); ?></div>
                            <span class="chat-time"><?php echo e($pesan->created_at->format('d M Y, H:i')); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-footer bg-white">
            <form action="<?php echo e(route($role . '.pesan.store', $siswa->nisn)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="input-group">
                    <input type="text" name="pesan" class="form-control" placeholder="Tulis pesan atau saran Anda di sini..." required autocomplete="off">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var chatContainer = document.getElementById('chatContainer');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.nav.' . $role, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\SiswaMagang\resources\views/pesan/show.blade.php ENDPATH**/ ?>