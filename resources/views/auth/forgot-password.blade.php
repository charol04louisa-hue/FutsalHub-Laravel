
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - FutsalHub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --fh-green: #198754;
            --fh-dark: #123c2b;
            --fh-cream: #f7f8f2;
        }

        body {
            min-height: 100vh;
            background: var(--fh-cream);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: Arial, sans-serif;
        }

        .fh-card {
            width: 100%;
            max-width: 440px;
            background: #fff;
            padding: 36px;
            border-radius: 20px;
            box-shadow: 0 12px 35px rgba(18, 60, 43, .09);
        }

        .fh-logo {
            width: 58px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            border-radius: 16px;
            background: #e8f4ec;
            color: var(--fh-green);
            font-size: 28px;
        }

        .fh-title {
            color: var(--fh-dark);
            font-weight: 800;
            text-align: center;
        }

        .fh-description {
            color: #718078;
            text-align: center;
            font-size: 14px;
            line-height: 1.6;
        }

        .form-label {
            color: var(--fh-dark);
            font-weight: 700;
            font-size: 14px;
        }

        .form-control {
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #dce5dd;
        }

        .form-control:focus {
            border-color: var(--fh-green);
            box-shadow: 0 0 0 3px rgba(25, 135, 84, .12);
        }

        .fh-btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            background: var(--fh-green);
            color: #fff;
            font-weight: 700;
        }

        .fh-btn:hover {
            background: #146c43;
        }

        .fh-back {
            display: block;
            margin-top: 22px;
            color: var(--fh-green);
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
        }

        .fh-back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="fh-card">
        <div class="fh-logo">🔑</div>

        <h3 class="fh-title mb-2">Lupa Password?</h3>

        <p class="fh-description mb-4">
            Jangan khawatir! Masukkan email yang terdaftar di FutsalHub.
            Kami akan mengirimkan tautan untuk mengatur ulang password kamu.
        </p>

        @if (session('status'))
            <div class="alert alert-success small">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger small">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">Alamat Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="Masukkan email terdaftar"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="fh-btn">
                Kirim Link Reset Password →
            </button>
        </form>

        <a href="{{ route('login') }}" class="fh-back">
            ← Kembali ke halaman login
        </a>
    </div>

</body>
</html>
