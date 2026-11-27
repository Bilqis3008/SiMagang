<?php $__env->startSection('title', 'Tambah Modul Pembelajaran - Pembimbing Lapangan'); ?>
<?php $__env->startSection('body-class', 'dashboard-page'); ?>

<?php $__env->startSection('body'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="<?php echo e(route('pembimbing.modul.index')); ?>" class="btn btn-light rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Modul
                </a>
                <h5 class="fw-bold mb-0">Tambah Modul Pembelajaran</h5>
            </div>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger rounded-4 mb-4">
                    <ul class="mb-0 ps-3">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('pembimbing.modul.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                
                
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-primary text-white p-3 px-4 rounded-top-4">
                        <h6 class="fw-bold mb-0"><i class="fas fa-file-pdf me-2"></i> 1. Materi Dokumen PDF Modul</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Modul <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control rounded-pill px-3 py-2" required placeholder="Contoh: Pengenalan Sensus & Metodologi Statistik" value="<?php echo e(old('judul')); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi Ringkas</label>
                            <textarea name="deskripsi" class="form-control rounded-4 p-3" rows="3" placeholder="Jelaskan capaian pembelajaran yang diharapkan dari modul ini..."><?php echo e(old('deskripsi')); ?></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Upload File PDF Modul <span class="text-danger">*</span></label>
                            <input type="file" name="file_pdf" class="form-control rounded-pill px-3 py-2" accept=".pdf" required>
                            <small class="text-muted">Format file PDF (Maksimal 15 MB). Siswa akan membaca modul ini pada tahap pertama.</small>
                        </div>
                    </div>
                </div>

                
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-danger text-white p-3 px-4 rounded-top-4">
                        <h6 class="fw-bold mb-0"><i class="fas fa-video me-2"></i> 2. Video Modul Pembelajaran</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Opsi Sumber Video <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipe_video" id="videoYt" value="youtube" checked onchange="toggleVideoSource()">
                                    <label class="form-check-label fw-semibold" for="videoYt">
                                        <i class="fab fa-youtube text-danger me-1"></i> Link YouTube Video
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipe_video" id="videoUpload" value="upload" onchange="toggleVideoSource()">
                                    <label class="form-check-label fw-semibold" for="videoUpload">
                                        <i class="fas fa-upload text-primary me-1"></i> Upload File Video Langsung
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="wrapperYoutube" class="mb-0">
                            <label class="form-label fw-bold">Link URL Video YouTube</label>
                            <input type="url" name="video_youtube" id="inputYoutube" class="form-control rounded-pill px-3 py-2" placeholder="https://www.youtube.com/watch?v=xxxx atau https://youtu.be/xxxx" value="<?php echo e(old('video_youtube')); ?>">
                            <small class="text-muted">Masukkan URL lengkap video YouTube materi.</small>
                        </div>

                        <div id="wrapperUpload" class="mb-0 d-none">
                            <label class="form-label fw-bold">Pilih File Video (MP4 / WebM)</label>
                            <input type="file" name="video_file" id="inputUpload" class="form-control rounded-pill px-3 py-2" accept="video/mp4,video/webm,video/quicktime">
                            <small class="text-muted">Maksimal ukuran video: 50 MB.</small>
                        </div>
                    </div>
                </div>

                
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-success text-white p-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="fas fa-tasks me-2"></i> 3. Kuis & Soal Pilihan Ganda</h6>
                        <span class="badge bg-white text-success px-3 py-1 rounded-pill">Wajib Mengisi Soal</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Waktu Pengerjaan Kuis (Menit) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="durasi_kuis" class="form-control rounded-start-pill px-3 py-2" min="1" max="180" value="<?php echo e(old('durasi_kuis', 15)); ?>" required>
                                    <span class="input-group-text rounded-end-pill bg-light">Menit</span>
                                </div>
                                <small class="text-muted">Waktu hitung mundur otomatis saat siswa mulai mengerjakan kuis.</small>
                            </div>
                        </div>

                        <h6 class="fw-bold text-primary mb-3">Daftar Pertanyaan Kuis</h6>
                        <div id="soalContainer">
                            
                            <div class="soal-item border p-3 rounded-4 mb-3 bg-light" id="soal-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-dark">Pertanyaan #1</span>
                                </div>
                                <div class="mb-3">
                                    <textarea name="soal[0][pertanyaan]" class="form-control rounded-3" rows="2" required placeholder="Tuliskan butir soal di sini..."></textarea>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">A</span>
                                            <input type="text" name="soal[0][opsi_a]" class="form-control" required placeholder="Pilihan Jawaban A">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">B</span>
                                            <input type="text" name="soal[0][opsi_b]" class="form-control" required placeholder="Pilihan Jawaban B">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">C</span>
                                            <input type="text" name="soal[0][opsi_c]" class="form-control" required placeholder="Pilihan Jawaban C">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">D</span>
                                            <input type="text" name="soal[0][opsi_d]" class="form-control" required placeholder="Pilihan Jawaban D">
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold text-muted mb-1">Kunci Jawaban Benar:</label>
                                        <select name="soal[0][kunci]" class="form-select rounded-pill" required>
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="d">D</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-success rounded-pill px-4 mt-2" onclick="tambahSoal()">
                            <i class="fas fa-plus me-1"></i> Tambah Butir Soal Lainnya
                        </button>
                    </div>
                </div>

                
                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="<?php echo e(route('pembimbing.modul.index')); ?>" class="btn btn-light rounded-pill px-4 py-2">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow">
                        <i class="fas fa-save me-2"></i> Simpan Modul Pembelajaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleVideoSource() {
        const isYt = document.getElementById('videoYt').checked;
        const wrapYt = document.getElementById('wrapperYoutube');
        const wrapUpload = document.getElementById('wrapperUpload');
        const inputYt = document.getElementById('inputYoutube');
        const inputUpload = document.getElementById('inputUpload');

        if (isYt) {
            wrapYt.classList.remove('d-none');
            wrapUpload.classList.add('d-none');
            inputYt.setAttribute('required', 'required');
            inputUpload.removeAttribute('required');
        } else {
            wrapYt.classList.add('d-none');
            wrapUpload.classList.remove('d-none');
            inputUpload.setAttribute('required', 'required');
            inputYt.removeAttribute('required');
        }
    }

    let soalIndex = 1;
    function tambahSoal() {
        const container = document.getElementById('soalContainer');
        const div = document.createElement('div');
        div.className = 'soal-item border p-3 rounded-4 mb-3 bg-light';
        div.id = 'soal-' + soalIndex;
        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-dark">Pertanyaan #${soalIndex + 1}</span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" onclick="hapusSoal(${soalIndex})">
                    <i class="fas fa-trash me-1"></i> Hapus Soal
                </button>
            </div>
            <div class="mb-3">
                <textarea name="soal[${soalIndex}][pertanyaan]" class="form-control rounded-3" rows="2" required placeholder="Tuliskan butir soal di sini..."></textarea>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text fw-bold">A</span>
                        <input type="text" name="soal[${soalIndex}][opsi_a]" class="form-control" required placeholder="Pilihan Jawaban A">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text fw-bold">B</span>
                        <input type="text" name="soal[${soalIndex}][opsi_b]" class="form-control" required placeholder="Pilihan Jawaban B">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text fw-bold">C</span>
                        <input type="text" name="soal[${soalIndex}][opsi_c]" class="form-control" required placeholder="Pilihan Jawaban C">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text fw-bold">D</span>
                        <input type="text" name="soal[${soalIndex}][opsi_d]" class="form-control" required placeholder="Pilihan Jawaban D">
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">Kunci Jawaban Benar:</label>
                    <select name="soal[${soalIndex}][kunci]" class="form-select rounded-pill" required>
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>
            </div>
        `;
        container.appendChild(div);
        soalIndex++;
    }

    function hapusSoal(idx) {
        const el = document.getElementById('soal-' + idx);
        if (el) el.remove();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.nav.pembimbing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\SiswaMagang\resources\views/pembimbing/modul/create.blade.php ENDPATH**/ ?>