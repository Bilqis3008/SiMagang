@extends('layouts.nav.' . $role)

@section('title', 'Obrolan Pesan')

@push('styles')
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
@endpush

@section('body')
<div class="container mt-5 pt-4 mb-5">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route($role . '.pesan') }}" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div>
            <h4 class="mb-0">Obrolan dengan {{ $lawanBicara->nama }}</h4>
            <small class="text-muted">Konteks Siswa: {{ $siswa->nama }} ({{ $siswa->nisn }})</small>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="chat-container bg-light" id="chatContainer">
                @if($pesans->isEmpty())
                    <div class="text-center text-muted my-auto">
                        <p>Belum ada pesan. Mulai diskusikan perkembangan atau laporan siswa.</p>
                    </div>
                @else
                    @foreach($pesans as $pesan)
                        <div class="chat-bubble {{ $pesan->sender_role === $role ? 'sent' : 'received' }}">
                            <div>{{ $pesan->pesan }}</div>
                            <span class="chat-time">{{ $pesan->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="card-footer bg-white">
            <form action="{{ route($role . '.pesan.store', $siswa->nisn) }}" method="POST">
                @csrf
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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var chatContainer = document.getElementById('chatContainer');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    });
</script>
@endpush
