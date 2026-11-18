
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <x-dashboard-header
        title="Edit Data Karyawan"
        subtitle="Perbarui informasi Admin atau Kasir yang bekerja di lapangan futsal milikmu."
    />

    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-body p-4">
            <form action="{{ route('pemilik.karyawan.update', $karyawan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap"
                        class="form-control @error('nama_lengkap') is-invalid @enderror"
                        value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" required>
                    @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $karyawan->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username"
                        class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username', $karyawan->username) }}" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor Telepon</label>
                    <input type="text" name="nomor_telepon"
                        class="form-control @error('nomor_telepon') is-invalid @enderror"
                        value="{{ old('nomor_telepon', $karyawan->nomor_telepon) }}">
                    @error('nomor_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Jenis Karyawan</label>
                    <select name="role"
                        class="form-select @error('role') is-invalid @enderror" required>
                        <option value="admin"
                            {{ old('role', $karyawan->role->value) === 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                        <option value="kasir"
                            {{ old('role', $karyawan->role->value) === 'kasir' ? 'selected' : '' }}>
                            Kasir
                        </option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('pemilik.karyawan.index') }}"
                        class="btn btn-light rounded-3">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-success rounded-3">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

