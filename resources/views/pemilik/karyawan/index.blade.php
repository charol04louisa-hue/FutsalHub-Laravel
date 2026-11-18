@extends('layouts.app')

@section('content')
<div class="container py-5">
    <x-dashboard-header
        title="Manajemen Karyawan"
        subtitle="Kelola akun Admin dan Kasir yang bekerja di lapangan futsal milikmu."
    />

    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Karyawan</h5>
                    <p class="text-muted mb-0">
                        Total {{ $karyawan->count() }} karyawan
                    </p>
                </div>
            </div>

            @if ($karyawan->isEmpty())
                <div class="text-center py-5">
                    <h6 class="fw-bold">Belum Ada Karyawan</h6>
                    <p class="text-muted mb-0">
                        Admin dan Kasir yang kamu tambahkan akan muncul di sini.
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karyawan as $pegawai)
                                <tr>
                                    <td>{{ $pegawai->nama_lengkap }}</td>
                                    <td>{{ $pegawai->username }}</td>
                                    <td>{{ ucfirst($pegawai->role->value ?? $pegawai->role) }}</td>
                                    <td>
                                        @if ($pegawai->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection