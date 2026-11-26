
@extends('layouts.app')

@section('title', 'Profil Admin')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Profil Admin</h2>
        <p class="text-muted mb-0">
            Kelola informasi akun dan keamanan password.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-success bg-opacity-10
                                text-success d-flex align-items-center
                                justify-content-center mx-auto mb-3"
                         style="width: 80px; height: 80px; font-size: 30px;">
                        {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                    </div>

                    <h5 class="fw-bold mb-1">
                        {{ $user->nama_lengkap }}
                    </h5>

                    <p class="text-muted mb-3">
                        {{ $user->email }}
                    </p>

                    <span class="badge bg-success">Akun Admin</span>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Informasi Profil</h5>

                    <form action="{{ route('admin.profil.update') }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">
                                Nama Lengkap
                            </label>
                            <input type="text"
                                   class="form-control @error('nama_lengkap') is-invalid @enderror"
                                   id="nama_lengkap"
                                   name="nama_lengkap"
                                   value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                                   required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nomor_telepon" class="form-label">
                                Nomor Telepon
                            </label>
                            <input type="text"
                                   class="form-control @error('nomor_telepon') is-invalid @enderror"
                                   id="nomor_telepon"
                                   name="nomor_telepon"
                                   value="{{ old('nomor_telepon', $user->nomor_telepon) }}">
                            @error('nomor_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-2">Ganti Password</h5>
                    <p class="text-muted small mb-4">
                        Password baru minimal 8 karakter.
                    </p>

                    <form action="{{ route('admin.profil.password') }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="password_lama" class="form-label">
                                Password Lama
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('password_lama') is-invalid @enderror"
                                       id="password_lama"
                                       name="password_lama"
                                       required>
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        data-toggle-password="password_lama">
                                    Show
                                </button>
                            </div>
                            @error('password_lama')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_baru" class="form-label">
                                Password Baru
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('password_baru') is-invalid @enderror"
                                       id="password_baru"
                                       name="password_baru"
                                       required>
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        data-toggle-password="password_baru">
                                    Show
                                </button>
                            </div>
                            @error('password_baru')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_baru_confirmation"
                                   class="form-label">
                                Konfirmasi Password Baru
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password_baru_confirmation"
                                       name="password_baru_confirmation"
                                       required>
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        data-toggle-password="password_baru_confirmation">
                                    Show
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">
                            Perbarui Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('[data-toggle-password]').forEach(button => {
        button.addEventListener('click', function () {
            const inputId = this.getAttribute('data-toggle-password');
            const input = document.getElementById(inputId);

            if (input.type === 'password') {
                input.type = 'text';
                this.textContent = 'Hide';
            } else {
                input.type = 'password';
                this.textContent = 'Show';
            }
        });
    });
</script>
@endsection
