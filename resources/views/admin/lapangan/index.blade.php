
@extends('layouts.app')

@section('title', 'Manajemen Lapangan')

@push('styles')
<style>
    .admin-lapangan {
        --green: #168653;
        --muted: #718096;
        --border: #e8eee9;
    }

    .admin-lapangan .content-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 21px;
    }

    .admin-lapangan .section-title {
        color: #24352b;
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .admin-lapangan .section-subtitle {
        color: var(--muted);
        font-size: 12px;
        margin-bottom: 20px;
    }

    .admin-lapangan .btn-add {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        background: var(--green);
        color: #fff;
        border: none;
        border-radius: 9px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
    }

    .admin-lapangan .btn-add:hover {
        background: #116c42;
        color: #fff;
    }

    .admin-lapangan .lapangan-table {
        min-width: 900px;
        margin-bottom: 0;
        vertical-align: middle;
    }

    .admin-lapangan .lapangan-table thead th {
        background: #f7faf8;
        color: #718096;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 14px 12px;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .admin-lapangan .lapangan-table tbody td {
        padding: 14px 12px;
        border-bottom: 1px solid #f0f3f1;
        font-size: 12px;
        color: #374151;
    }

    .admin-lapangan .lapangan-table tbody tr:last-child td {
        border-bottom: none;
    }

    .admin-lapangan .lapangan-image {
        width: 65px;
        height: 52px;
        object-fit: cover;
        border-radius: 9px;
        background: #f1f5f2;
    }

    .admin-lapangan .lapangan-name {
        color: #24352b;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .admin-lapangan .muted {
        color: #8a96a3;
        font-size: 11px;
    }

    .admin-lapangan .owner-badge {
        display: inline-block;
        background: #e8f7ee;
        color: #168653;
        padding: 6px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    /* TOMBOL AKSI */
    .admin-lapangan .action-group {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }

    .admin-lapangan .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 82px;
        height: 36px;
        padding: 0 11px;
        border-radius: 9px;
        border: 1px solid transparent;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        cursor: pointer;
        transition: all .2s ease;
    }

    .admin-lapangan .btn-action i {
        font-size: 14px;
        line-height: 1;
    }

    .admin-lapangan .btn-edit {
        background: #edf2ff;
        border-color: #dce5ff;
        color: #5269b5;
    }

    .admin-lapangan .btn-edit:hover {
        background: #5269b5;
        border-color: #5269b5;
        color: #fff;
        transform: translateY(-1px);
    }

    .admin-lapangan .btn-delete {
        background: #fff0f0;
        border-color: #ffdddd;
        color: #dc3545;
    }

    .admin-lapangan .btn-delete:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: #fff;
        transform: translateY(-1px);
    }

    .admin-lapangan .empty-state {
        text-align: center;
        padding: 55px 15px;
        color: #8a96a3;
    }

    .admin-lapangan .empty-state i {
        font-size: 38px;
        color: #b7c9bc;
        margin-bottom: 12px;
    }

    @media (max-width: 767px) {
        .admin-lapangan .content-card {
            padding: 16px;
        }

        .admin-lapangan .section-heading {
            align-items: flex-start !important;
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 admin-lapangan">

    <x-dashboard-header
        eyebrow="FUTSALHUB MANAGEMENT"
        title="Manajemen Lapangan"
        :greeting="'Halo, ' . auth()->user()->nama_lengkap . '!'"
        description="Kelola seluruh data lapangan futsal dan pemiliknya."
    />

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- DAFTAR LAPANGAN --}}
    <div class="content-card">

        <div class="section-heading d-flex justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h5 class="section-title">Daftar Seluruh Lapangan</h5>
                <p class="section-subtitle mb-0">
                    Total {{ $lapangan->count() }} lapangan terdaftar dalam sistem.
                </p>
            </div>

            <a href="{{ route('admin.lapangan.create') }}" class="btn-add">
                <i class="bi bi-plus-lg"></i>
                Tambah Lapangan
            </a>
        </div>

        <div class="table-responsive">
            <table class="table lapangan-table">
                <thead>
                    <tr>
                        <th>Lapangan</th>
                        <th>Pemilik</th>
                        <th>Ukuran</th>
                        <th>Jenis Lantai</th>
                        <th>Harga / Jam</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($lapangan as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if ($item->gambar)
                                        <img
                                            src="{{ asset('assets/img/' . $item->gambar) }}"
                                            alt="{{ $item->nama_lapangan }}"
                                            class="lapangan-image"
                                        >
                                    @else
                                        <div class="lapangan-image d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image text-secondary"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <div class="lapangan-name">
                                            {{ $item->nama_lapangan }}
                                        </div>
                                        <div class="muted">
                                            ID Lapangan #{{ $item->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if ($item->owner)
                                    <div class="lapangan-name">
                                        {{ $item->owner->nama_lengkap }}
                                    </div>
                                    <span class="owner-badge">Pemilik Lapangan</span>
                                @else
                                    <span class="muted">Pemilik tidak tersedia</span>
                                @endif
                            </td>

                            <td>{{ $item->ukuran ?: '-' }}</td>

                            <td>{{ $item->jenis_lantai ?: '-' }}</td>

                            <td>
                                <div class="lapangan-name">
                                    Rp {{ number_format((float) $item->harga_per_jam, 0, ',', '.') }}
                                </div>
                            </td>

                            {{-- TOMBOL AKSI --}}
                            <td>
                                <div class="action-group">

                                    <a
                                        href="{{ route('admin.lapangan.edit', $item->id) }}"
                                        class="btn-action btn-edit"
                                        title="Edit Lapangan"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Edit</span>
                                    </a>

                                    <form
                                        action="{{ route('admin.lapangan.destroy', $item->id) }}"
                                        method="POST"
                                        class="m-0"
                                        onsubmit="return confirm('Yakin ingin menghapus lapangan {{ addslashes($item->nama_lapangan) }}?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn-action btn-delete"
                                            title="Hapus Lapangan"
                                        >
                                            <i class="bi bi-trash"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-building d-block"></i>
                                    <h6 class="fw-bold text-dark">
                                        Belum Ada Lapangan
                                    </h6>
                                    <p class="small mb-0">
                                        Silakan tambahkan lapangan pertama ke dalam sistem.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
