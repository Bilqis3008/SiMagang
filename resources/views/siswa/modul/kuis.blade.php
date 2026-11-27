@extends('layouts.nav.siswa')

@section('title', 'Kuis Modul Pembelajaran - Siswa Magang')
@section('body-class', 'dashboard-page')

@push('styles')
    <style>
        .timer-box {
            position: sticky;
            top: 80px;
            z-index: 100;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            border: 2px solid #ef4444;
            padding: 12px 20px;
        }
        .option-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 18px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .option-card:hover {
            border-color: #3b82f6;
            background: #f8fafc;
        }
        .option-card input[type="radio"]:checked + .option-text {
            color: #1d4ed8;
            font-weight: 700;
        }
    </style>
@endpush

@section('body')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1"><i class="fas fa-edit text-primary me-2"></i> Kuis: {{ $modulSiswa->modul->judul }}</h5>
            <small class="text-muted">Jawablah pertanyaan pilihan ganda berikut sebelum waktu habis.</small>
        </div>

        {{-- Floating Timer --}}
        <div class="timer-box d-flex align-items-center gap-3">
            <i class="fas fa-clock fa-2x text-danger"></i>
            <div>
                <div class="small fw-bold text-muted text-uppercase" style="font-size: 0.7rem;">Sisa Waktu Pengerjaan</div>
                <div class="fw-bold fs-4 text-danger font-monospace" id="countdownTimer">--:--</div>
            </div>
        </div>
    </div>

    <form id="quizForm" action="{{ route('siswa.modul.submitKuis', $modulSiswa->id_modul_siswa) }}" method="POST">
        @csrf

        <div class="d-flex flex-column gap-4 mb-5">
            @foreach($soals as $idx => $soal)
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-primary rounded-pill px-3 py-1 fw-bold">Nomor {{ $idx + 1 }}</span>
                    </div>

                    <p class="fs-6 fw-semibold text-dark mb-4" style="line-height: 1.6;">{{ $soal->pertanyaan }}</p>

                    <div class="row g-3">
                        @foreach(['a' => $soal->opsi_a, 'b' => $soal->opsi_b, 'c' => $soal->opsi_c, 'd' => $soal->opsi_d] as $key => $opsi)
                            <div class="col-md-6">
                                <label class="option-card w-100">
                                    <input type="radio" name="jawaban[{{ $soal->id_soal }}]" value="{{ $key }}" class="form-check-input mt-0" style="width: 20px; height: 20px;" required>
                                    <span class="option-text flex-grow-1">
                                        <strong>{{ strtoupper($key) }}.</strong> {{ $opsi }}
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 text-end bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-muted small">Periksa kembali semua jawaban Anda sebelum mengumpulkan.</span>
                <button type="submit" id="submitBtn" class="btn btn-success rounded-pill px-5 py-3 fw-bold fs-6 shadow">
                    <i class="fas fa-paper-plane me-2"></i> Kumpulkan Jawaban Kuis
                </button>

    <!-- Modal for confirming submit -->
    <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmSubmitLabel">Konfirmasi Pengumpulan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Apakah Anda yakin ingin mengumpulkan jawaban kuis ini?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="confirmSubmitBtn">Kirim</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for time up -->
    <div class="modal fade" id="timeUpModal" tabindex="-1" aria-labelledby="timeUpLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="timeUpLabel">Waktu Habis</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Waktu pengerjaan kuis telah habis! Jawaban Anda akan otomatis dikirimkan.
          </div>
        </div>
      </div>
    </div>
            </div>
        </div>
    </form>
</div>

<script>
    let remainingSeconds = {{ $sisaDetik }};
    const timerElement = document.getElementById('countdownTimer');
    const formElement = document.getElementById('quizForm');

    function updateTimer() {
        if (remainingSeconds <= 0) {
            timerElement.textContent = "00:00";
            // Show timeout modal and submit after it's shown
            $('#timeUpModal').modal('show');
            $('#timeUpModal').on('shown.bs.modal', function () {
                formElement.submit();
            });
            return;
        }

        const minutes = Math.floor(remainingSeconds / 60);
        const seconds = remainingSeconds % 60;

        timerElement.textContent = 
            (minutes < 10 ? "0" : "") + minutes + ":" + 
            (seconds < 10 ? "0" : "") + seconds;

        remainingSeconds--;
    }

    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);

    // Handle submit button confirmation
    document.getElementById('submitBtn').addEventListener('click', function(e) {
        e.preventDefault();
        $('#confirmSubmitModal').modal('show');
    });

    // When user confirms, submit the form
    document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
        $('#confirmSubmitModal').modal('hide');
        formElement.submit();
    });
</script>
@endsection
