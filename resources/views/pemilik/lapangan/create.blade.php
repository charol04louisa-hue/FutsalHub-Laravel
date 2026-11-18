
@extends('layouts.app')

@section('content')
<div class="container py-5">

    <x-dashboard-header
        title="Tambah Lapangan"
        subtitle="Tambahkan informasi lapangan futsal baru ke dalam sistem."
    />

    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-1">Informasi Lapangan</h5>
            <p class="text-muted small mb-4">
                Lengkapi informasi lapangan yang ingin ditambahkan.
            </p>

            <form
                action="{{ route('pemilik.lapangan.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="mb-3">
                    <label for="nama_lapangan" class="form-label fw-semibold">
                        Nama Lapangan <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_lapangan"
                        id="nama_lapangan"
                        class="form-control rounded-3 @error('nama_lapangan') is-invalid @enderror"
                        value="{{ old('nama_lapangan') }}"
                        placeholder="Contoh: Lapangan Futsal A"
                        required
                    >
                    @error('nama_lapangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="ukuran" class="form-label fw-semibold">
                            Ukuran Lapangan
                        </label>
                        <input
                            type="text"
                            name="ukuran"
                            id="ukuran"
                            class="form-control rounded-3 @error('ukuran') is-invalid @enderror"
                            value="{{ old('ukuran') }}"
                            placeholder="Contoh: 25 x 15 meter"
                        >
                        @error('ukuran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="jenis_lantai" class="form-label fw-semibold">
                            Jenis Lantai
                        </label>
                        <input
                            type="text"
                            name="jenis_lantai"
                            id="jenis_lantai"
                            class="form-control rounded-3 @error('jenis_lantai') is-invalid @enderror"
                            value="{{ old('jenis_lantai') }}"
                            placeholder="Contoh: Vinyl"
                        >
                        @error('jenis_lantai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="harga_per_jam" class="form-label fw-semibold">
                        Harga Sewa per Jam (Rp) <span class="text-danger">*</span>
                    </label>
                    <input
                        type="number"
                        name="harga_per_jam"
                        id="harga_per_jam"
                        class="form-control rounded-3 @error('harga_per_jam') is-invalid @enderror"
                        value="{{ old('harga_per_jam') }}"
                        min="0"
                        step="1000"
                        placeholder="Contoh: 150000"
                        required
                    >
                    @error('harga_per_jam')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label fw-semibold">
                        Deskripsi Lapangan
                    </label>
                    <textarea
                        name="deskripsi"
                        id="deskripsi"
                        rows="4"
                        class="form-control rounded-3 @error('deskripsi') is-invalid @enderror"
                        placeholder="Tuliskan informasi tambahan tentang lapangan..."
                    >{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="gambar" class="form-label fw-semibold">
                        Foto Lapangan
                    </label>
                    <input
                        type="file"
                        name="gambar"
                        id="gambar"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="form-control rounded-3 @error('gambar') is-invalid @enderror"
                    >
                    <small class="text-muted">
                        Format JPG, JPEG, PNG, atau WebP. Maksimal 2 MB.
                    </small>
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ route('pemilik.lapangan.index') }}"
                       class="btn btn-light border rounded-3 px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-success rounded-3 px-4">
                        <i class="bi bi-save me-1"></i>
                        Simpan Lapangan
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
