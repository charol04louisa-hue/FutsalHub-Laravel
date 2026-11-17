
@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-5">

    {{-- HEADER PROFIL --}}
    <div class="mb-4">
        <div class="text-uppercase small fw-bold text-secondary mb-2">
            FUTSALHUB / AKUN SAYA
        </div>

        <h2 class="fw-bold mb-2">Profil Saya</h2>

        <p class="text-secondary mb-0">
            Kelola informasi pribadi dan keamanan akun kamu.
        </p>
    </div>

    {{-- NOTIFIKASI BERHASIL --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup">
            </button>
        </div>
    @endif

    {{-- RINGKASAN PROFIL --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 flex-wrap">

                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width: 76px; height: 76px; background: #eaf1ff; color: #2563eb; font-size: 30px; font-weight: 700;">
                    {{ strtoupper(substr($user->nama_lengkap ?: 'P', 0, 1)) }}
                </div>

                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-1">
                        {{ $user->nama_lengkap ?: 'Penyewa' }}
                    </h4>

                    <p class="text-secondary mb-2">
                        {{ $user->email }}
                    </p>

                    <span class="badge rounded-pill px-3 py-2"
                          style="background: #eaf1ff; color: #2563eb;">
                        <span class="me-1">●</span> Akun Penyewa
                    </span>
                </div>

            </div>
        </div>
    </div>

    {{-- KONTEN PROFIL --}}
    <div class="row g-4 align-items-start">

        {{-- INFORMASI PRIBADI --}}
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 p-md-4">

                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="fs-5">👤</span>
                            <h5 class="fw-bold mb-0">
                                Informasi Pribadi
                            </h5>
                        </div>

                        <p class="text-secondary small mb-0">
                            Pastikan informasi akun kamu selalu terbaru.
                        </p>
                    </div>

                    <form action="{{ route('penyewa.profil.update') }}"
                          method="POST">

                        @csrf
                        @method('PUT')

                        {{-- NAMA LENGKAP --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                name="nama_lengkap"
                                class="form-control rounded-3 py-2 @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                                maxlength="120"
                                placeholder="Masukkan nama lengkap"
                                required
                            >

                            @error('nama_lengkap')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control rounded-3 py-2 @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}"
                                maxlength="190"
                                placeholder="Masukkan alamat email"
                                required
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- NOMOR TELEPON --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Nomor Telepon
                            </label>

                            <input
                                type="text"
                                name="nomor_telepon"
                                class="form-control rounded-3 py-2 @error('nomor_telepon') is-invalid @enderror"
                                value="{{ old('nomor_telepon', $user->nomor_telepon) }}"
                                maxlength="30"
                                placeholder="Contoh: 081234567890"
                            >

                            @error('nomor_telepon')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                    class="btn btn-primary rounded-3 px-4 py-2">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>

        {{-- KEAMANAN AKUN --}}
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="fs-5">🔒</span>
                            <h5 class="fw-bold mb-0">
                                Keamanan Akun
                            </h5>
                        </div>

                        <p class="text-secondary small mb-0">
                            Ganti password secara berkala untuk menjaga keamanan akun.
                        </p>
                    </div>

                    <form action="{{ route('penyewa.profil.password') }}"
                          method="POST">

                        @csrf
                        @method('PUT')

                        {{-- PASSWORD LAMA --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Password Lama
                            </label>

                            <div class="input-group">
                                <input
                                    type="password"
                                    id="password_lama"
                                    name="password_lama"
                                    class="form-control py-2 rounded-start-3 @error('password_lama') is-invalid @enderror"
                                    placeholder="Masukkan password lama"
                                    autocomplete="current-password"
                                    required
                                >

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary rounded-end-3"
                                    data-password-toggle="password_lama"
                                    aria-label="Tampilkan password lama"
                                    title="Tampilkan password"
                                >
                                    👁
                                </button>

                                @error('password_lama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- PASSWORD BARU --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Password Baru
                            </label>

                            <div class="input-group">
                                <input
                                    type="password"
                                    id="password_baru"
                                    name="password_baru"
                                    class="form-control py-2 rounded-start-3 @error('password_baru') is-invalid @enderror"
                                    placeholder="Masukkan password baru"
                                    minlength="8"
                                    autocomplete="new-password"
                                    required
                                >

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary rounded-end-3"
                                    data-password-toggle="password_baru"
                                    aria-label="Tampilkan password baru"
                                    title="Tampilkan password"
                                >
                                    👁
                                </button>

                                @error('password_baru')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <small class="text-secondary">
                                Minimal 8 karakter.
                            </small>
                        </div>

                        {{-- KONFIRMASI PASSWORD --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Konfirmasi Password Baru
                            </label>

                            <div class="input-group">
                                <input
                                    type="password"
                                    id="password_baru_confirmation"
                                    name="password_baru_confirmation"
                                    class="form-control py-2 rounded-start-3"
                                    placeholder="Ulangi password baru"
                                    minlength="8"
                                    autocomplete="new-password"
                                    required
                                >

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary rounded-end-3"
                                    data-password-toggle="password_baru_confirmation"
                                    aria-label="Tampilkan konfirmasi password"
                                    title="Tampilkan password"
                                >
                                    👁
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                                class="btn btn-primary rounded-3 py-2 w-100">
                            Ganti Password
                        </button>

                    </form>

                    {{-- INFORMASI KEAMANAN --}}
                    <div class="rounded-3 p-3 mt-4"
                         style="background: #f3f6fb;">

                        <div class="d-flex gap-2 align-items-start">
                            <span>🛡️</span>

                            <div>
                                <div class="fw-semibold small">
                                    Tips Keamanan
                                </div>

                                <p class="text-secondary small mb-0 mt-1">
                                    Gunakan password yang kuat dan jangan bagikan password akun kamu kepada orang lain.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

{{-- SCRIPT SHOW / HIDE PASSWORD --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('[data-password-toggle]').forEach(function (button) {

        button.addEventListener('click', function () {

            const inputId = this.getAttribute('data-password-toggle');
            const passwordInput = document.getElementById(inputId);

            if (!passwordInput) {
                return;
            }

            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            this.textContent = isPassword ? '🙈' : '👁';

            this.setAttribute(
                'aria-label',
                isPassword ? 'Sembunyikan password' : 'Tampilkan password'
            );

            this.setAttribute(
                'title',
                isPassword ? 'Sembunyikan password' : 'Tampilkan password'
            );

        });

    });

});
</script>

@endsection
