# Migrasi database FutsalHub (Tahap 2)

Prinsip: database lama (`db_futsal`) tidak dihapus atau di-reset. Laravel bekerja di salinan
`db_futsal_laravel`. Semua perubahan bersifat additive dan aman dijalankan ulang.

| Migration | Isi | Pada database lama |
|---|---|---|
| 100001-100003 baseline | Meniru `users`, `lapangan`, `pesanan` persis seperti dump asli | Dilewati (tabel sudah ada) |
| 100004 | ENUM `users.role` + `kasir` (di akhir), `users.updated_at` | ALTER, data aman |
| 100005 | `lapangan`: `ukuran`, `jenis_lantai`, timestamps, `deleted_at`; backfill lantai + foto id 1 dan 2 | ALTER + UPDATE terbatas |
| 100006 | `pesanan`: status booking, status pembayaran, alasan penolakan, verifikator, snapshot harga, timestamps | ALTER + backfill (lihat bawah) |
| 100007 | `pesanan.waktu_mulai` / `waktu_selesai` (generated column) + index slot | ALTER |
| 100008 | FK `user_id`, `diverifikasi_oleh`, `owner_id` -> `users.id` | Berhenti jika ada data yatim |

Backfill pesanan lama: status booking dan pembayaran = `menunggu_verifikasi` (tidak otomatis lunas).
`harga_per_jam` dan `total_harga` dihitung dari harga lapangan saat ini, jadi merupakan perkiraan.
`lapangan.ukuran` dibiarkan kosong (tidak ada sumber datanya); Admin mengisinya nanti.

Aturan jadwal: booking `menunggu_verifikasi` dan `disetujui` memblokir slot; `ditolak` tidak.

Rollback: `php artisan migrate:rollback` membatalkan migration additive. Baseline sengaja
tidak menghapus tabel apa pun.

STATUS: belum pernah dijalankan (dibuat tanpa akses PHP). Jalankan dulu di salinan database.
