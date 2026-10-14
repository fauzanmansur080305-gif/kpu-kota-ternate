<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('kpu-logo.png') }}?v=20261012">
    <title>Login | KPU Kota Ternate</title>
    <style>
        :root {
            --brown-deep: #2a0f04;
            --brown: #4a1e0e;
            --gold: #c49b2c;
            --gold-deep: #8a6512;
            --ink: #172b4d;
            --muted: #718096;
            --line: #e6ebf1;
        }

        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            color: var(--ink);
            background: #f3f6fa;
            font-family: Inter, Arial, sans-serif;
        }

        .login-background {
            position: fixed;
            inset: -12px;
            width: calc(100% + 24px);
            height: calc(100% + 24px);
            border: 0;
            filter: blur(7px);
            pointer-events: none;
        }

        body.embedded-login {
            background: transparent;
        }

        .embedded-login .login-background {
            display: none;
        }

        .embedded-login .login-overlay {
            background: transparent;
        }

        .login-overlay {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 32px 24px;
            background: rgba(42, 15, 4, .5);
        }

        .login-shell {
            position: relative;
            width: min(880px, 100%);
            display: grid;
            grid-template-columns: .9fr 1.1fr;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .75);
            border-radius: 24px;
            background: white;
            box-shadow: 0 32px 90px rgba(7, 26, 54, .32);
        }

        .close-link {
            position: absolute;
            z-index: 2;
            top: 16px;
            right: 16px;
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border: 1px solid var(--line);
            border-radius: 50%;
            color: var(--muted);
            background: white;
            font-size: 22px;
            line-height: 1;
            text-decoration: none;
            transition: .2s;
        }

        .close-link:hover, .close-link:focus-visible {
            color: var(--gold);
            border-color: var(--gold);
        }

        .login-intro {
            padding: 48px 42px;
            color: white;
            background: linear-gradient(150deg, var(--brown-deep), var(--brown));
        }

        .logo-mark {
            width: 66px;
            height: 66px;
            margin-bottom: 34px;
            display: block;
            object-fit: contain;
            filter: drop-shadow(0 8px 12px rgba(0,0,0,.24));
        }

        .login-intro h1 {
            margin: 0 0 12px;
            font-size: 29px;
            line-height: 1.15;
        }

        .login-intro p {
            max-width: 280px;
            margin: 0;
            color: rgba(255,255,255,.7);
            font-size: 13px;
            line-height: 1.7;
        }

        .login-intro .tag {
            display: inline-block;
            margin-top: 42px;
            padding: 8px 12px;
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 999px;
            color: rgba(255,255,255,.8);
            font-size: 11px;
        }

        .login-form {
            padding: 48px 42px;
        }

        .login-form h2 {
            margin: 0 0 7px;
            font-size: 24px;
        }

        .login-form > p {
            margin: 0 0 28px;
            color: var(--muted);
            font-size: 13px;
        }

        .error-box {
            margin-bottom: 18px;
            padding: 12px 14px;
            border: 1px solid #fecaca;
            border-radius: 10px;
            color: #991b1b;
            background: #fef2f2;
            font-size: 12px;
        }

        .field { margin-bottom: 17px; }
        .field label {
            display: block;
            margin-bottom: 7px;
            color: #52637a;
            font-size: 12px;
            font-weight: 700;
        }

        .field input,
        .field select {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #dbe3ec;
            border-radius: 10px;
            outline: 0;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 13px;
            transition: .2s;
        }

        .field input:focus,
        .field select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(196,155,44,.22);
        }

        .login-button {
            width: 100%;
            margin-top: 8px;
            padding: 14px;
            border: 0;
            border-radius: 10px;
            color: white;
            background: linear-gradient(135deg, var(--gold), var(--gold-deep));
            font: inherit;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(196,155,44,.28);
        }

        .back-link {
            display: block;
            margin-top: 18px;
            color: var(--muted);
            font-size: 12px;
            text-align: center;
            text-decoration: none;
        }

        .back-link:hover { color: var(--gold); }
        .forgot-link {
            display: block;
            margin-top: 14px;
            color: var(--gold-deep);
            font-size: 12px;
            text-align: center;
            text-decoration: none;
        }

        .forgot-link:hover { color: var(--gold); }

        @media (max-width: 700px) {
            .login-overlay { padding: 20px 14px; }
            .login-shell { grid-template-columns: 1fr; }
            .login-intro, .login-form { padding: 28px; }
            .login-intro { padding-right: 60px; }
            .logo-mark { margin-bottom: 22px; }
            .login-intro .tag { margin-top: 24px; }
        }
    </style>
