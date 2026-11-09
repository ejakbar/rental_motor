@extends('layouts.app')

@section('title', 'Edit Data Motor')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Edit Motor: {{ $motor->merk }} {{ $motor->tipe_motor }}</h1>
        <p class="small text-muted mb-0">Perbarui spesifikasi atau tarif sewa motor Anda</p>
    </div>
    <a href="{{ route('pemilik.motor.index') }}" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formulir Perubahan Data</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('pemilik.motor.update', $motor->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3">1. Informasi Utama Kendaraan</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Merek Motor</label>
                    <select name="merk" required class="form-control">
                        <option value="Honda" {{ $motor->merk == 'Honda' ? 'selected' : '' }}>Honda</option>
                        <option value="Yamaha" {{ $motor->merk == 'Yamaha' ? 'selected' : '' }}>Yamaha</option>
                        <option value="Vespa" {{ $motor->merk == 'Vespa' ? 'selected' : '' }}>Vespa</option>
                        <option value="Suzuki" {{ $motor->merk == 'Suzuki' ? 'selected' : '' }}>Suzuki</option>
                        <option value="Kawasaki" {{ $motor->merk == 'Kawasaki' ? 'selected' : '' }}>Kawasaki</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Model / Tipe Motor</label>
                    <input type="text" name="tipe_motor" value="{{ old('tipe_motor', $motor->tipe_motor) }}" required class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Kapasitas Mesin (CC)</label>
                    <input type="text" name="tipe_cc" value="{{ old('tipe_cc', $motor->tipe_cc) }}" required class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Tahun Keluaran</label>
                    <input type="number" name="tahun" value="{{ old('tahun', $motor->tahun) }}" required class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Nomor Polisi (Plat Nomor)</label>
                    <input type="text" name="no_plat" value="{{ old('no_plat', $motor->no_plat) }}" required class="form-control text-uppercase">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Warna Kendaraan</label>
                    <input type="text" name="warna" value="{{ old('warna', $motor->warna) }}" class="form-control">
                </div>
            </div>

            <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3 mt-4">2. Tarif Sewa</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Tarif Sewa Harian (Rp)</label>
                    <input type="number" name="tarif_harian" value="{{ old('tarif_harian', (int)$motor->tarif_harian) }}" required class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Tarif Mingguan (Rp)</label>
                    <input type="number" name="tarif_mingguan" value="{{ old('tarif_mingguan', (int)$motor->tarif_mingguan) }}" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="small font-weight-bold">Tarif Bulanan (Rp)</label>
                    <input type="number" name="tarif_bulanan" value="{{ old('tarif_bulanan', (int)$motor->tarif_bulanan) }}" class="form-control">
                </div>
            </div>

            <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3 mt-4">3. Perbarui Foto & Dokumen (Kosongkan jika tidak diubah)</h6>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Foto Depan</label>
                    <input type="file" name="foto_depan" accept="image/*" class="form-control-file">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Foto Samping</label>
                    <input type="file" name="foto_samping" accept="image/*" class="form-control-file">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Foto Belakang</label>
                    <input type="file" name="foto_belakang" accept="image/*" class="form-control-file">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Foto STNK</label>
                    <input type="file" name="foto_stnk" accept="image/*" class="form-control-file">
                </div>
            </div>

            <div class="mb-4">
                <label class="small font-weight-bold">Deskripsi & Kelengkapan</label>
                <textarea name="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $motor->deskripsi) }}</textarea>
            </div>

            <div class="border-top pt-3 text-right">
                <a href="{{ route('pemilik.motor.index') }}" class="btn btn-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-primary font-weight-bold px-4">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

