
@extends('layouts.app')

@section('title', 'Manajemen Lapangan')

@section('content')
<div class="container py-4">

    <x-dashboard-header
        title="Manajemen Lapangan"
        subtitle="Lihat informasi, harga, dan ketersediaan lapangan futsal milikmu."
    />

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mt-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mt-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- DAFTAR LAPANGAN --}}
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Lapangan</h5>
                    <p class="text-muted small mb-0">
                        Total {{ $lapangan->count() }} lapangan terdaftar
                    </p>
                </div>

                <span class="badge rounded-pill px-3 py-2"
                      style="background: #e8f7ee; color: #168653;">
                    <i class="bi bi-eye me-1"></i>
                    Mode Lihat
                </span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Lapangan</th>
                            <th>Ukuran</th>
                            <th>Jenis Lantai</th>
                            <th>Harga / Jam</th>
                            <th>Aksi</th>
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
                                                class="rounded-3"
                                                style="width: 65px; height: 50px; object-fit: cover;"
                                            >
                                        @else
                                            <div
                                                class="rounded-3 bg-light d-flex align-items-center justify-content-center"
                                                style="width: 65px; height: 50px;"
                                            >
                                                <i class="bi bi-image text-muted fs-4"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <div class="fw-semibold">
                                                {{ $item->nama_lapangan }}
                                            </div>
                                            <small class="text-muted">
                                                ID Lapangan: {{ $item->id }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $item->ukuran ?: '-' }}</td>

                                <td>{{ $item->jenis_lantai ?: '-' }}</td>

                                <td class="fw-semibold">
                                    Rp{{ number_format((float) $item->harga_per_jam, 0, ',', '.') }}
                                </td>

                                <td>
                                    <a
                                        href="{{ route('pemilik.lapangan.show', $item->id) }}"
                                        class="btn btn-sm rounded-3 px-3"
                                        style="background:#e8f7ee;color:#168653;font-weight:600;"
                                    >
                                        <i class="bi bi-eye me-1"></i>
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-building text-muted fs-1"></i>

                                    <h6 class="fw-bold mt-3">
                                        Belum Ada Lapangan
                                    </h6>

                                    <p class="text-muted small mb-0">
                                        Belum ada lapangan yang terdaftar atas akunmu.
                                        Silakan hubungi Admin untuk menambahkan lapangan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection
