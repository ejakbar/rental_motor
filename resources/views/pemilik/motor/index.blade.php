@extends('layouts.app')

@section('title', 'Manajemen Motor Saya')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Motor Saya</h1>
        <p class="small text-muted mb-0">Kelola kendaraan dan pantau status kurasi Admin serta ketersediaan unit</p>
    </div>
    <a href="{{ route('pemilik.motor.create') }}" class="btn btn-primary shadow-sm font-weight-bold">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Motor Baru
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Kendaraan Terdaftar</h6>
        <span class="small text-muted">Total: {{ $motors->total() }} Motor</span>
    </div>
    <div class="card-body">
        @if($motors->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-motorcycle fa-3x text-gray-300 mb-3"></i>
                <h5>Belum Ada Motor yang Didaftarkan</h5>
                <p class="text-muted small">Mulai daftarkan sepeda motor Anda untuk disewakan di marketplace RentRide.</p>
                <a href="{{ route('pemilik.motor.create') }}" class="btn btn-primary btn-sm mt-2">
                    <i class="fas fa-plus mr-1"></i> Daftarkan Motor Pertama
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 100px;">Foto</th>
                            <th>Detail Motor</th>
                            <th>Tarif Harian</th>
                            <th>Status Kurasi Admin</th>
                            <th>Status Ketersediaan</th>
                            <th style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($motors as $motor)
                            <tr>
                                <td>
                                    <img src="{{ $motor->foto_utama_url }}" class="rounded shadow-sm" style="width: 90px; height: 65px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="font-weight-bold text-primary">{{ $motor->merk }} {{ $motor->tipe_motor }}</div>
                                    <div class="small text-muted">
                                        <span class="badge badge-secondary">{{ $motor->tipe_cc }} CC</span>
                                        <span class="badge badge-light border">Tahun {{ $motor->tahun }}</span>
                                        <span class="font-mono font-weight-bold ml-1">{{ $motor->no_plat }}</span>
                                    </div>
                                    @if($motor->catatan_approval && $motor->status_approval === 'rejected')
                                        <div class="alert alert-danger p-1 mt-1 mb-0 small">
                                            <strong>Alasan Ditolak:</strong> {{ $motor->catatan_approval }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">
                                        Rp {{ number_format($motor->tarif_harian, 0, ',', '.') }}
                                    </div>
                                    <span class="small text-muted">/hari</span>
                                </td>
                                <td>
                                    @if($motor->status_approval === 'approved')
                                        <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>Approved (Terbit)</span>
                                    @elseif($motor->status_approval === 'pending')
                                        <span class="badge badge-warning text-dark"><i class="fas fa-clock mr-1"></i>Pending Kurasi</span>
                                        <div class="small text-muted mt-1">Admin sedang meninjau dokumen & kelayakan</div>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i>Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($motor->status === 'disewa')
                                        <span class="badge badge-dark"><i class="fas fa-user mr-1 text-warning"></i>Sedang Disewa</span>
                                        @php
                                            $activeRental = $motor->penyewaans->first();
                                        @endphp
                                        @if($activeRental)
                                            <div class="small text-danger font-weight-bold mt-1">
                                                <i class="fas fa-calendar-times mr-1"></i>Kembali: {{ $activeRental->tanggal_selesai->format('d M H:i') }}
                                            </div>
                                            <div class="small text-muted">
                                                Penyewa: {{ $activeRental->penyewa->nama ?? 'Pengguna' }}
                                            </div>
                                        @endif
                                    @elseif($motor->status === 'tersedia')
                                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Tersedia (Ready)</span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-power-off mr-1"></i>Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('motor.detail', $motor->id) }}" target="_blank" class="btn btn-outline-dark" title="Lihat di Katalog">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                        <a href="{{ route('pemilik.motor.edit', $motor->id) }}" class="btn btn-outline-primary" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($motor->status !== 'disewa')
                                            <form method="POST" action="{{ route('pemilik.motor.destroy', $motor->id) }}" onsubmit="return confirm('Hapus motor ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $motors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