</head>
<body>
    <iframe class="login-background" id="loginBackground" title="Latar belakang halaman KPU" aria-hidden="true" tabindex="-1" inert></iframe>
    <div class="login-overlay">
    <main class="login-shell" role="dialog" aria-modal="true" aria-labelledby="loginTitle">
        <a class="close-link" id="closeLogin" href="{{ route('home') }}" aria-label="Tutup login">&times;</a>
        <section class="login-intro">
            <img class="logo-mark" src="{{ asset('kpu-logo.png') }}" alt="Logo KPU">
            <h1>Komisi Pemilihan Umum<br>Kota Ternate</h1>
            <p>Sistem informasi buku tamu dan pelayanan KPU Kota Ternate.</p>
            <span class="tag">Sistem Informasi KPU</span>
        </section>

        <section class="login-form">
            <h2 id="loginTitle">Login</h2>
            <p>Masukkan NIP dan kata sandi untuk melanjutkan.</p>

            @if(session('status'))
                <div class="error-box" style="border-color:#bbf7d0;color:#166534;background:#f0fdf4;">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label for="nip">NIP</label>
                    <input id="nip" name="nip" type="text" inputmode="numeric" value="{{ old('nip') }}" placeholder="Masukkan NIP pegawai" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Masukkan password" required>
                </div>

                <button class="login-button" type="submit">Masuk ke Dashboard</button>
            </form>

            <a class="forgot-link" href="{{ route('password.request') }}">Lupa kata sandi?</a>
            <a class="back-link" id="backToPage" href="{{ route('home') }}">Kembali ke halaman utama</a>
        </section>
    </main>
    </div>
    <script>
        const isEmbedded = window.parent !== window;
        if (isEmbedded) {
            document.body.classList.add('embedded-login');
            document.getElementById('closeLogin').addEventListener('click', function (event) {
                event.preventDefault();
                window.parent.closeLoginDialog();
            });
            document.getElementById('backToPage').addEventListener('click', function (event) {
                event.preventDefault();
                window.parent.closeLoginDialog();
            });
        }

        const homeUrl = @json(route('home'));
        const guestUrls = [homeUrl, @json(route('tamu.index'))];
        const previousPage = document.referrer.split('#')[0];
        const storedPage = sessionStorage.getItem('kpuLoginPreviousPage');
        const backgroundUrl = guestUrls.includes(previousPage)
            ? previousPage
            : guestUrls.includes(storedPage) ? storedPage : homeUrl;

        sessionStorage.setItem('kpuLoginPreviousPage', backgroundUrl);
        const background = document.getElementById('loginBackground');
        if (!isEmbedded) {
            background.src = backgroundUrl;
        }
        document.getElementById('closeLogin').href = backgroundUrl;
        document.getElementById('backToPage').href = backgroundUrl;

        if (!isEmbedded) {
            background.addEventListener('load', function () {
                const scrollPosition = Number(sessionStorage.getItem('kpuLoginPreviousScroll') || 0);
                background.contentWindow.scrollTo(0, scrollPosition);
            });
        }

        document.querySelector('.login-overlay').addEventListener('click', function (event) {
            if (event.target !== event.currentTarget) {
                return;
            }

            if (isEmbedded) {
                window.parent.closeLoginDialog();
            } else {
                window.location.href = backgroundUrl;
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                if (isEmbedded) {
                    window.parent.closeLoginDialog();
                } else {
                    window.location.href = backgroundUrl;
                }
            }
        });
    </script>
    @include('layouts.loading')
</body>
</html>
