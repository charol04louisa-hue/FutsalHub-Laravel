
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

                <a href="{{ route('pemilik.karyawan.create') }}"
                    class="btn btn-success rounded-3">
                    <i class="bi bi-plus-lg me-1"></i>
                    Tambah Karyawan
                </a>
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
                                <th>Aksi</th>
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
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('pemilik.karyawan.edit', $pegawai->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-3">
                                                Edit
                                            </a>

                                            <form action="{{ route('pemilik.karyawan.toggle-status', $pegawai->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah kamu yakin ingin mengubah status akun karyawan ini?')">
                                                @csrf
                                                @method('PATCH')

                                                @if ($pegawai->is_active)
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                                        Nonaktifkan
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-3">
                                                        Aktifkan
                                                    </button>
                                                @endif
                                            </form>

                                            <form action="{{ route('pemilik.karyawan.destroy', $pegawai->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah kamu yakin ingin menghapus akun karyawan ini? Tindakan ini tidak dapat dibatalkan.')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
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
