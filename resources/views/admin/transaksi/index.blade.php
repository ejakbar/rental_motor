@extends('layouts.app')

@section('title', 'Pusat Resolusi & Transaksi Platform')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Pusat Resolusi & Monitoring Transaksi</h1>
        <p class="small text-muted mb-0">Pantau seluruh siklus pembayaran, keterlambatan pengembalian, dan penanganan perselisihan (dispute)</p>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Seluruh Transaksi & Penyewaan</h6>
        <span class="small text-muted">Total: {{ $transaksiList->total() }} Transaksi</span>
    </div>
    <div class="card-body">
        @if($transaksiList->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-exchange-alt fa-3x text-gray-300 mb-3"></i>
                <h5>Belum Ada Data Transaksi</h5>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode Booking</th>
                            <th>Motor & Pemilik</th>
                            <th>Penyewa</th>
                            <th>Batas Waktu Pengembalian</th>
                            <th>Status Pembayaran</th>
                            <th>Status Sewa</th>
                            <th style="width: 140px;">Pusat Resolusi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksiList as $trx)
                            <tr>
                                <td>
                                    <div class="font-mono font-weight-bold text-primary">{{ $trx->kode_booking }}</div>
                                    <div class="small text-muted">{{ $trx->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="small font-weight-bold text-dark mt-1">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</div>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $trx->motor->merk }} {{ $trx->motor->tipe_motor }}</div>
                                    <span class="badge badge-light border font-mono">{{ $trx->motor->no_plat }}</span>
                                    <div class="small text-muted mt-1">Pemilik: {{ $trx->motor->pemilik->nama ?? 'Mitra' }}</div>
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $trx->penyewa->nama ?? 'Penyewa' }}</div>
                                    <div class="small text-muted">{{ $trx->penyewa->no_telp ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="small font-weight-bold text-danger">
                                        <i class="fas fa-calendar-times mr-1"></i>{{ $trx->tanggal_selesai->format('d M Y, H:i') }}
                                    </div>
                                    @if($trx->waktu_dikembalikan)
                                        <div class="small text-success mt-1">
                                            <i class="fas fa-check-circle mr-1"></i>Kembali: {{ $trx->waktu_dikembalikan->format('d M H:i') }}
                                        </div>
                                    @else
                                        @if(now()->greaterThan($trx->tanggal_selesai) && $trx->status_penyewaan === 'on_rent')
                                            <span class="badge badge-danger animate-pulse mt-1">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>TERLAMBAT KEMBALI
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($trx->pembayaran)
                                        {!! $trx->pembayaran->status_badge !!}
                                        <div class="small text-muted mt-1 uppercase">{{ $trx->pembayaran->metode_pembayaran }}</div>
                                    @else
                                        <span class="badge badge-light">Belum Dibuat</span>
                                    @endif
                                </td>
                                <td>
                                    {!! $trx->status_badge !!}
                                    @if($trx->denda_keterlambatan > 0)
                                        <div class="small text-danger font-weight-bold mt-1">
                                            Denda: Rp {{ number_format($trx->denda_keterlambatan, 0, ',', '.') }}
                                        </div>
                                    @endif
                                    @if($trx->catatan_resolusi)
                                        <div class="small text-dark font-weight-bold mt-1">
                                            <strong>Resolusi:</strong> {{ $trx->catatan_resolusi }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-warning btn-block font-weight-bold" data-toggle="modal" data-target="#resolusiModal{{ $trx->id }}">
                                        <i class="fas fa-gavel mr-1"></i> Resolusi
                                    </button>

                                    <!-- Modal Resolusi Keterlambatan / Dispute Sesuai PRD -->
                                    <div class="modal fade" id="resolusiModal{{ $trx->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.transaksi.resolusi', $trx->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-bold text-dark">
                                                            <i class="fas fa-gavel text-warning mr-1"></i> Resolusi Transaksi {{ $trx->kode_booking }}
                                                        </h5>
                                                        <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                        <div class="alert alert-dark small">
                                                            <i class="fas fa-info-circle text-warning mr-1"></i> Pusat resolusi digunakan untuk menangani motor yang terlambat dikembalikan dari jadwal, insiden kerusakan, atau perselisihan sewa antara pemilik dan penyewa.
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="small font-weight-bold">Status Penyewaan</label>
                                                            <select name="status_penyewaan" class="form-control">
                                                                <option value="on_rent" {{ $trx->status_penyewaan === 'on_rent' ? 'selected' : '' }}>On Rent (Sedang Digunakan)</option>
                                                                <option value="dispute" {{ $trx->status_penyewaan === 'dispute' ? 'selected' : '' }}>Dispute (Perselisihan / Keterlambatan)</option>
                                                                <option value="selesai" {{ $trx->status_penyewaan === 'selesai' ? 'selected' : '' }}>Selesai (Kasus Ditutup)</option>
                                                                <option value="batal" {{ $trx->status_penyewaan === 'batal' ? 'selected' : '' }}>Batal (Dibatalkan)</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="small font-weight-bold">Penetapan Denda Keterlambatan (Rp)</label>
                                                            <input type="number" name="denda_keterlambatan" value="{{ (int)$trx->denda_keterlambatan }}" class="form-control" placeholder="Contoh: 50000">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="small font-weight-bold">Catatan Hasil Resolusi / Kesepakatan</label>
                                                            <textarea name="catatan_resolusi" rows="3" required class="form-control"
                                                                placeholder="Jelaskan hasil mediasi antara pemilik dan penyewa...">{{ $trx->catatan_resolusi }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary btn-sm font-weight-bold">Simpan Resolusi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transaksiList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

