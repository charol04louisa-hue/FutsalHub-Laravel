
@extends('layouts.app')

@section('title', 'Profil Kasir')

@section('content')
<div class="container py-5">

    <div class="mb-4">
        <h2 class="fw-bold">Profil Saya</h2>
        <p class="text-muted">Kelola informasi akun dan keamanan password.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Informasi Akun --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center
                            text-white fw-bold"
                     style="width:65px;height:65px;background:#198754;font-size:24px;">
                    {{ strtoupper(substr($user->nama_lengkap ?? $user->username, 0, 1)) }}
                </div>

                <div>
                    <h4 class="fw-bold mb-1">
                        {{ $user->nama_lengkap ?? $user->username }}
                    </h4>
                    <p class="text-muted mb-1">{{ $user->email }}</p>
                    <span class="badge bg-success">Akun Kasir</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Profil --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4">Informasi Pribadi</h5>

            <form action="{{ route('kasir.profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text"
                           name="nama_lengkap"
                           class="form-control"
                           value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                           required>
                    @error('nama_lengkap')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email', $user->email) }}"
                           required>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text"
                           name="nomor_telepon"
                           class="form-control"
                           value="{{ old('nomor_telepon', $user->nomor_telepon) }}">
                    @error('nomor_telepon')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    {{-- Ganti Password --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-2">Keamanan Akun</h5>
            <p class="text-muted mb-4">Ganti password akun Kasir kamu.</p>

            <form action="{{ route('kasir.profil.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Password Lama</label>
                    <div class="input-group">
                        <input type="password"
                               name="password_lama"
                               class="form-control"
                               id="password_lama"
                               required>
                        <button type="button"
                                class="btn btn-outline-secondary"
                                data-toggle-password="password_lama">
                            Show
                        </button>
                    </div>
                    @error('password_lama')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <div class="input-group">
                        <input type="password"
                               name="password_baru"
                               class="form-control"
                               id="password_baru"
                               required>
                        <button type="button"
                                class="btn btn-outline-secondary"
                                data-toggle-password="password_baru">
                            Show
                        </button>
                    </div>
                    @error('password_baru')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <input type="password"
                               name="password_baru_confirmation"
                               class="form-control"
                               id="password_baru_confirmation"
                               required>
                        <button type="button"
                                class="btn btn-outline-secondary"
                                data-toggle-password="password_baru_confirmation">
                            Show
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    Ganti Password
                </button>
            </form>
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
