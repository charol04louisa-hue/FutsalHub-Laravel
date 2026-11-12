
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - FutsalHub</title>

    <link rel="stylesheet" href="{{ asset('assets/css/futsalhub.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--fh-cream);
        }

        .fh-auth-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            padding: 28px;
            background: var(--fh-cream);
        }

        .fh-auth-card {
            width: 100%;
            max-width: 560px;
            margin: auto;
            padding: clamp(24px, 4vw, 52px);
            background: #fff;
            border: 1px solid var(--fh-border);
            border-radius: 26px;
            box-shadow: var(--fh-shadow);
        }

        .fh-auth-card h1 {
            color: var(--fh-forest);
            font-weight: 900;
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            margin: 12px 0 8px;
        }

        .fh-auth-intro {
            color: var(--fh-muted);
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .fh-role-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .fh-role-option {
            position: relative;
            min-height: 85px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            border: 1px solid var(--fh-border);
            border-radius: 15px;
            color: var(--fh-text);
            text-decoration: none;
            text-align: center;
            font-size: .83rem;
            cursor: pointer;
        }

        .fh-role-option:has(input:checked) {
            border-color: var(--fh-green);
            background: var(--fh-green-soft);
            box-shadow: inset 0 0 0 1px var(--fh-green);
        }

        .fh-role-option input {
            position: absolute;
            opacity: 0;
        }

        .fh-role-option:focus-within {
            outline: 2px solid var(--fh-green);
            outline-offset: 3px;
        }

        .fh-role-icon {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--fh-forest);
            color: var(--fh-lime);
            font-size: 1.1rem;
        }

        .fh-auth-card .form-control {
            min-height: 48px;
            border-color: var(--fh-border);
            border-radius: 11px;
        }

        .fh-auth-card .form-control:focus {
            border-color: var(--fh-green);
            box-shadow: 0 0 0 3px rgba(29,80,58,.12);
        }

        .fh-auth-aside {
            min-height: 520px;
            display: flex;
            align-items: flex-end;
            padding: clamp(28px, 5vw, 64px);
            color: white;
            border-radius: 26px;
            overflow: hidden;
            background:
                linear-gradient(180deg, rgba(18,55,42,.15), rgba(10,31,24,.93)),
                url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=1400') center/cover;
        }

        .fh-auth-aside h2 {
            margin: 22px 0 16px;
            color: #fff;
            font-size: clamp(2.3rem, 4vw, 4rem);
            font-weight: 900;
            line-height: 1.03;
        }

        .fh-auth-aside h2 em {
            color: var(--fh-lime);
            font-style: normal;
        }

        .fh-auth-aside p {
            max-width: 420px;
            color: rgba(255,255,255,.82);
            line-height: 1.7;
        }

        .fh-auth-bottom {
            margin: 22px 0 10px;
            text-align: center;
            color: var(--fh-muted);
            font-size: .92rem;
        }

        .fh-back-link {
            display: block;
            text-align: center;
            margin-top: 16px;
        }

        @media (max-width: 900px) {
            .fh-auth-shell {
                grid-template-columns: 1fr;
                padding: 14px;
            }

            .fh-auth-aside {
                min-height: 280px;
                order: -1;
            }
        }

        @media (max-width: 480px) {
            .fh-role-grid {
                grid-template-columns: 1fr;
            }

            .fh-role-option {
                min-height: 66px;
                flex-direction: row;
            }

            .fh-auth-card {
                padding: 24px 18px;
            }
        }
    </style>
</head>

<body>
<section class="fh-auth-shell">

    <div class="fh-auth-card">

        <a class="fh-brand mb-4" href="/">
            <span class="fh-brand-mark">⚽</span>
            <span>Futsal<span class="fh-brand-accent">Hub</span></span>
        </a>

        <span class="fh-eyebrow">Selamat datang kembali</span>
        <h1>Masuk ke akunmu</h1>

        <p class="fh-auth-intro">
            Pilih jenis akun, lalu masukkan informasi login.
        </p>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}" class="fh-form">
            @csrf

            @php
                $roles = [
                    'penyewa' => ['Penyewa', '⚽'],
                    'pemilik' => ['Pemilik Lapangan', '⌂'],
                    'kasir' => ['Kasir', '✓'],
                    'admin' => ['Admin', '⚙'],
                ];

                $selectedRole = old('role', 'penyewa');
            @endphp

            <div class="fh-role-grid mb-4" role="group" aria-label="Jenis akun">

                @foreach ($roles as $key => $role)
                    <label class="fh-role-option">
                        <input
                            type="radio"
                            name="role"
                            value="{{ $key }}"
                            @checked($selectedRole === $key)
                            required
                        >

                        <span class="fh-role-icon">{{ $role[1] }}</span>
                        <strong>{{ $role[0] }}</strong>
                    </label>
                @endforeach

            </div>

            <div class="mb-3">
                <label class="form-label" for="username">Username</label>

                <input
                    class="form-control"
                    id="username"
                    name="username"
                    type="text"
                    autocomplete="username"
                    value="{{ old('username') }}"
                    placeholder="Masukkan username"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Password</label>

                <div class="position-relative">
                    <input
                        class="form-control pe-5"
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        required
                    >

                    <button
                        type="button"
                        id="togglePassword"
                        aria-label="Tampilkan password"
                        style="
                            position: absolute;
                            right: 14px;
                            top: 50%;
                            transform: translateY(-50%);
                            border: none;
                            background: transparent;
                            color: #557064;
                            cursor: pointer;
                            font-size: 19px;
                        "
                    >
                        👁
                    </button>
                </div>
            </div>

            <button class="fh-btn fh-btn-primary w-100" type="submit">
                Masuk ke FutsalHub →
            </button>

        </form>

        <p class="fh-auth-bottom">
            Belum punya akun?
            <a href="#">Daftar sebagai penyewa</a>
        </p>

        <a class="fh-back-link" href="/">
            ← Kembali ke FutsalHub
        </a>

    </div>

    <div class="fh-auth-aside">
        <div>
            <span class="fh-hero-badge">Play better</span>

            <h2>
                Temukan lapangan.<br>
                <em>Bangun momen.</em>
            </h2>

            <p>
                Semua kebutuhan futsalmu, dari memilih lapangan
                sampai mengatur jadwal, dalam satu tempat.
            </p>
        </div>
    </div>

</section>

<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword.addEventListener('click', function () {
        const isHidden = passwordInput.type === 'password';

        passwordInput.type = isHidden ? 'text' : 'password';
        togglePassword.textContent = isHidden ? '🙈' : '👁';

        togglePassword.setAttribute(
            'aria-label',
            isHidden ? 'Sembunyikan password' : 'Tampilkan password'
        );
    });
</script>

</body>
</html>
