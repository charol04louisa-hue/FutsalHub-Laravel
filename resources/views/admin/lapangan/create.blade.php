
@extends('layouts.app')

@section('title', 'Tambah Lapangan')

@push('styles')
<style>
    .admin-lapangan-form {
        --green: #168653;
        --muted: #718096;
        --border: #e8eee9;
    }

    .admin-lapangan-form .form-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 25px;
    }

    .admin-lapangan-form .section-title {
        color: #24352b;
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .admin-lapangan-form .section-subtitle {
        color: var(--muted);
        font-size: 12px;
        margin-bottom: 23px;
    }

    .admin-lapangan-form .form-label {
        color: #374151;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .admin-lapangan-form .form-control,
    .admin-lapangan-form .form-select {
        border: 1px solid #dfe7e1;
        border-radius: 9px;
        padding: 11px 13px;
        font-size: 13px;
        color: #374151;
    }

    .admin-lapangan-form .form-control:focus,
    .admin-lapangan-form .form-select:focus {
        border-color: var(--green);
        box-shadow: 0 0 0 3px rgba(22, 134, 83, .10);
    }

    .admin-lapangan-form .form-text {
        color: #8a96a3;
        font-size: 11px;
    }

    .admin-lapangan-form .btn-save {
        background: var(--green);
        color: #fff;
        border: none;
        border-radius: 9px;
        padding: 11px 19px;
        font-size: 13px;
        font-weight: 700;
    }

    .admin-lapangan-form .btn-save:hover {
        background: #116c42;
        color: #fff;
    }

    .admin-lapangan-form .btn-back {
        background: #f1f5f2;
        color: #52605a;
        border: 1px solid #e2e9e4;
        border-radius: 9px;
        padding: 11px 19px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }

    .admin-lapangan-form .btn-back:hover {
        background: #e7eee9;
        color: #24352b;
    }

    @media (max-width: 767px) {
        .admin-lapangan-form .form-card {
            padding: 18px;
        }

        .admin-lapangan-form .form-actions {
            flex-direction: column-reverse;
        }

        .admin-lapangan-form .form-actions a,
        .admin-lapangan-form .form-actions button {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 admin-lapangan-form">

    <x-dashboard-header
        eyebrow="FUTSALHUB MANAGEMENT"
        title="Tambah Lapangan"
        :greeting="'Halo, ' . auth()->user()->nama_lengkap . '!'"
        description="Tambahkan informasi lapangan futsal baru ke dalam sistem."
    />

    <div class="form-card">

        <h5 class="section-title">Informasi Lapangan</h5>
        <p class="section-subtitle">
            Lengkapi informasi lapangan dan tentukan pemiliknya.
        </p>

        @if ($errors->any())
            <div class="alert alert-danger rounded-3 mb-4">
                <div class="fw-bold small mb-2">
                    Terdapat data yang perlu diperbaiki:
                </div>
                <ul class="small mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('admin.lapangan.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="row g-3">

                {{-- NAMA LAPANGAN --}}
                <div class="col-md-6">
                    <label for="nama_lapangan" class="form-label">
                        Nama Lapangan <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_lapangan"
                        id="nama_lapangan"
                        class="form-control @error('nama_lapangan') is-invalid @enderror"
                        value="{{ old('nama_lapangan') }}"
                        placeholder="Contoh: Lapangan Futsal A"
                        maxlength="100"
                        required
                    >
                    @error('nama_lapangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PEMILIK --}}
                <div class="col-md-6">
                    <label for="owner_id" class="form-label">
                        Pemilik Lapangan <span class="text-danger">*</span>
                    </label>
                    <select
                        name="owner_id"
                        id="owner_id"
                        class="form-select @error('owner_id') is-invalid @enderror"
                        required
                    >
                        <option value="">Pilih pemilik lapangan</option>

                        @foreach ($pemilik as $owner)
                            <option
                                value="{{ $owner->id }}"
                                {{ old('owner_id') == $owner->id ? 'selected' : '' }}
                            >
                                {{ $owner->nama_lengkap }}
                                ({{ $owner->username }})
                            </option>
                        @endforeach
                    </select>

                    @error('owner_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if ($pemilik->isEmpty())
                        <div class="form-text text-danger">
                            Belum ada akun Pemilik. Buat akun Pemilik terlebih dahulu.
                        </div>
                    @else
                        <div class="form-text">
                            Pilih akun Pemilik yang memiliki lapangan ini.
                        </div>
                    @endif
                </div>

                {{-- UKURAN --}}
                <div class="col-md-6">
                    <label for="ukuran" class="form-label">
                        Ukuran Lapangan
                    </label>
                    <input
                        type="text"
                        name="ukuran"
                        id="ukuran"
                        class="form-control @error('ukuran') is-invalid @enderror"
                        value="{{ old('ukuran') }}"
                        placeholder="Contoh: 25 x 15 meter"
                        maxlength="50"
                    >
                    @error('ukuran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- JENIS LANTAI --}}
                <div class="col-md-6">
                    <label for="jenis_lantai" class="form-label">
                        Jenis Lantai
                    </label>
                    <input
                        type="text"
                        name="jenis_lantai"
                        id="jenis_lantai"
                        class="form-control @error('jenis_lantai') is-invalid @enderror"
                        value="{{ old('jenis_lantai') }}"
                        placeholder="Contoh: Rumput sintetis"
                        maxlength="100"
                    >
                    @error('jenis_lantai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- HARGA --}}
                <div class="col-md-6">
                    <label for="harga_per_jam" class="form-label">
                        Harga Sewa per Jam (Rp) <span class="text-danger">*</span>
                    </label>
                    <input
                        type="number"
                        name="harga_per_jam"
                        id="harga_per_jam"
                        class="form-control @error('harga_per_jam') is-invalid @enderror"
                        value="{{ old('harga_per_jam') }}"
                        placeholder="Contoh: 150000"
                        min="0"
                        step="0.01"
                        required
                    >
                    @error('harga_per_jam')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- GAMBAR --}}
                <div class="col-md-6">
                    <label for="gambar" class="form-label">
                        Gambar Lapangan
                    </label>
                    <input
                        type="file"
                        name="gambar"
                        id="gambar"
                        class="form-control @error('gambar') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png,.webp"
                    >
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        Format JPG, JPEG, PNG, atau WebP. Maksimal 2 MB.
                    </div>
                </div>

                {{-- DESKRIPSI --}}
                <div class="col-12">
                    <label for="deskripsi" class="form-label">
                        Deskripsi Lapangan
                    </label>
                    <textarea
                        name="deskripsi"
                        id="deskripsi"
                        rows="4"
                        class="form-control @error('deskripsi') is-invalid @enderror"
                        placeholder="Tuliskan informasi tambahan mengenai lapangan..."
                    >{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <hr class="my-4" style="border-color: #e8eee9;">

            <div class="form-actions d-flex justify-content-end gap-2">
                <a
                    href="{{ route('admin.lapangan.index') }}"
                    class="btn-back"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali
                </a>

                <button
                    type="submit"
                    class="btn-save"
                    {{ $pemilik->isEmpty() ? 'disabled' : '' }}
                >
                    <i class="bi bi-check-circle me-1"></i>
                    Simpan Lapangan
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
