<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa Baru - BPS Kabupaten Muara Enim</title>
    
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/kelola-modals.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/kelola-siswa.css') }}">
    
    <style>
        body {
            background: #f1f5f9;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            padding: 2.5rem 1rem;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reg-container {
            width: 100%;
            max-width: 1100px;
        }
        .reg-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            border: none;
        }
    </style>
</head>
<body>
    <div class="reg-container">
        <div class="reg-card">
            {{-- Header (Banner Biru Gradien Persis Modal Admin) --}}
            <div class="modal-header-primary">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-header-icon on-primary">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="modal-header-title">
                        <h5>Registrasi Siswa Baru</h5>
                        <p>Lengkapi formulir untuk mendaftarkan akun dan data siswa magang.</p>
                    </div>
                </div>
                <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 text-white text-decoration-none fw-semibold" style="font-size: 0.85rem;">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
                </a>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Body (Persis Struktur Card & Input Modal Admin) --}}
                <div class="modal-form-body bg-light p-4 p-md-5">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4 mb-4 shadow-sm border-0">
                            <h6 class="fw-bold mb-2"><i class="fas fa-exclamation-circle me-1"></i> Terjadi Kesalahan Input:</h6>
                            <ul class="mb-0 ps-3 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-4">
                        {{-- ══ KOLOM KIRI: PERSONAL & AKUN ════════════════ --}}
                        <div class="col-lg-6">
                            <div class="d-flex flex-column gap-4">
                                <div class="edit-section-block shadow-sm border-0">
                                    <div class="edit-section-title">
                                        <i class="fas fa-id-badge"></i> Informasi Akun & Personal
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="edit-field-label">Nama Lengkap <span class="req">*</span></label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-user input-icon"></i>
                                                <input type="text" name="nama" class="p-input with-icon" required placeholder="Nama Lengkap Siswa" value="{{ old('nama') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="edit-field-label">Jenis Kelamin <span class="req">*</span></label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-venus-mars input-icon"></i>
                                                <select name="jenis_kelamin" class="p-input with-icon" required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="edit-field-label">No. WhatsApp</label>
                                            <div class="p-input-wrapper">
                                                <i class="fab fa-whatsapp input-icon"></i>
                                                <input type="text" name="no_hp" class="p-input with-icon" placeholder="08xx" value="{{ old('no_hp') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="edit-field-label">Email <span class="req">*</span></label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-envelope input-icon"></i>
                                                <input type="email" name="email" class="p-input with-icon" required placeholder="siswa@gmail.com" value="{{ old('email') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="edit-field-label">Kata Sandi <span class="req">*</span></label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-lock input-icon"></i>
                                                <div class="custom-pw-group">
                                                    <input type="password" name="password" id="reg_pw" class="p-input with-icon" required placeholder="Min. 6 Karakter">
                                                    <button class="btn border-0 toggle-password pw-toggle-btn text-muted" type="button" tabindex="-1">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="edit-field-label">Konfirmasi Sandi <span class="req">*</span></label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-shield-alt input-icon"></i>
                                                <div class="custom-pw-group">
                                                    <input type="password" name="password_confirmation" id="reg_pwc" class="p-input with-icon" required placeholder="Ulangi Sandi">
                                                    <button class="btn border-0 toggle-password pw-toggle-btn text-muted" type="button" tabindex="-1">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ══ KOLOM KANAN: IDENTITAS AKADEMIK & MAGANG ════════════════ --}}
                        <div class="col-lg-6">
                            <div class="d-flex flex-column gap-4">
                                <div class="edit-section-block shadow-sm border-0">
                                    <div class="edit-section-title">
                                        <i class="fas fa-graduation-cap"></i> Identitas Akademik & Magang
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="edit-field-label">NISN Siswa <span class="req">*</span></label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-id-card input-icon"></i>
                                                <input type="text" name="nisn" class="p-input with-icon" required placeholder="NISN Siswa" value="{{ old('nisn') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="edit-field-label">Tahun Ajaran <span class="req">*</span></label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-calendar-alt input-icon"></i>
                                                <select name="id_tahun_ajaran" class="p-input with-icon" required>
                                                    @foreach($periodeOptions as $p)
                                                        <option value="{{ $p->id_tahun_ajaran }}" {{ old('id_tahun_ajaran') == $p->id_tahun_ajaran ? 'selected' : '' }}>
                                                            {{ $p->label_periode }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="edit-field-label">Kelas <span class="req">*</span></label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-chalkboard-user input-icon"></i>
                                                <input type="text" name="kelas" class="p-input with-icon" required placeholder="XII-RPL" value="{{ old('kelas') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="edit-field-label">Jurusan <span class="req">*</span></label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-book-open input-icon"></i>
                                                <input type="text" name="jurusan" class="p-input with-icon" required placeholder="Rekayasa Perangkat Lunak" value="{{ old('jurusan') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="edit-field-label">Asal Sekolah <span class="req">*</span></label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-school input-icon"></i>
                                                <input type="text" name="sekolah" id="reg_sekolah" class="p-input with-icon" required placeholder="Nama Sekolah" value="{{ old('sekolah') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="edit-field-label">Tipe Magang <span class="req">*</span></label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-users-viewfinder input-icon"></i>
                                                <select name="tipe_magang" class="p-input with-icon" required>
                                                    <option value="individu" {{ old('tipe_magang') == 'individu' ? 'selected' : '' }}>Individu</option>
                                                    <option value="kelompok" {{ old('tipe_magang') == 'kelompok' ? 'selected' : '' }}>Kelompok</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="edit-field-label">NISN Ketua (Jika Kelompok)</label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-user-shield input-icon"></i>
                                                <input type="text" name="nisn_ketua" class="p-input with-icon" placeholder="Hanya jika Kelompok" value="{{ old('nisn_ketua') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="edit-field-label">Pembimbing Lapangan (BPS)</label>
                                            <div class="p-input-wrapper">
                                                <i class="fas fa-user-tie input-icon"></i>
                                                <select name="id_pembimbing" class="p-input with-icon">
                                                    <option value="">Pilih Pembimbing</option>
                                                    @foreach($pembimbings as $d)
                                                        <option value="{{ $d->id_pembimbing }}" {{ old('id_pembimbing') == $d->id_pembimbing ? 'selected' : '' }}>
                                                            {{ $d->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="edit-field-label">Tgl. Mulai</label>
                                            <div class="p-input-wrapper">
                                                <input type="date" name="tgl_mulai_magang" class="p-input" value="{{ old('tgl_mulai_magang') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="edit-field-label">Tgl. Selesai</label>
                                            <div class="p-input-wrapper">
                                                <input type="date" name="tgl_selesai_magang" class="p-input" value="{{ old('tgl_selesai_magang') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="mt-4 pt-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <span class="text-muted small">
                            Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk di sini</a>
                        </span>
                        <button type="submit" class="btn-primary-custom rounded-pill px-5">
                            <i class="fas fa-user-plus me-2"></i> Daftarkan Siswa
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const wrapper = this.closest('.custom-pw-group') || this.closest('.p-input-wrapper');
                const input = wrapper ? wrapper.querySelector('input') : null;
                if (!input) return;
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
    </script>
</body>
</html>
