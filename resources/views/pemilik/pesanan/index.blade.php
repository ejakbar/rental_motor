@extends('layouts.app')

@section('title', 'Manajemen Pesanan Masuk')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Pesanan Sewa Masuk</h1>
        <p class="small text-muted mb-0">Pantau jadwal peminjaman, konfirmasi serah terima motor, dan selesaikan pengembalian unit</p>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Sewa Motor</h6>
        <span class="small text-muted">Total: {{ $pesanans->total() }} Pesanan</span>
    </div>
    <div class="card-body">
        @if($pesanans->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                <h5>Belum Ada Pesanan yang Masuk</h5>
                <p class="text-muted small">Pesanan baru dari penyewa akan muncul di sini secara otomatis.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode Booking</th>
                            <th>Unit Motor</th>
                            <th>Data Penyewa</th>
                            <th>Jadwal Sewa (Ambil - Kembali)</th>
                            <th>Total Sewa</th>
                            <th>Status Pesanan</th>
                            <th style="width: 170px;">Aksi Pemilik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanans as $pesanan)
                            <tr>
                                <td>
                                    <span class="font-weight-bold font-mono text-primary">{{ $pesanan->kode_booking }}</span>
                                    <div class="small text-muted">{{ $pesanan->created_at->format('d M Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $pesanan->motor->merk }} {{ $pesanan->motor->tipe_motor }}</div>
                                    <span class="badge badge-light border font-mono">{{ $pesanan->motor->no_plat }}</span>
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $pesanan->penyewa->nama ?? 'Penyewa' }}</div>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pesanan->penyewa->no_telp ?? '') }}" target="_blank" class="small text-success font-weight-bold">
                                        <i class="fab fa-whatsapp mr-1"></i>{{ $pesanan->penyewa->no_telp }}
                                    </a>
                                </td>
                                <td>
                                    <div class="small">
                                        <i class="fas fa-calendar-plus text-primary mr-1"></i>
                                        <strong>Ambil:</strong> {{ $pesanan->tanggal_mulai->format('d M Y H:i') }}
                                    </div>
                                    <div class="small mt-1">
                                        <i class="fas fa-calendar-check text-danger mr-1"></i>
                                        <strong>Kembali:</strong> {{ $pesanan->tanggal_selesai->format('d M Y H:i') }}
                                    </div>
                                    <span class="badge badge-secondary mt-1">{{ $pesanan->durasi_hari }} Hari</span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">
                                        Rp {{ number_format($pesanan->subtotal, 0, ',', '.') }}
                                    </div>
                                    <span class="small text-success font-weight-bold">
                                        <i class="fas fa-check-circle"></i> Terbayar
                                    </span>
                                </td>
                                <td>
                                    {!! $pesanan->status_badge !!}
                                    @if($pesanan->waktu_diambil)
                                        <div class="small text-muted mt-1">
                                            Diambil: {{ $pesanan->waktu_diambil->format('d/m H:i') }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($pesanan->status_penyewaan === 'disetujui' || $pesanan->status_penyewaan === 'menunggu_konfirmasi')
                                        <form method="POST" action="{{ route('pemilik.pesanan.serahTerima', $pesanan->id) }}" onsubmit="return confirm('Konfirmasi serah terima motor ke penyewa? Motor akan berubah status menjadi Sedang Disewa.')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary btn-block mb-1 font-weight-bold">
                                                <i class="fas fa-handshake mr-1"></i> Serah Terima
                                            </button>
                                        </form>
                                    @elseif($pesanan->status_penyewaan === 'on_rent')
                                        <form method="POST" action="{{ route('pemilik.pesanan.selesai', $pesanan->id) }}" onsubmit="return confirm('Konfirmasi bahwa motor telah dikembalikan dengan aman? Dana sewa (90%) akan otomatis masuk ke saldo Anda.')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success btn-block mb-1 font-weight-bold">
                                                <i class="fas fa-flag-checkered mr-1"></i> Motor Kembali
                                            </button>
                                        </form>
                                    @elseif($pesanan->status_penyewaan === 'selesai')
                                        <span class="badge badge-light border text-muted p-2 d-block text-center">
                                            <i class="fas fa-check-double mr-1 text-success"></i> Transaksi Beres
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pesanans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

