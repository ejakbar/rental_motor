@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Pengguna</h1>
    </div>

@if ($errors->any())
  <div class="alert alert-danger">
    <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

    <div class="row">
        <div class="col">
            <form action="/pengguna/{{ $penggunas -> id }}" method="put">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">Nama</label>
                            <input type="text" inputmode="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama Lengkap">
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">E-mail</label>
                            <input type="mail" inputmode="mail" class="form-control" id="email" name="email" placeholder="Masukkan E-mail">
                        </div>
                        <div class="form-group mb-3">
                            <label for="no_telp">No. Telepon</label>
                            <input type="number" inputmode="numeric" class="form-control" id="no_telp" name="no_telp" placeholder="Masukkan No.Telepon">
                        </div>
                        <div class="form-group mb-3">
                            <label for="role"> Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="role">Admin</option>
                                <option value="role">Pemilik</option>
                                <option value="role">Pengguna</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end" style="gap: 10px">
                            <a href="/pengguna" class="btn btn-outline-secondary">
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