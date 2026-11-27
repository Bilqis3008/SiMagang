<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Siswa Magang</title>
    
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/auth/login.css') }}?v={{ time() }}">
</head>

<body class="login-page">

    <div class="login-container">
        <div class="login-box">
            
            <!-- LEFT SIDE: BRANDING/INFO -->
            <div class="login-info-section">
                <div class="info-overlay"></div>
                <div class="info-content">
                    <img src="{{ asset('images/loogo.png') }}" alt="Logo BPS" class="info-logo">
                    <h2>Monitoring Siswa Magang</h2>
                    <p>Sistem Informasi Monitoring Siswa Magang Pada Badan Pusat Statistik (BPS) Kabupaten Muara Enim</p>
                    <div class="info-divider"></div>
                </div>
            </div>

            <!-- RIGHT SIDE: FORM INPUT -->
            <div class="login-form-section">
                <div class="form-content">
                    <div class="mobile-logo">
                        <img src="{{ asset('images/loogo.png') }}" alt="Logo BPS">
                        <h3>Monitoring Siswa</h3>
                    </div>

                    <h2 class="form-title">Selamat Datang</h2>
                    <p class="form-subtitle">Silakan masuk ke akun Anda</p>

                    <!-- Alerts -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> Email atau password salah.
                        </div>
                    @endif

                    <form method="post" action="{{ route('login') }}" novalidate>
                        @csrf

                        <div class="form-group">
                            <label class="form-label">EMAIL</label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control" 
                                   placeholder="nama@email.com"
                                   required 
                                   value="{{ old('email') }}"
                                   autofocus>
                        </div>

                        <div class="form-group">
                            <label class="form-label">PASSWORD</label>
                            <div class="input-group">
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control" 
                                       placeholder="••••••••"
                                       required>
                                <button class="toggle-password" type="button" id="btnTogglePassword">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary" id="btn-login">LOGIN</button>
                    </form>

                    <div class="text-center mt-3 pt-2" style="font-size: 0.9rem; color: #64748b;">
                        Belum punya akun? <a href="{{ route('register') }}" style="color: #2563eb; font-weight: 700; text-decoration: none;">Daftar Siswa Baru</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/auth/login.js') }}"></script>
</body>
</html>
