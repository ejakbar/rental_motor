@extends('layouts.app')

@section('title', 'Manajemen Pengguna Platform')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Manajemen Pengguna Platform</h1>
        <p class="small text-muted mb-0">Daftar seluruh akun Admin, Pemilik Kendaraan, dan Penyewa</p>
    </div>
</div>

<div class="mb-4">
    <div class="btn-group" role="group">
        <a href="{{ route('admin.users.index') }}" class="btn {{ !request('role') ? 'btn-primary' : 'btn-outline-primary' }}">
            Semua Pengguna
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="btn {{ request('role') === 'admin' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-crown text-warning mr-1"></i> Admin
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'pemilik']) }}" class="btn {{ request('role') === 'pemilik' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-motorcycle text-dark mr-1"></i> Pemilik
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'penyewa']) }}" class="btn {{ request('role') === 'penyewa' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-users text-success mr-1"></i> Penyewa
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Akun Terdaftar</h6>
        <span class="small text-muted">Total: {{ $users->total() }} Akun</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Pengguna</th>
                        <th>Email & Kontak</th>
                        <th>Peran (Role)</th>
                        <th>Status KYC</th>
                        <th>Saldo / Rekening</th>
                        <th>Tgl Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>#{{ $user->id }}</td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $user->nama }}</div>
                            </td>
                            <td>
                                <div class="small">{{ $user->email }}</div>
                                <div class="small text-muted">{{ $user->no_telp ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $user->isAdmin() ? 'badge-warning text-dark font-weight-bold' : ($user->isPemilik() ? 'badge-dark font-weight-bold' : 'badge-success font-weight-bold') }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->status_kyc === 'verified')
                                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Verified</span>
                                @elseif($user->status_kyc === 'pending')
                                    <span class="badge badge-warning text-dark"><i class="fas fa-clock mr-1"></i>Pending</span>
                                @elseif($user->status_kyc === 'rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-light border">Belum KYC</span>
                                @endif
                            </td>
                            <td>
                                @if($user->isPemilik())
                                    <div class="font-weight-bold text-success small">Rp {{ number_format($user->saldo, 0, ',', '.') }}</div>
                                    <div class="small text-muted">{{ $user->nama_bank }} - {{ $user->no_rekening }}</div>
                                @else
                                    <span class="small text-muted">-</span>
                                @endif
                            </td>
                            <td class="small">{{ $user->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

