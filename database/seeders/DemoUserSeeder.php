<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Membuat 4 akun demo (satu per role). Password dibuat ACAK saat seeder dijalankan dan
 * hanya ditampilkan sekali di terminal. Tidak ada password di source code.
 * Akun yang sudah ada tidak diubah. Tidak berjalan di environment production.
 */
class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command->error('Seeder akun demo tidak dijalankan di production.');

            return;
        }

        $akun = [
            [Role::Admin, 'demo_admin', 'demo.admin@futsalhub.test', 'Demo Admin'],
            [Role::Kasir, 'demo_kasir', 'demo.kasir@futsalhub.test', 'Demo Kasir'],
            [Role::Pemilik, 'demo_pemilik', 'demo.pemilik@futsalhub.test', 'Demo Pemilik Lapangan'],
            [Role::Penyewa, 'demo_penyewa', 'demo.penyewa@futsalhub.test', 'Demo Penyewa'],
        ];

        $baris = [];

        foreach ($akun as [$role, $username, $email, $nama]) {
            $sudahAda = User::where('username', $username)->orWhere('email', $email)->exists();

            if ($sudahAda) {
                $baris[] = [$role->label(), $username, '(sudah ada, password tidak diubah)'];

                continue;
            }

            $password = Str::password(14, symbols: false);

            $user = new User([
                'nama_lengkap' => $nama,
                'email' => $email,
                'username' => $username,
                'password_hash' => $password, // di-hash otomatis oleh cast 'hashed'
            ]);
            $user->role = $role;
            $user->save();

            $baris[] = [$role->label(), $username, $password];
        }

        $this->command->table(['Role', 'Username', 'Password'], $baris);
        $this->command->warn('Catat password di atas sekarang. Password tidak disimpan di mana pun.');
    }
}
