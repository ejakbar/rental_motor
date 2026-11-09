@extends('layouts.app')

@section('title', 'Tambah Motor Baru')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Daftarkan Motor Baru</h1>
        <p class="small text-muted mb-0">Isi kelengkapan data kendaraan Anda. Motor akan masuk ke antrean kurasi Admin sebelum terbit di katalog.</p>
    </div>
    <a href="{{ route('pemilik.motor.index') }}" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formulir Pendaftaran Kendaraan Sesuai PRD</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('pemilik.motor.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Section 1: Informasi Utama Kendaraan -->
            <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3">1. Informasi Utama Kendaraan</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Merek Motor <span class="text-danger">*</span></label>
                    <select name="merk" required class="form-control @error('merk') is-invalid @enderror">
                        <option value="">-- Pilih Merek --</option>
                        <option value="Honda" {{ old('merk') == 'Honda' ? 'selected' : '' }}>Honda</option>
                        <option value="Yamaha" {{ old('merk') == 'Yamaha' ? 'selected' : '' }}>Yamaha</option>
                        <option value="Vespa" {{ old('merk') == 'Vespa' ? 'selected' : '' }}>Vespa</option>
                        <option value="Suzuki" {{ old('merk') == 'Suzuki' ? 'selected' : '' }}>Suzuki</option>
                        <option value="Kawasaki" {{ old('merk') == 'Kawasaki' ? 'selected' : '' }}>Kawasaki</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Model / Tipe Motor <span class="text-danger">*</span></label>
                    <input type="text" name="tipe_motor" value="{{ old('tipe_motor') }}" required placeholder="Contoh: Vario 160 ABS / NMAX Connected"
                        class="form-control @error('tipe_motor') is-invalid @enderror">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Kapasitas Mesin (CC) <span class="text-danger">*</span></label>
                    <input type="text" name="tipe_cc" value="{{ old('tipe_cc') }}" required placeholder="Contoh: 125, 150, 160"
                        class="form-control @error('tipe_cc') is-invalid @enderror">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Tahun Keluaran <span class="text-danger">*</span></label>
                    <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" min="2012" max="{{ date('Y') + 1 }}" required
                        class="form-control @error('tahun') is-invalid @enderror">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Nomor Polisi (Plat Nomor) <span class="text-danger">*</span></label>
                    <input type="text" name="no_plat" value="{{ old('no_plat') }}" required placeholder="Contoh: B 1234 XYZ"
                        class="form-control text-uppercase @error('no_plat') is-invalid @enderror">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Warna Kendaraan</label>
                    <input type="text" name="warna" value="{{ old('warna') }}" placeholder="Contoh: Hitam Doff"
                        class="form-control">
                </div>
            </div>

            <!-- Section 2: Tarif Sewa Sesuai PRD -->
            <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3 mt-4">2. Penentuan Tarif Sewa</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Tarif Sewa Harian (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="tarif_harian" value="{{ old('tarif_harian') }}" required placeholder="Contoh: 100000"
                        class="form-control @error('tarif_harian') is-invalid @enderror">
                    <small class="text-muted">Tarif standar per 24 jam</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Tarif Sewa Mingguan (Opsional)</label>
                    <input type="number" name="tarif_mingguan" value="{{ old('tarif_mingguan') }}" placeholder="Contoh: 600000"
                        class="form-control">
                    <small class="text-muted">Diskon untuk penyewaan 7 hari</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Tarif Sewa Bulanan (Opsional)</label>
                    <input type="number" name="tarif_bulanan" value="{{ old('tarif_bulanan') }}" placeholder="Contoh: 2200000"
                        class="form-control">
                    <small class="text-muted">Untuk penyewaan 30 hari</small>
                </div>
            </div>

            <!-- Section 3: Foto Kendaraan & Dokumen STNK Sesuai PRD -->
            <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3 mt-4">
                3. Foto Kendaraan (Depan, Samping, Belakang) & STNK <span class="text-danger">*</span>
            </h6>
            <div class="alert alert-info small">
                <i class="fas fa-info-circle mr-1"></i> Sesuai standar kurasi PRD RentRide, harap unggah foto fisik motor dari berbagai sudut serta foto STNK untuk verifikasi keaslian oleh Admin.
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Foto Tampak Depan</label>
                    <input type="file" name="foto_depan" accept="image/*" class="form-control-file">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Foto Tampak Samping</label>
                    <input type="file" name="foto_samping" accept="image/*" class="form-control-file">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Foto Tampak Belakang</label>
                    <input type="file" name="foto_belakang" accept="image/*" class="form-control-file">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold text-primary">Foto STNK Asli (Kurasi Admin)</label>
                    <input type="file" name="foto_stnk" accept="image/*" class="form-control-file">
                </div>
            </div>

            <div class="mb-4">
                <label class="small font-weight-bold">Deskripsi & Fasilitas Tambahan</label>
                <textarea name="deskripsi" rows="3" placeholder="Jelaskan kondisi mesin, kelengkapan helm, jas hujan, aturan bensin, dll."
                    class="form-control">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="border-top pt-3 text-right">
                <a href="{{ route('pemilik.motor.index') }}" class="btn btn-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-primary font-weight-bold px-4">
                    <i class="fas fa-save mr-1"></i> Daftarkan Motor ke Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

