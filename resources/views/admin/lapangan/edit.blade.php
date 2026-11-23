
@extends('layouts.app')

@section('title', 'Edit Lapangan')

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

    .admin-lapangan-form .current-image {
        width: 150px;
        height: 105px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: #f1f5f2;
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
        title="Edit Lapangan"
        :greeting="'Halo, ' . auth()->user()->nama_lengkap . '!'"
        description="Perbarui informasi lapangan futsal yang terdaftar."
    />

    <div class="form-card">

        <h5 class="section-title">Informasi Lapangan</h5>
        <p class="section-subtitle">
            Perbarui data lapangan sesuai informasi terbaru.
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
            action="{{ route('admin.lapangan.update', $lapangan->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

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
                        value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}"
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
                                {{ old('owner_id', $lapangan->owner_id) == $owner->id ? 'selected' : '' }}
                            >
                                {{ $owner->nama_lengkap }}
                                ({{ $owner->username }})
                            </option>
                        @endforeach
                    </select>

                    @error('owner_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-text">
                        Admin dapat mengubah pemilik lapangan melalui pilihan ini.
                    </div>
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
                        value="{{ old('ukuran', $lapangan->ukuran) }}"
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
                        value="{{ old('jenis_lantai', $lapangan->jenis_lantai) }}"
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
                        value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}"
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
                        Ganti Gambar Lapangan
                    </label>

                    @if ($lapangan->gambar)
                        <div class="mb-3">
                            <img
                                src="{{ asset('assets/img/' . $lapangan->gambar) }}"
                                alt="{{ $lapangan->nama_lapangan }}"
                                class="current-image"
                            >
                            <div class="form-text mt-2">
                                Gambar yang sedang digunakan.
                            </div>
                        </div>
                    @else
                        <div class="form-text mb-2">
                            Belum ada gambar untuk lapangan ini.
                        </div>
                    @endif

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
                        Kosongkan jika tidak ingin mengganti gambar. Maksimal 2 MB.
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
                    >{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
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

                <button type="submit" class="btn-save">
                    <i class="bi bi-check-circle me-1"></i>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
