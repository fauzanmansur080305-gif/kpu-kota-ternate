<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('kpu-logo.png') }}?v=20261005">
    <title>Edit Tamu | KPU Kota Ternate</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; color: #172b4d; background: #f3f6fa; font-family: Inter, Arial, sans-serif; }
        .page { width: min(900px, 100%); margin: 0 auto; padding: 28px 18px 45px; }
        .top { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin-bottom: 22px; }
        .eyebrow { color: #b00821; font-size: 10px; font-weight: 800; letter-spacing: 1.4px; text-transform: uppercase; }
        h1 { margin: 7px 0 0; font-size: 28px; }
        .back { padding: 10px 14px; border-radius: 10px; color: #52637a; background: white; font-size: 12px; text-decoration: none; box-shadow: 0 5px 18px rgba(25,45,75,.06); }
        .card { padding: 26px; border: 1px solid #e3eaf1; border-radius: 18px; background: white; box-shadow: 0 10px 30px rgba(25,45,75,.07); }
        .errors { margin-bottom: 18px; padding: 13px; border-radius: 10px; color: #991b1b; background: #fff1f2; font-size: 12px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .field.full { grid-column: 1 / -1; }
        label { display: block; margin-bottom: 7px; color: #52637a; font-size: 12px; font-weight: 700; }
        input, textarea, select { width: 100%; padding: 12px 13px; border: 1px solid #dbe4ed; border-radius: 10px; outline: 0; color: #172b4d; background: #fbfcfe; font: inherit; font-size: 13px; }
        textarea { min-height: 100px; resize: vertical; }
        input:focus, textarea:focus, select:focus { border-color: #b00821; box-shadow: 0 0 0 3px rgba(176,8,33,.1); }
        .submit { margin-top: 22px; padding: 13px 18px; border: 0; border-radius: 10px; color: white; background: linear-gradient(135deg, #b00821, #8d071a); font-weight: 800; cursor: pointer; }
        @media (max-width: 650px) { .top { align-items: flex-start; flex-direction: column; } .form-grid { grid-template-columns: 1fr; } .field.full { grid-column: auto; } .card { padding: 19px; } }
    </style>
</head>
<body>
    <main class="page">
        <div class="top"><div><div class="eyebrow">Buku Tamu</div><h1>Edit Data Tamu</h1></div><a class="back" href="{{ route('admin.tamu.index') }}">Kembali ke Data Tamu</a></div>
        <section class="card">
            @if($errors->any())<div class="errors">{{ $errors->first() }}</div>@endif
            <form action="{{ route('admin.tamu.update', $tamu) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div><label for="hari_tanggal">Hari / Tanggal</label><input id="hari_tanggal" name="hari_tanggal" type="date" value="{{ old('hari_tanggal', $tamu->hari_tanggal) }}" required></div>
                    <div><label for="jam">Jam</label><input id="jam" name="jam" type="time" value="{{ old('jam', substr($tamu->jam, 0, 5)) }}" required></div>
                    <div><label for="nama">Nama Lengkap</label><input id="nama" name="nama" value="{{ old('nama', $tamu->nama) }}" required></div>
                    <div><label for="instansi">Instansi</label><input id="instansi" name="instansi" value="{{ old('instansi', $tamu->instansi) }}" required></div>
                    <div class="field full"><label for="alamat">Alamat</label><textarea id="alamat" name="alamat" required>{{ old('alamat', $tamu->alamat) }}</textarea></div>
                    <div class="field full"><label for="keperluan">Keperluan</label><textarea id="keperluan" name="keperluan" required>{{ old('keperluan', $tamu->keperluan) }}</textarea></div>
                    <div><label for="tujuan">Tujuan</label><input id="tujuan" name="tujuan" value="{{ old('tujuan', $tamu->tujuan) }}" required></div>
                    <div><label for="no_handphone">Nomor Handphone</label><input id="no_handphone" name="no_handphone" value="{{ old('no_handphone', $tamu->no_handphone) }}" required></div>
                    @include('admin.tamu.partials.signature-pad', ['signaturePrefix' => 'edit', 'signatureValue' => $tamu->paraf])
                    <div class="field full">
                        <label for="gambar">Gambar Dokumentasi</label>
                        @if($tamu->documentationPath())
                            <img src="{{ route('admin.tamu.gambar', $tamu) }}" alt="Gambar dokumentasi saat ini" style="display:block;max-width:100%;max-height:220px;margin-bottom:10px;border-radius:10px;">
                        @endif
                        <input id="gambar" name="gambar" type="file" accept="image/png,image/jpeg">
                    </div>
                </div>
                <button class="submit" type="submit">Simpan Perubahan</button>
            </form>
        </section>
    </main>
    @include('layouts.loading')
</body>
</html>
