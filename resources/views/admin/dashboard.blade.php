@extends('layouts.app')

@section('title', 'Admin Platform Dashboard')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Admin Platform Dashboard</h1>
        <p class="small text-muted mb-0">Pusat kontrol kurasi kendaraan, verifikasi KYC pengguna, dan pusat resolusi transaksi</p>
    </div>
</div>

<!-- Metrics Cards Sesuai PRD KPIs -->
<div class="row">
    <!-- Antrean Kurasi Motor Pending Sesuai PRD -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kurasi Motor Menunggu</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingMotorsCount }} Kendaraan</div>
                        <a href="{{ route('admin.motor.index', ['status' => 'pending']) }}" class="small font-weight-bold text-warning">Perlu Ditinjau &rarr;</a>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-motorcycle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Antrean Verifikasi KYC Penyewa Sesuai PRD -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">KYC Penyewa Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingKycCount }} Pengguna</div>
                        <a href="{{ route('admin.kyc.index') }}" class="small font-weight-bold text-danger">Tinjau KTP/SIM &rarr;</a>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-id-card fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Motor Sedang Disewa (On Rent) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Sewa Berlangsung (On Rent)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeRentalsCount }} Transaksi</div>
                        <div class="small text-muted font-weight-bold mt-1">Total Unit di Jalan</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-route fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pendapatan Platform (Komisi + Biaya Layanan Sesuai PRD) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Revenue Platform</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalRevenuePlatform, 0, ',', '.') }}</div>
                        <div class="small text-success font-weight-bold">Komisi 10% + Layanan</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Antrean Aksi Mendesak Sesuai PRD -->
<div class="row">
    <!-- Antrean Kurasi Motor Baru (Maks 1x24 Jam Sesuai KPI PRD) -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Antrean Pengajuan Motor Baru (Approval System)</h6>
                <a href="{{ route('admin.motor.index', ['status' => 'pending']) }}" class="small font-weight-bold">Lihat Semua &rarr;</a>
            </div>
            <div class="card-body">
                @if($pendingMotors->isEmpty())
                    <div class="text-center py-4 text-muted small">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p class="mb-0">Tidak ada pengajuan motor yang menunggu kurasi saat ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Motor</th>
                                    <th>Pemilik</th>
                                    <th>Plat Nomor</th>
                                    <th style="width: 140px;">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingMotors as $motor)
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold">{{ $motor->merk }} {{ $motor->tipe_motor }}</div>
                                            <span class="small text-muted">{{ $motor->tipe_cc }} CC</span>
                                        </td>
                                        <td class="small">{{ $motor->pemilik->nama ?? 'Mitra' }}</td>
                                        <td class="font-mono font-weight-bold small">{{ $motor->no_plat }}</td>
                                        <td>
                                            <a href="{{ route('admin.motor.detail', $motor->id) }}" class="btn btn-xs btn-primary btn-block">
                                                <i class="fas fa-search mr-1"></i> Periksa Dokumen
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

    <!-- Antrean Verifikasi Identitas Penyewa (KYC) -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Antrean Verifikasi KYC Penyewa</h6>
                <a href="{{ route('admin.kyc.index') }}" class="small font-weight-bold">Lihat Semua &rarr;</a>
            </div>
            <div class="card-body">
                @if($pendingKycs->isEmpty())
                    <div class="text-center py-4 text-muted small">
                        <i class="fas fa-shield-check fa-2x text-success mb-2"></i>
                        <p class="mb-0">Semua dokumen KYC penyewa sudah diproses.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Penyewa</th>
                                    <th>No. KTP / SIM</th>
                                    <th>Tgl Pengajuan</th>
                                    <th style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingKycs as $renter)
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold">{{ $renter->nama }}</div>
                                            <span class="small text-muted">{{ $renter->email }}</span>
                                        </td>
                                        <td class="small">
                                            <div>KTP: {{ $renter->no_ktp ?? '-' }}</div>
                                            <div>SIM: {{ $renter->no_sim ?? '-' }}</div>
                                        </td>
                                        <td class="small">{{ $renter->updated_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.kyc.index') }}" class="btn btn-xs btn-dark btn-block">
                                                <i class="fas fa-id-card mr-1 text-warning"></i> Tinjau
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
</div>
@endsection

