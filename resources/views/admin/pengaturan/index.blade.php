<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('kpu-logo.png') }}?v=20261005">
    <title>Pengaturan Administrator | KPU Kota Ternate</title>
    <style>
        :root {
            --brown-deep: #2a0f04;
            --brown: #4a1e0e;
            --brown-soft: #6b2f16;
            --gold: #b8860b;
            --gold-deep: #8a6512;
            --ink: #172b4d;
            --muted: #718096;
            --line: #e5ebf2;
            --canvas: #f3f6fa;
        }

        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; color: var(--ink); background: radial-gradient(circle at 88% 4%, rgba(184,134,11,.09), transparent 24rem), linear-gradient(135deg, #f7f9fc, var(--canvas)); font-family: Inter, Arial, sans-serif; }
        a { color: inherit; text-decoration: none; }
        button, input { font: inherit; }
        .sidebar { position: fixed; inset: 0 auto 0 0; z-index: 20; display: flex; width: 272px; flex-direction: column; padding: 22px 16px; color: white; background: linear-gradient(180deg, var(--brown), var(--brown-soft)); }
        .brand { display: flex; align-items: center; gap: 12px; padding: 5px 10px 27px; }
        .brand-mark { display: grid; width: 47px; height: 47px; place-items: center; border: 2px solid #e9eef5; border-radius: 13px; background: white; box-shadow: 0 8px 18px rgba(0,0,0,.16); }
        .brand-mark img { width: 38px; height: 38px; object-fit: contain; }
        .brand strong, .brand small { display: block; }
        .brand strong { font-size: 16px; }
        .brand small { margin-top: 3px; color: rgba(255,255,255,.58); font-size: 10px; }
        .section-label { margin: 0 10px 9px; color: rgba(255,255,255,.42); font-size: 10px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; }
        .nav { flex: 1; }
        .nav-link { display: flex; align-items: center; gap: 12px; margin-bottom: 5px; padding: 12px 13px; border-radius: 12px; color: rgba(255,255,255,.72); font-size: 13px; font-weight: 650; transition: .2s; }
        .nav-link:hover, .nav-link.active { color: white; background: linear-gradient(135deg, var(--gold), var(--gold-deep)); box-shadow: 0 9px 20px rgba(184,134,11,.22); }
        .nav-icon { display: grid; width: 31px; height: 31px; flex-shrink: 0; place-items: center; border-radius: 9px; color: #f6e6b8; background: rgba(255,255,255,.1); font-size: 15px; }
        .side-footer { padding: 15px 10px 0; border-top: 1px solid rgba(255,255,255,.12); }
        .logout { display: flex; min-width: 104px; height: 42px; align-items: center; justify-content: center; gap: 8px; margin: 0 auto; padding: 0 13px; border: 1px solid rgba(255,255,255,.16); border-radius: 12px; color: rgba(255,255,255,.78); background: rgba(255,255,255,.08); font-size: 11px; font-weight: 800; cursor: pointer; }
        .logout:hover { color: white; background: var(--gold-deep); }
        .main { min-height: 100vh; margin-left: 272px; }
        .topbar { position: sticky; top: 0; z-index: 10; display: flex; min-height: 76px; align-items: center; justify-content: space-between; gap: 18px; padding: 15px 34px; border-bottom: 3px solid var(--gold); background: linear-gradient(108deg, #fff 0%, #fff 58%, var(--brown-soft) 58%, var(--brown) 100%); }
        .crumb small, .crumb strong { display: block; }
        .crumb small { color: #91a0b2; font-size: 11px; }
        .crumb strong { margin-top: 4px; font-size: 14px; }
        .admin-label { padding: 10px 15px; border: 1px solid rgba(255,255,255,.28); border-radius: 12px; color: white; background: rgba(42,15,4,.22); font-size: 12px; font-weight: 800; }
        .content { width: min(1120px, 100%); margin: 0 auto; padding: 38px 34px 48px; }
        .eyebrow { color: var(--gold-deep); font-size: 10px; font-weight: 900; letter-spacing: 1.6px; text-transform: uppercase; }
        h1 { margin: 8px 0 9px; font-size: clamp(26px, 3vw, 36px); letter-spacing: -.6px; }
        .lead { margin: 0 0 27px; color: var(--muted); font-size: 13px; line-height: 1.7; }
        .settings-grid { display: grid; grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr); gap: 18px; align-items: start; }
        .card { overflow: hidden; border: 1px solid var(--line); border-radius: 18px; background: white; box-shadow: 0 12px 32px rgba(25,45,75,.07); }
        .card-head { padding: 22px 24px; border-bottom: 1px solid #eef2f6; background: linear-gradient(180deg, #fff, #fbfcfe); }
        .card-head h2 { margin: 0; font-size: 16px; }
        .card-head p { margin: 6px 0 0; color: var(--muted); font-size: 11px; line-height: 1.5; }
        .card-body { padding: 24px; }
        .profile-mark { display: grid; width: 58px; height: 58px; margin-bottom: 20px; place-items: center; border: 1px solid rgba(184,134,11,.2); border-radius: 18px; color: var(--gold-deep); background: linear-gradient(145deg, #fffaf0, #f7ead0); font-size: 22px; font-weight: 900; }
        .detail { padding: 14px 0; border-top: 1px solid #eef2f6; }
        .detail:first-of-type { border-top: 0; }
        .detail small { display: block; margin-bottom: 5px; color: #8b98a8; font-size: 10px; font-weight: 800; letter-spacing: .7px; text-transform: uppercase; }
        .detail strong { overflow-wrap: anywhere; font-size: 13px; }
        .success { margin-bottom: 18px; padding: 13px 15px; border: 1px solid #ccebd9; border-radius: 12px; color: #17653e; background: #effaf3; font-size: 12px; }
        .error-box { margin-bottom: 18px; padding: 12px 14px; border: 1px solid #fecaca; border-radius: 11px; color: #991b1b; background: #fef2f2; font-size: 12px; }
        .field { margin-bottom: 16px; }
        .field label { display: block; margin-bottom: 7px; color: #52637a; font-size: 11px; font-weight: 800; }
        .field input { width: 100%; padding: 12px 13px; border: 1px solid #dbe3ec; border-radius: 10px; outline: 0; color: var(--ink); background: #fbfcfe; font-size: 13px; transition: .2s; }
        .field input:focus { border-color: var(--gold); background: white; box-shadow: 0 0 0 3px rgba(184,134,11,.12); }
        .field input[readonly] { color: #718096; background: #f2f5f8; }
        .field-error { display: block; margin-top: 6px; color: #b42318; font-size: 11px; }
        .submit { display: inline-flex; min-height: 43px; align-items: center; justify-content: center; gap: 8px; margin-top: 3px; padding: 0 16px; border: 0; border-radius: 11px; color: white; background: linear-gradient(135deg, var(--brown), var(--gold-deep)); box-shadow: 0 8px 18px rgba(74,30,14,.16); font-size: 12px; font-weight: 800; cursor: pointer; }
        .submit:hover { filter: brightness(1.08); }
        @media(max-width: 900px) { .sidebar { display: none; } .main { margin-left: 0; } .topbar { padding: 13px 18px; } .content { padding: 27px 18px; } }
        @media(max-width: 680px) { .settings-grid { grid-template-columns: 1fr; } .content { padding: 23px 15px; } }
    </style>
</head>
<body>
    @include('admin.partials.navigation')

    <main class="main">
        @include('admin.partials.header', ['pageTitle' => 'Pengaturan Administrator'])

        <section class="content">
            <div class="eyebrow">Keamanan Akun</div>
            <h1>Pengaturan Administrator</h1>
            <p class="lead">Perbarui NIP, email, dan kata sandi akun administrator dengan aman.</p>

            <div class="settings-grid">
                <article class="card">
                    <header class="card-head"><h2>Data Administrator</h2><p>Nama akun tetap, NIP dan email dapat diperbarui.</p></header>
                    <div class="card-body">
                        <div class="profile-mark" aria-hidden="true">{{ strtoupper(substr($administrator->name, 0, 1)) }}</div>
                        <div class="detail"><small>Nama</small><strong>{{ $administrator->name }}</strong></div>
                        <div class="detail"><small>Hak Akses</small><strong>Administrator</strong></div>

                        @include('admin.partials.success-notice', ['message' => session('profile_success')])

                        @if($errors->has('nip') || $errors->has('email'))
                            <div class="error-box" role="alert">Periksa kembali NIP dan email administrator.</div>
                        @endif

                        <form action="{{ route('admin.pengaturan.profile') }}" method="POST">
                            @csrf
                            <div class="field">
                                <label for="nip">NIP</label>
                                <input id="nip" name="nip" value="{{ old('nip', $administrator->nip) }}" maxlength="30" autocomplete="username" required>
                                @error('nip')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                            <div class="field">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $administrator->email) }}" maxlength="255" autocomplete="email" required>
                                @error('email')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                            <button class="submit" type="submit">Simpan Data Administrator</button>
                        </form>
                    </div>
                </article>

                <article class="card">
                    <header class="card-head"><h2>Ganti Kata Sandi</h2><p>{{ session('password_recovery_verified_at') && (int) session('password_recovery_verified_at') >= now()->subMinutes(15)->timestamp ? 'Masukkan kata sandi baru minimal 8 karakter.' : 'Masukkan kata sandi saat ini dan kata sandi baru minimal 8 karakter.' }}</p></header>
                    <div class="card-body">
                        @include('admin.partials.success-notice', ['message' => session('success')])

                        @if($errors->has('current_password') || $errors->has('password'))
                            <div class="error-box" role="alert">Periksa kembali data kata sandi yang Anda masukkan.</div>
                        @endif

                        <form action="{{ route('admin.pengaturan.password') }}" method="POST">
                            @csrf
                            @unless(session('password_recovery_verified_at') && (int) session('password_recovery_verified_at') >= now()->subMinutes(15)->timestamp)
                                <div class="field">
                                    <label for="current_password">Kata Sandi Saat Ini</label>
                                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                                    @error('current_password')<small class="field-error">{{ $message }}</small>@enderror
                                </div>
                            @endunless
                            <div class="field">
                                <label for="password">Kata Sandi Baru</label>
                                <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                                @error('password')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                            <div class="field">
                                <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                            </div>
                            <button class="submit" type="submit">Simpan Kata Sandi</button>
                        </form>
                    </div>
                </article>
            </div>
        </section>
    </main>

    @include('admin.partials.logout-confirmation')
    @include('layouts.loading')
</body>
</html>
