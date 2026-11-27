@extends('layouts.nav.siswa')

@section('title', 'Belajar Modul - Siswa Magang')
@section('body-class', 'dashboard-page')

@section('body')
@php
    $today = \Carbon\Carbon::today()->toDateString();
    $isBelumMulai = $modulSiswa->tgl_mulai && $today < $modulSiswa->tgl_mulai;
    $isKadaluarsa = $modulSiswa->tgl_selesai && $today > $modulSiswa->tgl_selesai;
    $canWork = !$isBelumMulai && !$isKadaluarsa;
@endphp
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('siswa.modul.index') }}" class="btn btn-light rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Modul Saya
        </a>
        <h5 class="fw-bold mb-0 text-dark">{{ $modulSiswa->modul->judul }}</h5>
    </div>

    @if($isBelumMulai)
        <div class="alert alert-secondary border-0 rounded-4 shadow-sm mb-4">
            <i class="fas fa-clock text-warning me-2"></i> Akses modul pembelajaran ini baru dibuka pada <strong>{{ \Carbon\Carbon::parse($modulSiswa->tgl_mulai)->translatedFormat('d F Y') }}</strong>.
        </div>
    @elseif($isKadaluarsa && !$modulSiswa->kuis_selesai)
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i> Waktu pengerjaan modul ini telah berakhir pada <strong>{{ \Carbon\Carbon::parse($modulSiswa->tgl_selesai)->translatedFormat('d F Y') }}</strong>. Anda tidak dapat lagi mengerjakan modul ini.
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
        </div>
    @endif

    {{-- NAVIGASI TAHAPAN --}}
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div class="d-flex justify-content-around flex-wrap gap-2 text-center">
            <div class="d-flex align-items-center gap-2">
                <span class="badge {{ $modulSiswa->pdf_selesai ? 'bg-success' : 'bg-primary' }} rounded-circle p-2" style="width: 32px; height: 32px;">1</span>
                <span class="fw-bold {{ $modulSiswa->pdf_selesai ? 'text-success' : 'text-primary' }}">Materi Dokumen PDF</span>
            </div>
            <i class="fas fa-chevron-right text-muted align-self-center d-none d-md-block"></i>
            <div class="d-flex align-items-center gap-2">
                <span class="badge {{ $modulSiswa->video_selesai ? 'bg-success' : ($modulSiswa->pdf_selesai ? 'bg-primary' : 'bg-secondary') }} rounded-circle p-2" style="width: 32px; height: 32px;">2</span>
                <span class="fw-bold {{ $modulSiswa->video_selesai ? 'text-success' : ($modulSiswa->pdf_selesai ? 'text-primary' : 'text-muted') }}">Video Modul</span>
            </div>
            <i class="fas fa-chevron-right text-muted align-self-center d-none d-md-block"></i>
            <div class="d-flex align-items-center gap-2">
                <span class="badge {{ $modulSiswa->kuis_selesai ? 'bg-success' : ($modulSiswa->video_selesai ? 'bg-primary' : 'bg-secondary') }} rounded-circle p-2" style="width: 32px; height: 32px;">3</span>
                <span class="fw-bold {{ $modulSiswa->kuis_selesai ? 'text-success' : ($modulSiswa->video_selesai ? 'text-primary' : 'text-muted') }}">Kuis Pilihan Ganda</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- TAHAP 1: BACA MODUL PDF --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-primary text-white p-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="fas fa-file-pdf me-2"></i> Tahap 1: Mempelajari Dokumen PDF Modul</h6>
                    @if($modulSiswa->pdf_selesai)
                        <span class="badge bg-success rounded-pill px-3 py-1"><i class="fas fa-check me-1"></i> PDF Telah Diselesaikan</span>
                    @endif
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Silakan baca dan pelajari dokumen PDF berikut sebelum melanjutkan ke materi video.</p>
                    
                    <div style="height: 580px; width: 100%; border-radius: 14px; overflow: hidden; border: 1px solid #e2e8f0;" class="mb-3">
                        <iframe src="{{ route('siswa.modul.viewPdf', $modulSiswa->id_modul_siswa) }}" width="100%" height="100%" style="border: none;"></iframe>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="{{ route('siswa.modul.viewPdf', $modulSiswa->id_modul_siswa) }}" target="_blank" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i> Buka PDF di Tab Baru
                        </a>
                        
                        @if(!$modulSiswa->pdf_selesai)
                            @if(!$canWork)
                                <button class="btn btn-secondary rounded-pill px-4 py-2 fw-bold" disabled>
                                    <i class="fas fa-lock me-1"></i> Tidak Dapat Diselesaikan (Di Luar Periode)
                                </button>
                            @else
                                <form action="{{ route('siswa.modul.selesaiPdf', $modulSiswa->id_modul_siswa) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                                        <i class="fas fa-check me-2"></i> Selesaikan Modul PDF & Lanjut ke Video
                                    </button>
                                </form>
                            @endif
                        @else
                            <button class="btn btn-success rounded-pill px-4 py-2 fw-bold" disabled>
                                <i class="fas fa-check-circle me-1"></i> Tahap PDF Selesai
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- TAHAP 2: VIDEO PEMBELAJARAN (Terbuka setelah PDF selesai) --}}
        @if($modulSiswa->pdf_selesai)
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-danger text-white p-3 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="fas fa-video me-2"></i> Tahap 2: Video Pembelajaran Modul</h6>
                        @if($modulSiswa->video_selesai)
                            <span class="badge bg-success rounded-pill px-3 py-1"><i class="fas fa-check me-1"></i> Video Telah Diselesaikan</span>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted">Tonton video penjelasan materi pembelajaran di bawah ini secara saksama.</p>

                        @if($modulSiswa->modul->tipe_video === 'youtube')
                            @php
                                $ytUrl = $modulSiswa->modul->video_url;
                                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $ytUrl, $match);
                                $ytId = $match[1] ?? null;
                            @endphp
                            @if($ytId)
                                <div class="ratio ratio-16x9 rounded-4 overflow-hidden mb-4 shadow-sm" style="max-height: 520px;">
                                    <iframe src="https://www.youtube.com/embed/{{ $ytId }}" title="YouTube video" allowfullscreen></iframe>
                                </div>
                            @else
                                <div class="alert alert-info rounded-4 mb-4">
                                    Link Video: <a href="{{ $modulSiswa->modul->video_url }}" target="_blank">{{ $modulSiswa->modul->video_url }}</a>
                                </div>
                            @endif
                        @else
                            <div class="ratio ratio-16x9 rounded-4 overflow-hidden mb-4 shadow-sm" style="max-height: 520px;">
                                <video controls style="width: 100%; height: 100%; object-fit: cover;">
                                    <source src="{{ asset('storage/' . $modulSiswa->modul->video_url) }}">
                                </video>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end">
                            @if(!$modulSiswa->video_selesai)
                                @if(!$canWork)
                                    <button class="btn btn-secondary rounded-pill px-4 py-2 fw-bold" disabled>
                                        <i class="fas fa-lock me-1"></i> Tidak Dapat Diselesaikan (Di Luar Periode)
                                    </button>
                                @else
                                    <form action="{{ route('siswa.modul.selesaiVideo', $modulSiswa->id_modul_siswa) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-bold shadow-sm">
                                            <i class="fas fa-check me-2"></i> Selesaikan Video & Lanjut ke Kuis
                                        </button>
                                    </form>
                                @endif
                            @else
                                <button class="btn btn-success rounded-pill px-4 py-2 fw-bold" disabled>
                                    <i class="fas fa-check-circle me-1"></i> Tahap Video Selesai
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- TAHAP 3: KUIS PILIHAN GANDA --}}
        @if($modulSiswa->video_selesai)
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                    <div class="card-header bg-success text-white p-3 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="fas fa-tasks me-2"></i> Tahap 3: Kuis Pemahaman Materi</h6>
                        @if($modulSiswa->kuis_selesai)
                            <span class="badge bg-white text-success rounded-pill px-3 py-1 fw-bold">Skor: {{ number_format($modulSiswa->nilai_kuis, 0) }} / 100</span>
                        @endif
                    </div>
                    <div class="card-body p-4 text-center py-5">
                        @if($modulSiswa->kuis_selesai)
                            <div class="mb-4">
                                <i class="fas fa-trophy fa-4x text-warning mb-3"></i>
                                <h4 class="fw-bold text-dark">Selamat! Anda Telah Menyelesaikan Kuis</h4>
                                <p class="text-muted">Nilai yang Anda peroleh: <strong class="fs-4 text-success">{{ number_format($modulSiswa->nilai_kuis, 0) }}</strong></p>
                            </div>
                            <a href="{{ route('siswa.modul.hasil', $modulSiswa->id_modul_siswa) }}" class="btn btn-outline-success rounded-pill px-4 py-2 fw-bold">
                                <i class="fas fa-eye me-1"></i> Lihat Rincian Hasil Kuis
                            </a>
                        @else
                            <div class="mb-4">
                                <i class="fas fa-stopwatch fa-4x text-primary mb-3"></i>
                                <h4 class="fw-bold text-dark">Siap Menguji Pemahaman Anda?</h4>
                                <p class="text-muted">
                                    Kuis ini terdiri dari <strong>{{ $modulSiswa->modul->soals->count() }} soal pilihan ganda</strong> 
                                    dengan batas waktu <strong>{{ $modulSiswa->modul->durasi_kuis }} menit</strong>. Waktu akan langsung berjalan begitu Anda menekan tombol di bawah.
                                </p>
                            </div>
                            @if(!$canWork)
                                <button class="btn btn-secondary rounded-pill px-5 py-3 fw-bold fs-6" disabled>
                                    <i class="fas fa-lock me-2"></i> Kuis Tidak Dapat Dikerjakan (Di Luar Periode)
                                </button>
                            @else
                                <a href="{{ route('siswa.modul.kuis', $modulSiswa->id_modul_siswa) }}" class="btn btn-success rounded-pill px-5 py-3 fw-bold fs-6 shadow">
                                    <i class="fas fa-pencil-alt me-2"></i> Mulai Mengerjakan Kuis Sekarang
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
