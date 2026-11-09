@extends('layouts.app')

@section('title', 'Dashboard Pemilik Motor')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard Pemilik Motor</h1>
    <a href="{{ route('pemilik.motor.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm font-weight-bold">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Motor Baru
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Total Motor -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Motor Terdaftar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMotor }} Unit</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-motorcycle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Motor Tersedia -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Siap Disewa (Available)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $motorAvailable }} Unit</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sedang Disewa (On Rent) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Sedang Disewa (On Rent)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $motorOnRent }} Unit</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-road fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Saldo Siap Tarik Sesuai PRD -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Saldo Siap Ditarik</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($saldoTersedia, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-wallet fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row: Pesanan Aktif Butuh Tindakan Sesuai PRD -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-between">
                <h6 class="m-0 font-weight-bold text-primary">Pesanan Sewa Memerlukan Aksi (Serah Terima / Selesai)</h6>
                <a href="{{ route('pemilik.pesanan.index') }}" class="small font-weight-bold">Lihat Semua &rarr;</a>
            </div>
            <div class="card-body">
                @if($activeRentals->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-clipboard-check fa-3x mb-3 text-gray-300"></i>
                        <p class="mb-0">Tidak ada pesanan yang sedang menunggu serah terima saat ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode / Motor</th>
                                    <th>Penyewa</th>
                                    <th>Jadwal Sewa</th>
                                    <th>Status</th>
                                    <th>Aksi Pemilik</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeRentals as $rental)
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold">{{ $rental->motor->merk }} {{ $rental->motor->tipe_motor }}</span>
                                            <div class="small font-mono text-muted">{{ $rental->kode_booking }} ({{ $rental->motor->no_plat }})</div>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $rental->penyewa->nama ?? 'Penyewa' }}</div>
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rental->penyewa->no_telp ?? '') }}" target="_blank" class="small text-success">
                                                <i class="fab fa-whatsapp mr-1"></i>{{ $rental->penyewa->no_telp }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="small">Mulai: {{ $rental->tanggal_mulai->format('d M Y H:i') }}</div>
                                            <div class="small font-weight-bold text-danger">Kembali: {{ $rental->tanggal_selesai->format('d M Y H:i') }}</div>
                                        </td>
                                        <td>
                                            {!! $rental->status_badge !!}
                                        </td>
                                        <td>
                                            @if($rental->status_penyewaan === 'disetujui')
                                                <form method="POST" action="{{ route('pemilik.pesanan.serahTerima', $rental->id) }}" onsubmit="return confirm('Konfirmasi serah terima motor ke penyewa?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary btn-block">
                                                        <i class="fas fa-handshake mr-1"></i> Serah Terima
                                                    </button>
                                                </form>
                                            @elseif($rental->status_penyewaan === 'on_rent')
                                                <form method="POST" action="{{ route('pemilik.pesanan.selesai', $rental->id) }}" onsubmit="return confirm('Konfirmasi motor telah kembali? Dana sewa akan otomatis masuk ke saldo Anda.')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success btn-block">
                                                        <i class="fas fa-flag-checkered mr-1"></i> Motor Kembali
                                                    </button>
                                                </form>
                                            @endif
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

    <!-- Kolom Kanan: Info Rekening & Tarik Dana Sesuai PRD -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Rekening Bank Pemilik</h6>
            </div>
            <div class="card-body">
                @if($pemilik->no_rekening)
                    <div class="p-3 bg-light rounded mb-3">
                        <div class="small text-muted font-weight-bold">BANK PENERIMA</div>
                        <div class="h6 font-weight-bold text-dark">{{ $pemilik->nama_bank }}</div>
                        <div class="small text-muted font-weight-bold mt-2">NOMOR REKENING</div>
                        <div class="h5 font-weight-bold font-mono text-primary">{{ $pemilik->no_rekening }}</div>
                        <div class="small text-muted font-weight-bold mt-2">ATAS NAMA</div>
                        <div class="h6 font-weight-bold text-dark">{{ $pemilik->atas_nama_rekening ?? $pemilik->nama }}</div>
                    </div>
                    <a href="{{ route('pemilik.pendapatan') }}" class="btn btn-warning btn-block font-weight-bold">
                        <i class="fas fa-money-bill-wave mr-1"></i> Tarik Dana (Withdrawal)
                    </a>
                @else
                    <div class="alert alert-warning small">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Anda belum mengisi rekening bank untuk pencairan hasil sewa.
                    </div>
                    <a href="{{ route('pemilik.pendapatan') }}" class="btn btn-primary btn-block">
                        Atur Rekening Bank Sekarang
                    </a>
                @endif
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Status Motor Anda</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="small font-weight-bold">Menunggu Kurasi Admin:</span>
                    <span class="badge badge-warning">{{ $motorPending }} Unit</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small font-weight-bold">Tersedia di Katalog:</span>
                    <span class="badge badge-success">{{ $motorAvailable }} Unit</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="small font-weight-bold">Sedang Disewa (On Rent):</span>
                    <span class="badge badge-dark">{{ $motorOnRent }} Unit</span>
                </div>
                <hr>
                <a href="{{ route('pemilik.motor.index') }}" class="btn btn-outline-primary btn-sm btn-block">
                    Kelola Semua Motor
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

