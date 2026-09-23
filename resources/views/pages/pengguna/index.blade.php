@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Pengguna</h1>
            <div>
                <a href="/pengguna/create" class="d-none d-sm-inline-block btn btn-plus btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Tambah Data</a>
            </div>
    </div>

    {{-- Table --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-fixed table-bordered table-hovered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>E-Mail</th>
                                <th>No. Telepon</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>    
                        </thead>

                        @if (count($penggunas) < 1)
                            <tbody>
                                <tr>
                                    <td colspan="5"> <p class="text-center">Data tidak tersedia</p>
                                    </td>
                                </tr>
                            </tbody>
                        @else
                            <tbody>
                                @foreach ($penggunas as $item)
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->no_telp }}</td>
                                        <td>{{ $item->role }}</td>
                                        <td>
                                            <a href="/pengguna/{{ $item->id }}" class="btn btn-warning btn-sm ml-2">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a href="/pengguna/{{ $item->id }}" class="btn btn-danger btn-sm ml-2">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>                                 
                                @endforeach                            
                            </tbody>  
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection