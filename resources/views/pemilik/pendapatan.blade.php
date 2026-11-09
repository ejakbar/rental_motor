@extends('layouts.app')

@section('title', 'Laporan Pendapatan & Penarikan Dana')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Dompet Pendapatan & Tarik Dana</h1>
        <p class="small text-muted mb-0">Laporan hasil penyewaan motor, rincian bagi hasil platform, dan pencairan dana ke rekening Anda</p>
    </div>
</div>

<!-- Row: Saldo & Statistik Pendapatan Sesuai PRD -->
<div class="row">
    <!-- Saldo Dompet Siap Tarik -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Saldo Dompet Siap Ditarik</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($pemilik->saldo, 0, ',', '.') }}</div>
                        <div class="small text-muted mt-1">Dapat dicairkan kapan saja ke rekening bank Anda</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-wallet fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Pendapatan Bersih (Setelah Komisi) -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pendapatan Bersih Pemilik</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPendapatanBersih, 0, ',', '.') }}</div>
                        <div class="small text-success font-weight-bold mt-1">90% dari total nilai sewa motor</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Potongan Komisi Platform -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Total Komisi Platform (10%)</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalKomisiPlatform, 0, ',', '.') }}</div>
                        <div class="small text-muted mt-1">Biaya fasilitator & operasional kurasi</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-percentage fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Kolom Kiri: Form Tarik Dana & Pengaturan Rekening (4 Cols) -->
    <div class="col-lg-4 mb-4">
        <!-- Form Pengajuan Penarikan Dana (Withdrawal) Sesuai PRD -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tarik Dana (Withdrawal)</h6>
            </div>
            <div class="card-body">
                @if(empty($pemilik->no_rekening))
                    <div class="alert alert-warning small">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Harap isi informasi rekening bank Anda terlebih dahulu di formulir bawah sebelum melakukan penarikan dana.
                    </div>
                @else
                    <form method="POST" action="{{ route('pemilik.tarikDana') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="small font-weight-bold">Rekening Tujuan</label>
                            <div class="p-2.5 bg-light rounded border small">
                                <strong>{{ $pemilik->nama_bank }}</strong> - {{ $pemilik->no_rekening }}
                                <div class="text-muted">a.n. {{ $pemilik->atas_nama_rekening ?? $pemilik->nama }}</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small font-weight-bold">Nominal Penarikan (Rp)</label>
                            <input type="number" name="jumlah" min="50000" max="{{ $pemilik->saldo }}" value="{{ old('jumlah') }}" required
                                placeholder="Minimal 50000" class="form-control font-weight-bold">
                            <small class="text-muted">Maksimal: Rp {{ number_format($pemilik->saldo, 0, ',', '.') }}</small>
                        </div>

                        <button type="submit" class="btn btn-warning btn-block font-weight-bold" {{ $pemilik->saldo < 50000 ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane mr-1"></i> Ajukan Penarikan Dana
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Form Pengaturan Rekening Bank Pemilik Sesuai PRD -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pengaturan Rekening Bank</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('pemilik.rekening.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="small font-weight-bold">Nama Bank</label>
                        <input type="text" name="nama_bank" value="{{ old('nama_bank', $pemilik->nama_bank) }}" required
                            placeholder="Contoh: BCA / Mandiri / BRI / BNI" class="form-control form-control-sm">
                    </div>

                    <div class="mb-3">
                        <label class="small font-weight-bold">Nomor Rekening</label>
                        <input type="text" name="no_rekening" value="{{ old('no_rekening', $pemilik->no_rekening) }}" required
                            placeholder="Nomor rekening bank" class="form-control form-control-sm font-mono">
                    </div>

                    <div class="mb-3">
                        <label class="small font-weight-bold">Atas Nama Rekening</label>
                        <input type="text" name="atas_nama_rekening" value="{{ old('atas_nama_rekening', $pemilik->atas_nama_rekening ?? $pemilik->nama) }}" required
                            placeholder="Nama sesuai buku tabungan" class="form-control form-control-sm">
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm btn-block font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Simpan Informasi Rekening
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Riwayat Bagi Hasil & Riwayat Penarikan (8 Cols) -->
    <div class="col-lg-8 mb-4">
        <!-- Riwayat Bagi Hasil Sesuai PRD -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Bagi Hasil Transaksi Sewa Selesai</h6>
            </div>
            <div class="card-body">
                @if($bagiHasils->isEmpty())
                    <div class="text-center py-4 text-muted small">
                        <i class="fas fa-chart-line fa-2x text-gray-300 mb-2"></i>
                        <p class="mb-0">Belum ada transaksi sewa yang diselesaikan.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Booking / Motor</th>
                                    <th>Nilai Sewa Kotor</th>
                                    <th>Komisi Platform (10%)</th>
                                    <th>Pendapatan Bersih (90%)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bagiHasils as $item)
                                    <tr>
                                        <td class="small">{{ $item->tanggal_bagi_hasil->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="font-weight-bold text-primary">{{ $item->penyewaan->motor->merk }} {{ $item->penyewaan->motor->tipe_motor }}</div>
                                            <div class="small font-mono text-muted">{{ $item->penyewaan->kode_booking }}</div>
                                        </td>
                                        <td class="small">Rp {{ number_format($item->total_biaya_sewa, 0, ',', '.') }}</td>
                                        <td class="small text-danger">- Rp {{ number_format($item->komisi_admin, 0, ',', '.') }}</td>
                                        <td class="font-weight-bold text-success">
                                            + Rp {{ number_format($item->bagi_hasil_pemilik, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>Masuk Saldo</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $bagiHasils->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Penarikan Dana (Withdrawals) -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Permintaan Penarikan Dana (Withdrawals)</h6>
            </div>
            <div class="card-body">
                @if($penarikanList->isEmpty())
                    <div class="text-center py-4 text-muted small">
                        <p class="mb-0">Belum pernah melakukan penarikan dana.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode / Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Rekening Bank</th>
                                    <th>Status</th>
                                    <th>Catatan Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penarikanList as $wd)
                                    <tr>
                                        <td>
                                            <span class="font-mono font-weight-bold">{{ $wd->kode_penarikan }}</span>
                                            <div class="small text-muted">{{ $wd->created_at->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td class="font-weight-bold text-dark">
                                            Rp {{ number_format($wd->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td class="small">
                                            <strong>{{ $wd->nama_bank }}</strong> - {{ $wd->no_rekening }}
                                            <div class="text-muted">a.n. {{ $wd->atas_nama_rekening }}</div>
                                        </td>
                                        <td>
                                            {!! $wd->status_badge !!}
                                        </td>
                                        <td class="small text-muted">
                                            {{ $wd->catatan ?? '-' }}
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

