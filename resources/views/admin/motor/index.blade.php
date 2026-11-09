@extends('layouts.app')

@section('title', 'Approval System & Manajemen Motor')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Kurasi & Manajemen Ketersediaan Motor</h1>
        <p class="small text-muted mb-0">Verifikasi kelayakan motor baru dari pemilik sebelum dipublikasikan ke katalog publik</p>
    </div>
</div>

<!-- Tabs Status Filter Sesuai PRD -->
<div class="mb-4">
    <div class="btn-group" role="group">
        <a href="{{ route('admin.motor.index', ['status' => 'all']) }}" class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
            Semua Motor
        </a>
        <a href="{{ route('admin.motor.index', ['status' => 'pending']) }}" class="btn {{ $status === 'pending' ? 'btn-warning text-dark font-weight-bold' : 'btn-outline-warning' }}">
            <i class="fas fa-clock mr-1"></i> Antrean Pending Approval
        </a>
        <a href="{{ route('admin.motor.index', ['status' => 'approved']) }}" class="btn {{ $status === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
            <i class="fas fa-check-circle mr-1"></i> Approved (Tayang)
        </a>
        <a href="{{ route('admin.motor.index', ['status' => 'rejected']) }}" class="btn {{ $status === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
            <i class="fas fa-times-circle mr-1"></i> Ditolak
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Kendaraan (Filter: {{ strtoupper($status) }})</h6>
        <span class="small text-muted">Total: {{ $motors->total() }} Motor</span>
    </div>
    <div class="card-body">
        @if($motors->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-motorcycle fa-3x text-gray-300 mb-3"></i>
                <h5>Tidak Ada Motor dengan Filter Ini</h5>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 80px;">Foto</th>
                            <th>Motor & Pemilik</th>
                            <th>No. Plat & Tahun</th>
                            <th>Tarif Harian</th>
                            <th>Status Kurasi Admin</th>
                            <th>Status Operasional</th>
                            <th style="width: 220px;">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($motors as $motor)
                            <tr>
                                <td>
                                    <img src="{{ $motor->foto_utama_url }}" class="rounded shadow-sm" style="width: 80px; height: 55px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="font-weight-bold text-primary">{{ $motor->merk }} {{ $motor->tipe_motor }}</div>
                                    <div class="small text-muted">
                                        Pemilik: <strong>{{ $motor->pemilik->nama ?? 'Mitra' }}</strong> ({{ $motor->pemilik->no_telp ?? '-' }})
                                    </div>
                                    @if($motor->catatan_approval)
                                        <div class="alert alert-danger p-1 mt-1 mb-0 small">
                                            <strong>Catatan:</strong> {{ $motor->catatan_approval }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-mono font-weight-bold text-dark">{{ $motor->no_plat }}</span>
                                    <div class="small text-muted">{{ $motor->tipe_cc }} CC &bull; {{ $motor->tahun }}</div>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">
                                        Rp {{ number_format($motor->tarif_harian, 0, ',', '.') }}
                                    </div>
                                    <span class="small text-muted">/hari</span>
                                </td>
                                <td>
                                    @if($motor->status_approval === 'approved')
                                        <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>Approved</span>
                                    @elseif($motor->status_approval === 'pending')
                                        <span class="badge badge-warning text-dark"><i class="fas fa-clock mr-1"></i>Pending</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i>Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$motor->is_active)
                                        <span class="badge badge-danger"><i class="fas fa-ban mr-1"></i>Dinonaktifkan Paksa</span>
                                    @else
                                        <span class="badge badge-dark"><i class="fas fa-circle mr-1 text-success"></i>Aktif ({{ $motor->status }})</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <a href="{{ route('admin.motor.detail', $motor->id) }}" class="btn btn-xs btn-dark mb-1">
                                            <i class="fas fa-search mr-1 text-warning"></i> Tinjau STNK & Fisik
                                        </a>

                                        @if($motor->status_approval === 'pending')
                                            <div class="d-flex gap-1">
                                                <form method="POST" action="{{ route('admin.motor.approve', $motor->id) }}" class="flex-grow-1 mr-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-success btn-block" title="Setujui Motor">
                                                        <i class="fas fa-check mr-1"></i> Approve
                                                    </button>
                                                </form>

                                                <button type="button" class="btn btn-xs btn-danger flex-grow-1" data-toggle="modal" data-target="#rejectModal{{ $motor->id }}">
                                                    <i class="fas fa-times mr-1"></i> Tolak
                                                </button>
                                            </div>
                                        @endif

                                        <!-- Toggle Active Secara Paksa Sesuai PRD -->
                                        <form method="POST" action="{{ route('admin.motor.toggle', $motor->id) }}" class="mt-1">
                                            @csrf
                                            <button type="submit" class="btn btn-xs {{ $motor->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} btn-block"
                                                onclick="return confirm('Apakah Anda yakin ingin {{ $motor->is_active ? 'menonaktifkan motor ini secara paksa' : 'mengaktifkan kembali motor ini' }}?')">
                                                <i class="fas {{ $motor->is_active ? 'fa-ban' : 'fa-check' }} mr-1"></i>
                                                {{ $motor->is_active ? 'Nonaktifkan Paksa' : 'Aktifkan Kembali' }}
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal Tolak Motor Sesuai PRD -->
                                    <div class="modal fade" id="rejectModal{{ $motor->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.motor.reject', $motor->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-bold text-danger">Tolak Pengajuan Motor</h5>
                                                        <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                        <p class="small text-muted">
                                                            Berikan catatan alasan penolakan agar Pemilik dapat memperbarui dokumen kendaraan:
                                                        </p>
                                                        <textarea name="catatan_approval" rows="3" required class="form-control"
                                                            placeholder="Contoh: Foto STNK buram, pajak plat nomor kedaluwarsa, atau foto unit tidak sesuai."></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger btn-sm font-weight-bold">Konfirmasi Tolak Motor</button>
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
                {{ $motors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

