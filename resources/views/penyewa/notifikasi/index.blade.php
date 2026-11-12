
@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Notifikasi</h2>
            <p class="text-muted mb-0">Informasi terbaru mengenai aktivitas pemesanan lapanganmu.</p>
        </div>

        @if($jumlahBelumDibaca > 0)
            <form action="{{ route('penyewa.notifikasi.baca-semua') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-success">
                    <i class="bi bi-check2-all me-1"></i>
                    Tandai semua dibaca
                </button>
            </form>
        @endif
    </div>

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 bg-success-subtle text-success">
                            <i class="bi bi-bell fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Belum Dibaca</p>
                            <h3 class="fw-bold mb-0">{{ $jumlahBelumDibaca }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Notifikasi --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            @forelse($notifikasi as $item)
                <div class="d-flex gap-3 p-3 mb-3 rounded-3
                    {{ $item->sudah_dibaca ? 'bg-light' : 'border border-success bg-success-subtle' }}">

                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-white text-success d-flex align-items-center justify-content-center"
                             style="width: 45px; height: 45px;">
                            <i class="bi bi-bell fs-5"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex flex-wrap justify-content-between gap-2">
                            <h6 class="fw-bold mb-1">{{ $item->judul }}</h6>

                            @unless($item->sudah_dibaca)
                                <span class="badge bg-success">Baru</span>
                            @endunless
                        </div>

                        <p class="text-muted mb-2">{{ $item->pesan }}</p>

                        <small class="text-secondary">
                            <i class="bi bi-clock me-1"></i>
                            {{ $item->created_at?->format('d M Y, H:i') ?? '-' }}
                        </small>

                        @if(!$item->sudah_dibaca)
                            <div class="mt-2">
                                <form action="{{ route('penyewa.notifikasi.baca', $item->id) }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        Tandai sudah dibaca
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if($item->pesanan_id)
                            <div class="mt-2">
                                <a href="{{ route('penyewa.riwayat.detail', $item->pesanan_id) }}"
                                   class="btn btn-sm btn-link text-success p-0">
                                    Lihat detail transaksi
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash text-secondary" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold mt-3">Belum Ada Notifikasi</h5>
                    <p class="text-muted mb-0">
                        Notifikasi pemesanan dan pembayaran akan muncul di sini.
                    </p>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if($notifikasi->hasPages())
                <div class="mt-4">
                    {{ $notifikasi->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
