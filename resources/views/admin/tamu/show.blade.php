<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('kpu-logo.png') }}?v=20261005">
    <title>Detail Tamu | KPU Kota Ternate</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; color: #172b4d; background: #f3f6fa; font-family: Inter, Arial, sans-serif; }
        .page { width: min(850px, 100%); margin: 0 auto; padding: 28px 18px 45px; }
        .top { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin-bottom: 22px; }
        .eyebrow { color: #b00821; font-size: 10px; font-weight: 800; letter-spacing: 1.4px; text-transform: uppercase; }
        h1 { margin: 7px 0 0; font-size: 28px; }
        .back { padding: 10px 14px; border-radius: 10px; color: #52637a; background: white; font-size: 12px; text-decoration: none; box-shadow: 0 5px 18px rgba(25,45,75,.06); }
        .card { padding: 26px; border: 1px solid #e3eaf1; border-radius: 18px; background: white; box-shadow: 0 10px 30px rgba(25,45,75,.07); }
        .profile { display: flex; align-items: center; gap: 15px; padding-bottom: 22px; border-bottom: 1px solid #edf1f5; }
        .avatar { display: grid; width: 54px; height: 54px; place-items: center; border-radius: 15px; color: #b00821; background: #fff0f2; font-size: 22px; font-weight: 900; }
        .avatar svg { width: 25px; height: 25px; }
        .profile h2 { margin: 0; font-size: 19px; }
        .profile p { margin: 5px 0 0; color: #8290a3; font-size: 12px; }
        .details { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; padding-top: 22px; }
        .detail { padding: 14px; border-radius: 12px; background: #f8fafc; }
        .detail small, .detail strong { display: block; }
        .detail small { margin-bottom: 6px; color: #8997a8; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
        .detail strong { color: #263a55; font-size: 13px; line-height: 1.5; white-space: pre-line; }
        @media (max-width: 600px) { .top { align-items: flex-start; flex-direction: column; } .details { grid-template-columns: 1fr; } .card { padding: 19px; } }
    </style>
</head>
<body>
    <main class="page">
        <div class="top"><div><div class="eyebrow">Buku Tamu</div><h1>Detail Kunjungan</h1></div><a class="back" href="{{ route('admin.tamu.index') }}">Kembali ke Data Tamu</a></div>
        <section class="card">
            <div class="profile"><div class="avatar" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke-width="1.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 21a8 8 0 0 1 16 0"/></svg></div><div><h2>{{ $tamu->nama }}</h2><p>{{ $tamu->instansi }} &middot; {{ $tamu->tujuan }}</p></div></div>
            <div class="details">
                <div class="detail"><small>Tanggal</small><strong>{{ \Carbon\Carbon::parse($tamu->hari_tanggal)->locale('id')->translatedFormat('l, d F Y') }}</strong></div>
                <div class="detail"><small>Jam</small><strong>{{ $tamu->jam }} WIT</strong></div>
                <div class="detail"><small>Nomor Handphone</small><strong>{{ $tamu->no_handphone }}</strong></div>
                <div class="detail"><small>Alamat</small><strong>{{ $tamu->alamat }}</strong></div>
                <div class="detail"><small>Keperluan</small><strong>{{ $tamu->keperluan }}</strong></div>
                <div class="detail"><small>Paraf</small>@if($tamu->paraf && \Illuminate\Support\Str::startsWith($tamu->paraf, 'data:image/'))<img src="{{ route('admin.tamu.paraf', $tamu) }}" alt="Paraf tamu" style="display:block;max-width:100%;max-height:160px;">@else<strong>{{ $tamu->paraf ? 'Paraf tidak tersedia sebagai gambar' : 'Tidak tersedia' }}</strong>@endif</div>
                <div class="detail"><small>Dokumentasi</small>@if($tamu->documentationPath())<img src="{{ route('admin.tamu.gambar', $tamu) }}" alt="Dokumentasi tamu" style="display:block;max-width:100%;max-height:220px;">@else<strong>Tidak tersedia</strong>@endif</div>
            </div>
        </section>
    </main>
    @include('layouts.loading')
</body>
</html>
