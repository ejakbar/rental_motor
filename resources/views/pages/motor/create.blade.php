@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Motor</h1>
    </div>

    <div class="row">
        <div class="col">
            <form action="" method="post">
                
                <div class="card">
                    <div class="card-body">
                        <div class="form_group mb-3">
                            <label for="foto">Foto Motor</label>
                            <input type="file" class="form-control" accept="image/*" id="inputGambar">
                            <img id="preview" src="" alt="Preview gambar" style="max-width: 300px; display:none;">
                        </div>
                        <div class="form-group mb-3">
                            <label for="merk">Merk</label>
                            <input type="text" inputmode="text" class="form-control" id="merk" name="merk" placeholder="Masukkan Merk Motor Anda">
                        </div>
                        <div class="form-group mb-3">
                            <label for="tipe_cc"> Tipe CC</label>
                            <select name="tipe_cc" id="tipe_cc" class="form-control">
                                <option value="100">100 CC</option>
                                <option value="125">125 CC</option>
                                <option value="150">150 CC</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="no_plat">Plat Nomor</label>
                            <input type="string" inputmode="string" class="form-control" id="no_plat" name="no_plat" placeholder="Masukkan Plat Motor Anda">
                        </div>
                        <div class="form-group mb-3">
                            <label for="status"> Status Motor</label>
                            <select name="status" id="status" class="form-control">
                                <option value="tersedia">Tersedia</option>
                                <option value="disewa">Disewa</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="dokumen_kepemilikan"> Tipe CC</label>
                            <select name="tipe_cc" id="tipe_cc" class="form-control">
                                <option value="lengkap">Lengkap</option>
                                <option value="tidak_lengkap">Tidak Lengkap</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tarif_harian">Tarif Harga</label>
                            <input type="num" inputmode="num" class="form-control" id="tarif_harian" name="tarif_harian" placeholder="Masukkan Tarif Sewa">
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end" style="gap: 10px">
                            <a href="/motor" class="btn btn-outline-secondary">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button> 
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection