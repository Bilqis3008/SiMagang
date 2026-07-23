@extends('layouts.nav.' . $role)

@section('title', 'Pesan')

@section('body')
<div class="container mt-5 pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Daftar Pesan & Saran</h3>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($siswas->isEmpty())
                <div class="alert alert-info">Belum ada siswa yang dapat didiskusikan (memerlukan ketersediaan guru pembimbing dan pembimbing lapangan pada siswa).</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Siswa / NISN</th>
                                <th>Lawan Bicara</th>
                                <th>Pesan Baru</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswas as $siswa)
                                @php
                                    $lawanBicara = $role === 'guru' ? $siswa->pembimbing->nama : $siswa->guru->nama;
                                    $lawanRole = $role === 'guru' ? 'Pembimbing Lapangan' : 'Guru Pembimbing';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $siswa->nama }}</div>
                                        <small class="text-muted">{{ $siswa->nisn }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $lawanBicara }}</div>
                                        <span class="badge bg-secondary">{{ $lawanRole }}</span>
                                    </td>
                                    <td>
                                        @if($siswa->unread_count > 0)
                                            <span class="badge bg-danger rounded-pill">{{ $siswa->unread_count }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route($role . '.pesan.show', $siswa->nisn) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-comments"></i> Buka Obrolan
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
