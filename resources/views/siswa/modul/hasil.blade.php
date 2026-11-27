@extends('layouts.nav.siswa')

@section('title', 'Hasil Kuis - Siswa Magang')
@section('body-class', 'dashboard-page')

@section('body')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('siswa.modul.index') }}" class="btn btn-light rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Modul
        </a>
        <h5 class="fw-bold mb-0">Hasil Evaluasi Kuis</h5>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 p-5 text-center mb-4 bg-white">
        <div class="mb-3">
            <div style="width: 90px; height: 90px; border-radius: 50%; background: #ecfdf5; color: #059669; display: inline-flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1rem;">
                <i class="fas fa-award"></i>
            </div>
            <h3 class="fw-bold text-dark">{{ $modulSiswa->modul->judul }}</h3>
            <p class="text-muted">Kuis Pemahaman Materi Pembelajaran Berhasil Diselesaikan</p>
        </div>

        <div class="d-inline-flex flex-column align-items-center p-4 rounded-4 mb-4" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); min-width: 280px; margin: 0 auto;">
            <span class="text-uppercase small fw-bold text-primary" style="letter-spacing: 1px;">Skor Nilai Akhir</span>
            <span class="display-3 fw-bold text-primary">{{ number_format($modulSiswa->nilai_kuis, 0) }}</span>
            <span class="text-muted small">dari total 100 poin</span>
        </div>

        <div class="d-flex justify-content-center gap-4 text-muted small mb-4">
            <div><i class="fas fa-calendar-alt text-primary me-1"></i> Tanggal: <strong>{{ \Carbon\Carbon::parse($modulSiswa->kuis_selesai_pada)->translatedFormat('d F Y H:i') }}</strong></div>
            <div><i class="fas fa-stopwatch text-warning me-1"></i> Durasi: <strong>{{ $modulSiswa->modul->durasi_kuis }} Menit</strong></div>
            <div><i class="fas fa-check-circle text-success me-1"></i> Total Soal: <strong>{{ $modulSiswa->modul->soals->count() }} Butir</strong></div>
        </div>

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('siswa.modul.belajar', $modulSiswa->id_modul_siswa) }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold">
                <i class="fas fa-book-reader me-1"></i> Baca Kembali Modul
            </a>
            <a href="{{ route('siswa.modul.index') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                <i class="fas fa-th-large me-1"></i> Lihat Modul Lainnya
            </a>
        </div>
    </div>
</div>
@endsection
