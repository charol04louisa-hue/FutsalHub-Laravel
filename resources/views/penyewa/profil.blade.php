
@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="mb-4">
        <h2 class="fw-bold">Profil Saya</h2>
        <p class="text-muted">Kelola informasi pribadi dan keamanan akun kamu.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- INFORMASI PROFIL --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-1">Informasi Pribadi</h5>
                    <p class="text-muted small mb-4">
                        Perbarui informasi akun kamu.
                    </p>

                    <form action="{{ route('penyewa.profil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nama Lengkap
                            </label>
                            <input
                                type="text"
                                name="nama_lengkap"
                                class="form-control @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                                maxlength="120"
                                required
                            >
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}"
                                maxlength="190"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Nomor Telepon
                            </label>
                            <input
                                type="text"
                                name="nomor_telepon"
                                class="form-control @error('nomor_telepon') is-invalid @enderror"
                                value="{{ old('nomor_telepon', $user->nomor_telepon) }}"
                                maxlength="30"
                                placeholder="Contoh: 081234567890"
                            >
                            @error('nomor_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                        </button>
                    </form>

                </div>
            </div>
        </div>

        {{-- GANTI PASSWORD --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-1">Keamanan Akun</h5>
                    <p class="text-muted small mb-4">
                        Ganti password secara berkala untuk menjaga keamanan akun.
                    </p>

                    <form action="{{ route('penyewa.profil.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Password Lama
                            </label>
                            <input
                                type="password"
                                name="password_lama"
                                class="form-control @error('password_lama') is-invalid @enderror"
                                required
                            >
                            @error('password_lama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Password Baru
                            </label>
                            <input
                                type="password"
                                name="password_baru"
                                class="form-control @error('password_baru') is-invalid @enderror"
                                minlength="8"
                                required
                            >
                            @error('password_baru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Konfirmasi Password Baru
                            </label>
                            <input
                                type="password"
                                name="password_baru_confirmation"
                                class="form-control"
                                minlength="8"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Ganti Password
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
