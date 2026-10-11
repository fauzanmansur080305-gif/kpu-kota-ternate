<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('kpu-logo.png') }}?v=20261005">
    <title>Verifikasi Akun Administrator | KPU Kota Ternate</title>
    <style>
        :root { --brown-deep: #2a0f04; --brown: #4a1e0e; --gold: #c49b2c; --gold-deep: #8a6512; --ink: #172b4d; --muted: #718096; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; color: var(--ink); background: #f3f6fa; font-family: Inter, Arial, sans-serif; }
        .overlay { min-height: 100vh; display: grid; place-items: center; padding: 24px; background: rgba(42,15,4,.5); }
        .shell { width: min(880px, 100%); display: grid; grid-template-columns: .9fr 1.1fr; overflow: hidden; border: 1px solid rgba(255,255,255,.75); border-radius: 24px; background: white; box-shadow: 0 32px 90px rgba(7,26,54,.32); }
        .intro { padding: 48px 42px; color: white; background: linear-gradient(150deg,var(--brown-deep),var(--brown)); }
        .logo { width: 66px; height: 66px; margin-bottom: 34px; object-fit: contain; }
        h1 { margin: 0 0 12px; font-size: 29px; line-height: 1.15; }
        .intro p { max-width: 280px; margin: 0; color: rgba(255,255,255,.7); font-size: 13px; line-height: 1.7; }
        .tag { display: inline-block; margin-top: 42px; padding: 8px 12px; border: 1px solid rgba(255,255,255,.18); border-radius: 999px; color: rgba(255,255,255,.8); font-size: 11px; }
        .form-panel { padding: 48px 42px; }
        h2 { margin: 0 0 7px; font-size: 24px; }
        .description { margin: 0 0 28px; color: var(--muted); font-size: 13px; line-height: 1.6; }
        .notice { margin-bottom: 18px; padding: 12px 14px; border: 1px solid #bbf7d0; border-radius: 10px; color: #166534; background: #f0fdf4; font-size: 12px; line-height: 1.6; }
        .error { margin-bottom: 18px; padding: 12px 14px; border: 1px solid #fecaca; border-radius: 10px; color: #991b1b; background: #fef2f2; font-size: 12px; }
        .field { margin-bottom: 17px; }
        label { display: block; margin-bottom: 7px; color: #52637a; font-size: 12px; font-weight: 700; }
        input { width: 100%; padding: 13px 14px; border: 1px solid #dbe3ec; border-radius: 10px; outline: 0; color: var(--ink); background: #fbfcfe; font: inherit; font-size: 13px; }
        input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(196,155,44,.22); }
        button { width: 100%; margin-top: 8px; padding: 14px; border: 0; border-radius: 10px; color: white; background: linear-gradient(135deg,var(--gold),var(--gold-deep)); font: inherit; font-size: 13px; font-weight: 800; cursor: pointer; box-shadow: 0 8px 18px rgba(196,155,44,.28); }
        .back { display: block; margin-top: 18px; color: var(--muted); font-size: 12px; text-align: center; text-decoration: none; }
        .back:hover { color: var(--gold); }
        @media(max-width:700px) { .overlay { padding: 20px 14px; } .shell { grid-template-columns: 1fr; } .intro,.form-panel { padding: 28px; } .logo { margin-bottom: 22px; } .tag { margin-top: 24px; } }
    </style>
</head>
<body>
    <main class="overlay">
        <div class="shell">
            <section class="intro">
                <img class="logo" src="https://www.kpu.go.id/img/logo-kpu.png" alt="Logo KPU">
                <h1>Komisi Pemilihan Umum<br>Kota Ternate</h1>
                <p>Sistem informasi buku tamu, absensi, dan pelayanan KPU Kota Ternate.</p>
                <span class="tag">Pemulihan Akun Administrator</span>
            </section>
            <section class="form-panel">
                <h2>Verifikasi akun administrator</h2>
                <p class="description">Masukkan kode 6 digit yang dikirim ke email Anda. Kode berlaku selama 10 menit.</p>
                @if(session('status'))
                    <div class="notice" role="status">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="error" role="alert">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ route('password.verify') }}">
                    @csrf
                    <div class="field">
                        <label for="email">Email Administrator</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $email) }}" autocomplete="email" maxlength="255" required>
                    </div>
                    <div class="field">
                        <label for="code">Kode Verifikasi 6 Digit</label>
                        <input id="code" name="code" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code" required>
                    </div>
                    <button type="submit">Verifikasi Kode</button>
                </form>
                <a class="back" href="{{ route('login') }}">Kembali ke halaman login</a>
            </section>
        </div>
    </main>
</body>
</html>
