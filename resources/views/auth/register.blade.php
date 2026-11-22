
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - FutsalHub</title>

    <link rel="stylesheet" href="{{ asset('assets/css/futsalhub.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #f5f4ec;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #26352b;
        }

        .register-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            padding: 35px;
            gap: 30px;
            align-items: center;
        }

        .register-card {
            width: 100%;
            max-width: 580px;
            margin: auto;
            padding: 38px;
            background: #fff;
            border-radius: 26px;
            box-shadow: 0 12px 40px rgba(30, 60, 40, 0.08);
        }

        .register-logo {
            font-family: 'Archivo', sans-serif;
            font-size: 27px;
            font-weight: 800;
            color: #247a45;
            margin-bottom: 25px;
        }

        .register-title {
            font-family: 'Archivo', sans-serif;
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .register-subtitle {
            color: #788078;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .form-control {
            min-height: 46px;
            border: 1px solid #e0e5df;
            border-radius: 10px;
            font-size: 14px;
            padding: 11px 13px;
        }

        .form-control:focus {
            border-color: #31945a;
            box-shadow: 0 0 0 3px rgba(49, 148, 90, 0.12);
        }

        .register-button {
            width: 100%;
            min-height: 48px;
            border: none;
            border-radius: 11px;
            background: #247a45;
            color: white;
            font-weight: 700;
            margin-top: 12px;
        }

        .register-button:hover {
            background: #195f35;
        }

        .register-bottom {
            text-align: center;
            font-size: 13px;
            margin-top: 22px;
            color: #747d75;
        }

        .register-bottom a,
        .back-home {
            color: #247a45;
            font-weight: 700;
            text-decoration: none;
        }

        .register-aside {
            min-height: 650px;
            border-radius: 26px;
            background:
                linear-gradient(rgba(18, 75, 42, 0.65), rgba(18, 75, 42, 0.78)),
                url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=1200')
                center/cover;
            display: flex;
            align-items: end;
            padding: 45px;
            color: white;
        }

        .register-aside h2 {
            font-family: 'Archivo', sans-serif;
            font-size: 36px;
            font-weight: 800;
        }

        .register-aside p {
            font-size: 14px;
            line-height: 1.8;
            max-width: 400px;
            color: rgba(255,255,255,.85);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 55px;
        }

        .toggle-password {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #247a45;
            font-size: 12px;
            font-weight: 700;
        }

        @media (max-width: 900px) {
            .register-shell {
                grid-template-columns: 1fr;
                padding: 20px;
            }

            .register-aside {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .register-shell {
                padding: 12px;
            }

            .register-card {
                padding: 25px 20px;
            }

            .register-title {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

<div class="register-shell">

    <div class="register-card">

        <div class="register-logo">⚽ FutsalHub</div>

        <h1 class="register-title">Buat Akun Baru</h1>
        <p class="register-subtitle">
            Daftar sebagai penyewa dan mulai pesan lapangan favoritmu.
        </p>

        @if ($errors->any())
            <div class="alert alert-danger small">
                <strong>Registrasi belum berhasil.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input
                    type="text"
                    name="nama_lengkap"
                    id="nama_lengkap"
                    class="form-control"
                    placeholder="Masukkan nama lengkap"
                    value="{{ old('nama_lengkap') }}"
                    maxlength="120"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control"
                    placeholder="nama@email.com"
                    value="{{ old('email') }}"
                    maxlength="190"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-control"
                    placeholder="Buat username"
                    value="{{ old('username') }}"
                    maxlength="50"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="nomor_telepon" class="form-label">
                    Nomor Telepon <span class="text-muted fw-normal">(Opsional)</span>
                </label>
                <input
                    type="tel"
                    name="nomor_telepon"
                    id="nomor_telepon"
                    class="form-control"
                    placeholder="Contoh: 081234567890"
                    value="{{ old('nomor_telepon') }}"
                    maxlength="30"
                >
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Minimal 8 karakter"
                        minlength="8"
                        required
                    >
                    <button type="button" class="toggle-password" data-target="password">
                        Lihat
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">
                    Konfirmasi Password
                </label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-control"
                        placeholder="Masukkan ulang password"
                        minlength="8"
                        required
                    >
                    <button type="button" class="toggle-password" data-target="password_confirmation">
                        Lihat
                    </button>
                </div>
            </div>

            <button type="submit" class="register-button">
                Daftar Sekarang
            </button>
        </form>

        <div class="register-bottom">
            Sudah punya akun?
            <a href="{{ route('login') }}">Masuk</a>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="back-home small">
                ← Kembali ke Beranda
            </a>
        </div>

    </div>

    <div class="register-aside">
        <div>
            <h2>Mulai Bermain Lebih Mudah.</h2>
            <p>
                Buat akun FutsalHub dan nikmati kemudahan melihat jadwal,
                memilih lapangan, serta melakukan pemesanan secara online.
            </p>
        </div>
    </div>

</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);

            if (input.type === 'password') {
                input.type = 'text';
                this.textContent = 'Sembunyikan';
            } else {
                input.type = 'password';
                this.textContent = 'Lihat';
            }
        });
    });
</script>

</body>
</html>
