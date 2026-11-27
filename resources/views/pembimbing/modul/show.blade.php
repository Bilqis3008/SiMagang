@extends('layouts.nav.pembimbing')

@section('title', 'Detail Modul Pembelajaran - Pembimbing Lapangan')
@section('body-class', 'dashboard-page')

@section('body')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('pembimbing.modul.index') }}" class="btn btn-light rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Modul
        </a>
        <div class="d-flex align-items-center gap-3">
            <h5 class="fw-bold mb-0">{{ $modul->judul }}</h5>
            <a href="{{ route('pembimbing.modul.edit', $modul->id_modul) }}" class="btn btn-warning rounded-pill px-3 py-1 btn-sm fw-bold">
                <i class="fas fa-edit me-1"></i> Revisi Modul
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Sisi Kiri: Detail Modul, PDF & Video --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Informasi Modul</h6>
                <p class="text-muted">{{ $modul->deskripsi ?? 'Tidak ada deskripsi modul.' }}</p>

                <hr>

                <h6 class="fw-bold mb-3"><i class="fas fa-file-pdf text-danger me-1"></i> Dokumen PDF</h6>
                <div class="p-3 bg-light rounded-4 d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <div class="fw-bold text-dark">Modul Pembelajaran PDF</div>
                        <small class="text-muted">Siap dibaca oleh siswa</small>
                    </div>
                    <a href="{{ route('pembimbing.modul.viewPdf', $modul->id_modul) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3">
                        <i class="fas fa-external-link-alt me-1"></i> Buka PDF
                    </a>
                </div>

                <h6 class="fw-bold mb-3"><i class="fas fa-video text-danger me-1"></i> Video Pembelajaran</h6>
                @if($modul->tipe_video === 'youtube')
                    @php
                        // extract youtube id
                        $ytUrl = $modul->video_url;
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $ytUrl, $match);
                        $ytId = $match[1] ?? null;
                    @endphp
                    @if($ytId)
                        <div class="ratio ratio-16x9 rounded-4 overflow-hidden mb-3">
                            <iframe src="https://www.youtube.com/embed/{{ $ytId }}" title="YouTube video" allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="alert alert-info rounded-4">
                            Link: <a href="{{ $modul->video_url }}" target="_blank">{{ $modul->video_url }}</a>
                        </div>
                    @endif
                @else
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden mb-3">
                        <video controls style="width: 100%; height: 100%; object-fit: cover;">
                            <source src="{{ asset('storage/' . $modul->video_url) }}">
                            Browser Anda tidak mendukung tag video.
                        </video>
                    </div>
                @endif
                <div class="d-flex justify-content-between text-muted small mt-2">
                    <span>Durasi Kuis: <strong>{{ $modul->durasi_kuis }} Menit</strong></span>
                    <span>Total Soal: <strong>{{ $modul->soals->count() }} Soal</strong></span>
                </div>
            </div>

            {{-- Siswa Yang Mengerjakan Modul Ini --}}
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-user-graduate me-1"></i> Siswa yang Ditugaskan ({{ $modul->modulSiswas->count() }})</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Tahapan</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($modul->modulSiswas as $ms)
                                <tr>
                                    <td>
                                        <strong>{{ $ms->siswa->nama ?? $ms->nisn }}</strong><br>
                                        <small class="text-muted">{{ $ms->siswa->sekolah ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @if($ms->kuis_selesai)
                                            <span class="badge bg-success">Kuis Selesai</span>
                                        @elseif($ms->video_selesai)
                                            <span class="badge bg-warning text-dark">Tahap Kuis</span>
                                        @elseif($ms->pdf_selesai)
                                            <span class="badge bg-info text-dark">Tahap Video</span>
                                        @else
                                            <span class="badge bg-secondary">Tahap Baca PDF</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ms->nilai_kuis !== null)
                                            <span class="fw-bold fs-6 text-primary">{{ number_format($ms->nilai_kuis, 0) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">Belum ada siswa yang ditugaskan. Masuk ke menu Daftar Siswa untuk menugaskan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Daftar Soal Kuis Pilihan Ganda --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-success mb-0"><i class="fas fa-question-circle me-1"></i> Daftar Soal Kuis ({{ $modul->soals->count() }})</h6>
                    <span class="badge bg-light text-dark border px-3">Durasi: {{ $modul->durasi_kuis }} Menit</span>
                </div>

                <div class="d-flex flex-column gap-3">
                    @forelse($modul->soals as $idx => $s)
                        <div class="p-3 border rounded-4 bg-light">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary rounded-pill">Soal #{{ $idx + 1 }}</span>
                                <span class="badge bg-success-subtle text-success px-3">Kunci: {{ strtoupper($s->kunci_jawaban) }}</span>
                            </div>
                            <p class="fw-semibold text-dark mb-3">{{ $s->pertanyaan }}</p>
                            
                            <div class="row g-2 small">
                                <div class="col-md-6">
                                    <div class="p-2 border rounded-3 {{ $s->kunci_jawaban == 'a' ? 'bg-success text-white fw-bold' : 'bg-white' }}">
                                        A. {{ $s->opsi_a }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 border rounded-3 {{ $s->kunci_jawaban == 'b' ? 'bg-success text-white fw-bold' : 'bg-white' }}">
                                        B. {{ $s->opsi_b }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 border rounded-3 {{ $s->kunci_jawaban == 'c' ? 'bg-success text-white fw-bold' : 'bg-white' }}">
                                        C. {{ $s->opsi_c }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 border rounded-3 {{ $s->kunci_jawaban == 'd' ? 'bg-success text-white fw-bold' : 'bg-white' }}">
                                        D. {{ $s->opsi_d }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Belum ada butir soal pada modul ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
