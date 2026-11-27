@extends('layouts.nav.siswa')

@section('title', 'Modul Pembelajaran - Siswa Magang')
@section('body-class', 'dashboard-page')

@push('styles')
    <style>
        .module-item-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            border: 1px solid #f1f5f9;
            transition: all 0.25s ease;
        }
        .module-item-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 36px rgba(37, 99, 235, 0.1);
            border-color: #bfdbfe;
        }
        .step-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .step-done {
            background: #dcfce7;
            color: #15803d;
        }
        .step-pending {
            background: #f1f5f9;
            color: #64748b;
        }
    </style>
@endpush

@section('body')
<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
            <i class="fas fa-book-reader"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1">Modul Pembelajaran Saya</h5>
            <p class="text-muted mb-0">Pelajari materi PDF, tonton video pengajaran, dan ikuti kuis berwaktu.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($modulSiswas as $ms)
            @php
                $m = $ms->modul;
                $today = \Carbon\Carbon::today()->toDateString();
                $isBelumMulai = $ms->tgl_mulai && $today < $ms->tgl_mulai;
                $isKadaluarsa = $ms->tgl_selesai && $today > $ms->tgl_selesai;
                $isBolehMengerjakan = !$isBelumMulai && !$isKadaluarsa;
            @endphp
            <div class="col-lg-6">
                <div class="module-item-card p-4 h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill small fw-bold">
                            <i class="fas fa-chalkboard-teacher me-1"></i> {{ $m->pembimbing->nama ?? 'Pembimbing' }}
                        </span>
                        @if($ms->kuis_selesai)
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                <i class="fas fa-award me-1"></i> Skor: {{ number_format($ms->nilai_kuis, 0) }}
                            </span>
                        @elseif($isBelumMulai)
                            <span class="badge bg-secondary rounded-pill px-3 py-2">
                                <i class="fas fa-lock me-1"></i> Belum Dibuka
                            </span>
                        @elseif($isKadaluarsa)
                            <span class="badge bg-danger rounded-pill px-3 py-2">
                                <i class="fas fa-times-circle me-1"></i> Waktu Berakhir
                            </span>
                        @else
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                Dalam Proses
                            </span>
                        @endif
                    </div>

                    <h5 class="fw-bold text-dark mb-2">{{ $m->judul }}</h5>
                    <p class="text-muted small mb-3 flex-grow-1">
                        {{ Str::limit($m->deskripsi ?? 'Tidak ada deskripsi modul.', 120) }}
                    </p>

                    {{-- Progres 3 Tahapan --}}
                    <div class="p-3 bg-light rounded-4 mb-3">
                        <div class="small fw-bold text-muted mb-2 text-uppercase" style="letter-spacing: 0.5px;">Alur Pembelajaran:</div>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="step-pill {{ $ms->pdf_selesai ? 'step-done' : 'step-pending' }}">
                                <i class="fas {{ $ms->pdf_selesai ? 'fa-check-circle' : 'fa-circle' }}"></i> 1. Materi PDF
                            </span>
                            <span class="step-pill {{ $ms->video_selesai ? 'step-done' : 'step-pending' }}">
                                <i class="fas {{ $ms->video_selesai ? 'fa-check-circle' : 'fa-circle' }}"></i> 2. Video Modul
                            </span>
                            <span class="step-pill {{ $ms->kuis_selesai ? 'step-done' : 'step-pending' }}">
                                <i class="fas {{ $ms->kuis_selesai ? 'fa-check-circle' : 'fa-circle' }}"></i> 3. Kuis ({{ $m->durasi_kuis }} Mnt)
                            </span>
                        </div>
                    </div>

                    {{-- Periode Akses Modul --}}
                    @if($ms->tgl_mulai || $ms->tgl_selesai)
                    <div class="d-flex align-items-center gap-2 mb-3 px-1">
                        <i class="fas fa-calendar-alt text-primary" style="font-size:0.85rem;"></i>
                        <small class="text-muted">
                            <span class="fw-semibold text-dark">Periode Modul:</span>
                            {{ $ms->tgl_mulai ? \Carbon\Carbon::parse($ms->tgl_mulai)->translatedFormat('d M Y') : '?' }}
                            &ndash;
                            {{ $ms->tgl_selesai ? \Carbon\Carbon::parse($ms->tgl_selesai)->translatedFormat('d M Y') : '?' }}
                        </small>
                    </div>
                    @endif

                    @if($isBelumMulai)
                        <div class="alert alert-secondary py-2 px-3 small rounded-4 mb-3 border-0 bg-light text-muted">
                            <i class="fas fa-clock text-warning me-1"></i> Modul ini baru dapat dikerjakan pada tanggal <strong>{{ \Carbon\Carbon::parse($ms->tgl_mulai)->translatedFormat('d F Y') }}</strong>.
                        </div>
                    @elseif($isKadaluarsa && !$ms->kuis_selesai)
                        <div class="alert alert-danger py-2 px-3 small rounded-4 mb-3 border-0 bg-danger-subtle text-danger-emphasis">
                            <i class="fas fa-exclamation-triangle me-1"></i> Batas waktu pengerjaan telah berakhir pada tanggal <strong>{{ \Carbon\Carbon::parse($ms->tgl_selesai)->translatedFormat('d F Y') }}</strong>. Siswa tidak diperkenankan lagi mengerjakan modul ini.
                        </div>
                    @endif

                    <div class="d-flex justify-content-end">
                        @if($ms->kuis_selesai)
                            <a href="{{ route('siswa.modul.hasil', $ms->id_modul_siswa) }}" class="btn btn-outline-success rounded-pill px-4 py-2 fw-bold">
                                <i class="fas fa-check-double me-1"></i> Lihat Hasil & Review
                            </a>
                        @elseif($isBelumMulai)
                            <button class="btn btn-secondary rounded-pill px-4 py-2 fw-bold" disabled title="Modul belum dibuka">
                                <i class="fas fa-lock me-1"></i> Belum Dapat Dikerjakan
                            </button>
                        @elseif($isKadaluarsa)
                            <button class="btn btn-secondary rounded-pill px-4 py-2 fw-bold" disabled title="Waktu pengerjaan telah berakhir">
                                <i class="fas fa-ban me-1"></i> Waktu Mengerjakan Berakhir
                            </button>
                        @else
                            <a href="{{ route('siswa.modul.belajar', $ms->id_modul_siswa) }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                                @if($ms->video_selesai)
                                    <i class="fas fa-pen-alt me-1"></i> Kerjakan Kuis Sekarang
                                @elseif($ms->pdf_selesai)
                                    <i class="fas fa-play me-1"></i> Lanjut Nonton Video
                                @else
                                    <i class="fas fa-book-open me-1"></i> Mulai Belajar Modul
                                @endif
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
                    <i class="fas fa-book fa-3x text-muted opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark">Belum Ada Modul Ditugaskan</h5>
                    <p class="text-muted">Pembimbing lapangan belum menugaskan modul pembelajaran untuk Anda. Silakan hubungi pembimbing Anda.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
