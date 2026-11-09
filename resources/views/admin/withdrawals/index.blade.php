@extends('layouts.app')

@section('title', 'Manajemen Penarikan Dana Pemilik')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Pencairan Dana Pemilik (Withdrawals)</h1>
        <p class="small text-muted mb-0">Tinjau permohonan transfer pencairan hasil sewa motor dari para mitra pemilik</p>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Permintaan Penarikan Dana</h6>
        <span class="small text-muted">Total: {{ $withdrawals->total() }} Pengajuan</span>
    </div>
    <div class="card-body">
        @if($withdrawals->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-hand-holding-usd fa-3x text-gray-300 mb-3"></i>
                <h5>Belum Ada Permintaan Penarikan Dana</h5>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode & Tanggal</th>
                            <th>Mitra Pemilik</th>
                            <th>Nominal Penarikan</th>
                            <th>Rekening Tujuan Transfer</th>
                            <th>Status</th>
                            <th style="width: 180px;">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $wd)
                            <tr>
                                <td>
                                    <div class="font-mono font-weight-bold text-primary">{{ $wd->kode_penarikan }}</div>
                                    <div class="small text-muted">{{ $wd->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $wd->pemilik->nama ?? 'Mitra' }}</div>
                                    <div class="small text-muted">{{ $wd->pemilik->email ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark h6 mb-0">
                                        Rp {{ number_format($wd->jumlah, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-primary">{{ $wd->nama_bank }} - {{ $wd->no_rekening }}</div>
                                    <div class="small text-muted">a.n. {{ $wd->atas_nama_rekening }}</div>
                                </td>
                                <td>
                                    {!! $wd->status_badge !!}
                                    @if($wd->catatan)
                                        <div class="small text-muted mt-1">{{ $wd->catatan }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($wd->status === 'pending')
                                        <div class="d-flex gap-1">
                                            <form method="POST" action="{{ route('admin.withdrawals.approve', $wd->id) }}" class="flex-grow-1 mr-1" onsubmit="return confirm('Konfirmasi bahwa dana telah ditransfer ke rekening pemilik?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success btn-block font-weight-bold">
                                                    <i class="fas fa-check mr-1"></i> Setujui
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-sm btn-outline-danger flex-grow-1" data-toggle="modal" data-target="#rejectWdModal{{ $wd->id }}">
                                                <i class="fas fa-times mr-1"></i> Tolak
                                            </button>
                                        </div>

                                        <!-- Modal Tolak Penarikan -->
                                        <div class="modal fade" id="rejectWdModal{{ $wd->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('admin.withdrawals.reject', $wd->id) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title font-weight-bold text-danger">Tolak Penarikan Dana</h5>
                                                            <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <p class="small text-muted">Saldo sebesar Rp {{ number_format($wd->jumlah, 0, ',', '.') }} akan otomatis dikembalikan ke dompet pemilik.</p>
                                                            <label class="small font-weight-bold">Alasan Penolakan:</label>
                                                            <textarea name="catatan" rows="3" required class="form-control"
                                                                placeholder="Contoh: Nomor rekening tidak valid / nama tidak sesuai."></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger btn-sm font-weight-bold">Konfirmasi Tolak</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="small text-muted font-italic">Telah diproses {{ $wd->tanggal_diproses ? $wd->tanggal_diproses->format('d/m/Y') : '' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

