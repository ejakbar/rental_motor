@extends('layouts.app')

@section('title', 'Verifikasi KYC Identitas Penyewa')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Verifikasi KYC Identitas Penyewa</h1>
        <p class="small text-muted mb-0">Tinjau KTP, SIM C, dan swafoto penyewa untuk mencegah penipuan aset motor mitra</p>
    </div>
</div>

<!-- Tabs Status Filter KYC -->
<div class="mb-4">
    <div class="btn-group" role="group">
        <a href="{{ route('admin.kyc.index', ['status' => 'pending']) }}" class="btn {{ $status === 'pending' ? 'btn-warning text-dark font-weight-bold' : 'btn-outline-warning' }}">
            <i class="fas fa-clock mr-1"></i> Antrean Pending (Perlu Ditinjau)
        </a>
        <a href="{{ route('admin.kyc.index', ['status' => 'verified']) }}" class="btn {{ $status === 'verified' ? 'btn-success' : 'btn-outline-success' }}">
            <i class="fas fa-check-circle mr-1"></i> Terverifikasi (Verified)
        </a>
        <a href="{{ route('admin.kyc.index', ['status' => 'rejected']) }}" class="btn {{ $status === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
            <i class="fas fa-times-circle mr-1"></i> Ditolak
        </a>
        <a href="{{ route('admin.kyc.index', ['status' => 'all']) }}" class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
            Semua Penyewa
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Dokumen KYC Pengguna (Filter: {{ strtoupper($status) }})</h6>
        <span class="small text-muted">Total: {{ $renters->total() }} Pengguna</span>
    </div>
    <div class="card-body">
        @if($renters->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-id-card fa-3x text-gray-300 mb-3"></i>
                <h5>Tidak Ada Pengguna dalam Kategori Ini</h5>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Data Penyewa</th>
                            <th>No. KTP & No. SIM C</th>
                            <th>Dokumen Terlampir</th>
                            <th>Status KYC</th>
                            <th style="width: 200px;">Aksi Verifikasi Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($renters as $renter)
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $renter->nama }}</div>
                                    <div class="small text-muted">{{ $renter->email }}</div>
                                    <div class="small text-primary font-weight-bold">
                                        <i class="fas fa-phone mr-1"></i>{{ $renter->no_telp ?? '-' }}
                                    </div>
                                    @if($renter->catatan_kyc)
                                        <div class="alert alert-secondary p-1 mt-1 mb-0 small">
                                            <strong>Catatan:</strong> {{ $renter->catatan_kyc }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        <strong>NIK / KTP:</strong> <span class="font-mono">{{ $renter->no_ktp ?? '-' }}</span>
                                    </div>
                                    <div class="small mt-1">
                                        <strong>No. SIM C:</strong> <span class="font-mono text-primary font-weight-bold">{{ $renter->no_sim ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($renter->foto_ktp)
                                            <a href="{{ Str::startsWith($renter->foto_ktp, 'http') ? $renter->foto_ktp : asset('storage/' . $renter->foto_ktp) }}" target="_blank" class="btn btn-outline-dark" title="Lihat Foto KTP">
                                                <i class="fas fa-id-card mr-1 text-warning"></i> KTP
                                            </a>
                                        @endif
                                        @if($renter->foto_sim)
                                            <a href="{{ Str::startsWith($renter->foto_sim, 'http') ? $renter->foto_sim : asset('storage/' . $renter->foto_sim) }}" target="_blank" class="btn btn-outline-primary" title="Lihat Foto SIM">
                                                <i class="fas fa-id-badge mr-1"></i> SIM C
                                            </a>
                                        @endif
                                        @if($renter->foto_selfie)
                                            <a href="{{ Str::startsWith($renter->foto_selfie, 'http') ? $renter->foto_selfie : asset('storage/' . $renter->foto_selfie) }}" target="_blank" class="btn btn-outline-secondary" title="Lihat Selfie">
                                                <i class="fas fa-user mr-1"></i> Selfie
                                            </a>
                                        @endif
                                    </div>
                                    @if(!$renter->foto_ktp && !$renter->foto_sim)
                                        <span class="small text-muted font-italic">Belum upload file</span>
                                    @endif
                                </td>
                                <td>
                                    @if($renter->status_kyc === 'verified')
                                        <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>Terverifikasi</span>
                                    @elseif($renter->status_kyc === 'pending')
                                        <span class="badge badge-warning text-dark"><i class="fas fa-clock mr-1"></i>Menunggu Verifikasi</span>
                                    @elseif($renter->status_kyc === 'rejected')
                                        <span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i>Ditolak</span>
                                    @else
                                        <span class="badge badge-light border text-muted">Belum Verifikasi</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if($renter->status_kyc !== 'verified')
                                            <form method="POST" action="{{ route('admin.kyc.approve', $renter->id) }}" class="flex-grow-1 mr-1">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success btn-block" title="Setujui KYC">
                                                    <i class="fas fa-check mr-1"></i> Approve
                                                </button>
                                            </form>
                                        @endif

                                        <button type="button" class="btn btn-sm btn-outline-danger flex-grow-1" data-toggle="modal" data-target="#rejectKycModal{{ $renter->id }}">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                        </button>
                                    </div>

                                    <!-- Modal Tolak KYC Sesuai PRD -->
                                    <div class="modal fade" id="rejectKycModal{{ $renter->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.kyc.reject', $renter->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-bold text-danger">Tolak Verifikasi KYC</h5>
                                                        <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                        <label class="small font-weight-bold">Alasan Penolakan Identitas:</label>
                                                        <textarea name="catatan_kyc" rows="3" required class="form-control"
                                                            placeholder="Contoh: Foto SIM C buram, masa berlaku SIM habis, atau selfie tidak sesuai foto KTP."></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger btn-sm font-weight-bold">Konfirmasi Tolak KYC</button>
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
                {{ $renters->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

