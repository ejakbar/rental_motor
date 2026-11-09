@extends('layouts.app')

@section('title', 'Tinjauan Kurasi Motor - ' . $motor->no_plat)

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Tinjauan Kurasi Unit: {{ $motor->merk }} {{ $motor->tipe_motor }}</h1>
        <p class="small text-muted mb-0">Periksa keabsahan foto fisik, kesesuaian plat nomor, dan dokumen STNK sebelum approval</p>
    </div>
    <a href="{{ route('admin.motor.index') }}" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali ke Antrean
    </a>
</div>

<div class="row">
    <!-- Kolom Kiri: Galeri Foto Kendaraan & Dokumen STNK (7 Cols) -->
    <div class="col-lg-7 mb-4">
        <!-- Dokumen STNK (Kurasi Utama Sesuai PRD) -->
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-contract mr-1"></i> Dokumen STNK Kendaraan
                </h6>
                <span class="badge badge-success font-weight-bold">STNK {{ $motor->dokumen_kepemilikan }}</span>
            </div>
            <div class="card-body text-center">
                @if($motor->foto_stnk)
                    <img src="{{ asset('storage/' . $motor->foto_stnk) }}" class="img-fluid rounded border shadow-sm mb-2" style="max-height: 350px;">
                    <div class="small text-muted">Periksa kesesuaian Nomor Polisi <strong>{{ $motor->no_plat }}</strong> pada lembar STNK di atas.</div>
                @else
                    <div class="p-4 bg-light rounded text-muted">
                        <i class="fas fa-image fa-3x mb-2 text-gray-300"></i>
                        <p class="mb-0">Pemilik menyertakan dokumen fisik terverifikasi offline / gambar standar.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Foto Fisik Depan, Samping, Belakang Sesuai PRD -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Foto Fisik Kendaraan (3 Sudut Sesuai PRD)</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Depan -->
                    <div class="col-md-4 mb-3 text-center">
                        <div class="small font-weight-bold text-dark mb-1">Tampak Depan</div>
                        <img src="{{ $motor->foto_depan ? asset('storage/' . $motor->foto_depan) : $motor->foto_utama_url }}" class="img-fluid rounded border" style="height: 140px; width: 100%; object-fit: cover;">
                    </div>
                    <!-- Samping -->
                    <div class="col-md-4 mb-3 text-center">
                        <div class="small font-weight-bold text-dark mb-1">Tampak Samping</div>
                        <img src="{{ $motor->foto_samping ? asset('storage/' . $motor->foto_samping) : $motor->foto_utama_url }}" class="img-fluid rounded border" style="height: 140px; width: 100%; object-fit: cover;">
                    </div>
                    <!-- Belakang -->
                    <div class="col-md-4 mb-3 text-center">
                        <div class="small font-weight-bold text-dark mb-1">Tampak Belakang</div>
                        <img src="{{ $motor->foto_belakang ? asset('storage/' . $motor->foto_belakang) : $motor->foto_utama_url }}" class="img-fluid rounded border" style="height: 140px; width: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Spesifikasi, Data Pemilik, & Keputusan Admin (5 Cols) -->
    <div class="col-lg-5 mb-4">
        <!-- Card Keputusan Approval -->
        <div class="card shadow mb-4 border-left-warning">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tindakan Kurasi Admin</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="small font-weight-bold text-muted d-block">Status Saat Ini:</span>
                    @if($motor->status_approval === 'approved')
                        <span class="badge badge-success p-2"><i class="fas fa-check-circle mr-1"></i>Disetujui (Tayang di Publik)</span>
                    @elseif($motor->status_approval === 'pending')
                        <span class="badge badge-warning text-dark p-2"><i class="fas fa-clock mr-1"></i>Menunggu Keputusan Admin</span>
                    @else
                        <span class="badge badge-danger p-2"><i class="fas fa-times-circle mr-1"></i>Ditolak</span>
                    @endif
                </div>

                <div class="d-flex gap-2 mb-3">
                    <form method="POST" action="{{ route('admin.motor.approve', $motor->id) }}" class="flex-grow-1 mr-2">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block font-weight-bold">
                            <i class="fas fa-check-circle mr-1"></i> Setujui & Terbitkan
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger flex-grow-1 font-weight-bold" data-toggle="modal" data-target="#rejectModal">
                        <i class="fas fa-times-circle mr-1"></i> Tolak
                    </button>
                </div>
            </div>
        </div>

        <!-- Spesifikasi Kendaraan -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Spesifikasi Unit</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted small" style="width: 130px;">Merek / Model:</td>
                        <td class="font-weight-bold text-dark">{{ $motor->merk }} {{ $motor->tipe_motor }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Nomor Polisi:</td>
                        <td class="font-mono font-weight-bold text-primary h6 mb-0">{{ $motor->no_plat }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Kapasitas Mesin:</td>
                        <td class="font-weight-bold">{{ $motor->tipe_cc }} CC</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Tahun:</td>
                        <td class="font-weight-bold">{{ $motor->tahun }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Warna:</td>
                        <td>{{ $motor->warna ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Tarif Harian:</td>
                        <td class="font-weight-bold text-success h6 mb-0">Rp {{ number_format($motor->tarif_harian, 0, ',', '.') }}</td>
                    </tr>
                </table>

                <hr>
                <h6 class="small font-weight-bold text-dark">Data Pemilik Motor:</h6>
                <div class="p-3 bg-light rounded">
                    <div class="font-weight-bold">{{ $motor->pemilik->nama ?? 'Mitra' }}</div>
                    <div class="small text-muted">{{ $motor->pemilik->email ?? '-' }}</div>
                    <div class="small text-primary font-weight-bold mt-1">
                        <i class="fab fa-whatsapp mr-1"></i>{{ $motor->pemilik->no_telp ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.motor.reject', $motor->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold text-danger">Tolak Pengajuan Motor</h5>
                    <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-left">
                    <label class="small font-weight-bold">Alasan Penolakan (Akan Dibaca Pemilik):</label>
                    <textarea name="catatan_approval" rows="3" required class="form-control"
                        placeholder="Contoh: Foto STNK tidak jelas / buram, masa berlaku pajak habis."></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm font-weight-bold">Konfirmasi Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

