<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('kpu-logo.png') }}?v=20261005">
    <title>Laporan | KPU Kota Ternate</title>
    <style>
        :root { --navy:#4a1e0e; --blue:#6b2f16; --red:#b8860b; --ink:#152943; --muted:#74849a; --line:#e5ebf2; --canvas:#f3f6fa; }
        * { box-sizing:border-box; }
        body { min-height:100vh; margin:0; color:var(--ink); background:var(--canvas); font-family:Inter,Arial,sans-serif; }
        button,a { font:inherit; } a { color:inherit; text-decoration:none; }
        .sidebar { position:fixed; inset:0 auto 0 0; z-index:20; display:flex; width:272px; flex-direction:column; padding:22px 16px; color:white; background:linear-gradient(180deg,var(--navy),var(--blue)); }
        .brand { display:flex; align-items:center; gap:12px; padding:5px 10px 27px; }
        .brand-mark { display:grid; width:47px; height:47px; place-items:center; border:2px solid #e9eef5; border-radius:13px; background:white; box-shadow:0 8px 18px rgba(0,0,0,.16); }
        .brand-mark img { width:38px; height:38px; object-fit:contain; }
        .brand strong,.brand small { display:block; } .brand strong { font-size:16px; } .brand small { margin-top:3px; color:rgba(255,255,255,.58); font-size:10px; }
        .section-label { margin:0 10px 9px; color:rgba(255,255,255,.42); font-size:10px; font-weight:800; letter-spacing:1.5px; text-transform:uppercase; }
        .nav { flex:1; } .nav-link { display:flex; align-items:center; gap:12px; margin-bottom:5px; padding:12px 13px; border-radius:12px; color:rgba(255,255,255,.72); font-size:13px; font-weight:650; }
        .nav-link:hover,.nav-link.active { color:white; background:linear-gradient(135deg,var(--red),#8a6308); box-shadow:0 9px 20px rgba(184,134,11,.22); }
        .nav-icon { display:grid; width:31px; height:31px; flex-shrink:0; place-items:center; border-radius:9px; color:#d6e3f2; background:rgba(255,255,255,.1); font-size:15px; }
        .nav-link.active .nav-icon { background:rgba(255,255,255,.15); }
        .side-footer { padding:15px 10px 0; border-top:1px solid rgba(255,255,255,.12); }
        .logout { display:flex; width:104px; height:42px; align-items:center; justify-content:center; gap:8px; margin:0 auto; border:1px solid rgba(255,255,255,.16); border-radius:12px; color:rgba(255,255,255,.78); background:rgba(255,255,255,.08); font-size:11px; font-weight:800; cursor:pointer; }
        .logout:hover { color:white; background:var(--red); border-color:var(--red); }
        .main { min-height:100vh; margin-left:272px; }
        .topbar { min-height:76px; display:flex; align-items:center; justify-content:space-between; gap:18px; padding:15px 34px; border-bottom:3px solid var(--red); background:linear-gradient(108deg,#fff 0%,#fff 58%,var(--blue) 58%,var(--navy) 100%); }
        .crumb small,.crumb strong { display:block; } .crumb small { color:#91a0b2; font-size:11px; } .crumb strong { margin-top:4px; font-size:14px; }
        .profile { display:flex; align-items:center; gap:9px; padding:10px 15px; border:1px solid rgba(255,255,255,.28); border-radius:12px; color:white; background:rgba(42,15,4,.22); box-shadow:inset 0 1px 0 rgba(255,255,255,.12); } .profile-text strong { display:block; color:white; font-size:12px; }
        .content { width:min(1200px,100%); margin:0 auto; padding:38px 34px; }
        .eyebrow { color:var(--red); font-size:10px; font-weight:900; letter-spacing:1.6px; text-transform:uppercase; } h1 { margin:8px 0 9px; font-size:34px; letter-spacing:-.6px; } .lead { margin:0 0 28px; color:var(--muted); font-size:13px; }
        .summary { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; margin-bottom:20px; } .summary-card { position:relative; overflow:hidden; padding:21px; border:1px solid var(--line); border-radius:17px; background:white; box-shadow:0 10px 28px rgba(25,45,75,.06); } .summary-card::before { content:""; position:absolute; inset:0 0 auto; height:4px; background:#b8860b; } .summary-card:nth-child(2)::before { background:#2e9b72; }
        .summary-label { color:var(--muted); font-size:11px; font-weight:800; letter-spacing:.4px; text-transform:uppercase; } .summary-value { margin-top:9px; font-size:30px; font-weight:900; }
        .report-grid { display:grid; grid-template-columns:1fr; gap:18px; } .report-card { padding:24px; border:1px solid var(--line); border-radius:17px; background:white; box-shadow:0 10px 28px rgba(25,45,75,.06); } .report-card h2 { margin:0; font-size:17px; } .report-card p { min-height:45px; margin:8px 0 22px; color:var(--muted); font-size:12px; line-height:1.6; }
        .export { display:inline-flex; align-items:center; gap:9px; padding:12px 15px; border:0; border-radius:10px; color:white; background:linear-gradient(135deg,var(--red),#8a6308); font-size:12px; font-weight:800; cursor:pointer; box-shadow:0 8px 18px rgba(184,134,11,.18); }
        .export svg { width:16px; height:16px; }
        .export-form { display:grid; max-width:480px; gap:14px; margin-top:22px; }
        .export-field { display:grid; gap:7px; color:#52637a; font-size:11px; font-weight:800; }
        .export-field select,.export-field input { width:100%; min-height:45px; padding:10px 12px; border:1px solid #dbe3ec; border-radius:11px; outline:0; color:var(--ink); background:#fbfcfe; font:inherit; transition:border-color .2s,box-shadow .2s,background .2s; }
        .export-field select:focus,.export-field input:focus { border-color:var(--red); box-shadow:0 0 0 3px rgba(184,134,11,.12); }
        .export-error { margin-bottom:14px; padding:11px 13px; border:1px solid #fecaca; border-radius:10px; color:#991b1b; background:#fef2f2; font-size:12px; }
        .export-field[hidden] { display:none; }
        .export-dialog { position:relative; width:min(460px,100%); padding:30px; overflow:hidden; border:1px solid rgba(184,134,11,.24); border-radius:24px; background:linear-gradient(155deg,#fff 0%,#fffdf8 66%,#fff8e9 100%); box-shadow:0 30px 90px rgba(15,5,1,.34),inset 0 1px 0 #fff; transform:translateY(14px) scale(.985); transition:transform .24s cubic-bezier(.2,.8,.25,1); }
        .export-dialog::before { position:absolute; top:-105px; right:-75px; width:220px; height:220px; border-radius:50%; background:radial-gradient(circle,rgba(184,134,11,.16),transparent 70%); content:""; pointer-events:none; }
        .export-confirmation.is-open .export-dialog { transform:translateY(0) scale(1); }
        .export-dialog__heading { position:relative; display:flex; align-items:center; gap:14px; }
        .export-dialog__icon { display:grid; width:50px; height:50px; flex:0 0 50px; place-items:center; border:1px solid rgba(184,134,11,.22); border-radius:16px; color:#76520c; background:linear-gradient(145deg,#fffdf5,#f8eccb); box-shadow:0 9px 20px rgba(138,101,18,.1); font-size:22px; }
        .export-dialog__icon svg { width:23px; height:23px; }
        .export-dialog__eyebrow { display:block; margin-bottom:5px; color:#8a6512; font-size:9px; font-weight:900; letter-spacing:1.5px; text-transform:uppercase; }
        .export-dialog h2 { position:relative; margin:0; color:#3b1608; font-size:20px; letter-spacing:-.3px; }
        .export-dialog p { position:relative; margin:16px 0 0; color:var(--muted); font-size:12px; line-height:1.65; }
        .export-period-hint { display:flex; align-items:flex-start; gap:9px; padding:11px 12px; border:1px solid #e8edf3; border-radius:11px; color:#718096; background:#f8fafc; font-size:10px; line-height:1.5; }
        .export-period-hint span:first-child { display:block; flex:0 0 auto; color:#8a6512; font-size:13px; }
        .export-period-hint svg { width:14px; height:14px; }
        .note { margin-top:18px; padding:14px 16px; border-radius:12px; color:#65768a; background:#f7f9fb; font-size:11px; line-height:1.6; }
        .export-confirmation { position:fixed; inset:0; z-index:90; display:grid; place-items:center; padding:20px; background:rgba(20,8,2,.52); backdrop-filter:blur(8px); opacity:0; visibility:hidden; pointer-events:none; transition:opacity .2s ease,visibility .2s ease; }
        .export-confirmation.is-open { opacity:1; visibility:visible; pointer-events:auto; }
        .export-dialog { width:min(420px,100%); padding:28px; border:1px solid rgba(184,134,11,.24); border-radius:20px; background:linear-gradient(155deg,#fff,#fffaf0); box-shadow:0 28px 70px rgba(15,5,1,.3); transform:translateY(12px); transition:transform .2s ease; }
        .export-confirmation.is-open .export-dialog { transform:translateY(0); }
        .export-dialog h2 { margin:0; color:#4a1e0e; font-size:20px; }
        .export-dialog p { margin:10px 0 23px; color:var(--muted); font-size:13px; line-height:1.6; }
        .export-actions { display:flex; justify-content:flex-end; gap:9px; margin-top:4px; }
        .export-cancel,.export-confirm { min-height:43px; padding:0 16px; border:0; border-radius:11px; font:inherit; font-size:11px; font-weight:850; cursor:pointer; transition:transform .2s,filter .2s,box-shadow .2s; }
        .export-cancel { color:#52637a; background:#eef2f6; }
        .export-confirm { display:inline-flex; align-items:center; justify-content:center; gap:8px; color:white; background:linear-gradient(135deg,#6b2f16,#b8860b); box-shadow:0 9px 20px rgba(138,101,18,.2); }
        .export-cancel:hover,.export-confirm:hover { filter:brightness(1.04); transform:translateY(-1px); }
        .export-confirm:focus-visible,.export-cancel:focus-visible { outline:3px solid rgba(184,134,11,.35); outline-offset:3px; }
        @media(prefers-reduced-motion:reduce) { .export-dialog,.export-confirmation,.export-cancel,.export-confirm { transition:none; } }
        .menu-toggle { display:none; }
        @media(max-width:1050px) { .sidebar { width:240px; } .main { margin-left:240px; } }
        @media(max-width:760px) { .sidebar { display:none; } .main { margin-left:0; } .topbar { padding:13px 16px; } .content { padding:26px 16px; } h1 { font-size:28px; } .summary,.report-grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
    @include('admin.partials.navigation')

    <main class="main">
        @include('admin.partials.header', ['pageTitle' => 'Laporan Administrator'])
        <section class="content">
            <div class="eyebrow">Pusat Laporan</div>
            <h1>Ekspor Data KPU</h1>
            <p class="lead">Unduh data tamu dalam format Excel dengan tabel rapi, paraf, dan gambar dokumentasi.</p>
            <div class="summary"><div class="summary-card"><div class="summary-label">Total Tamu</div><div class="summary-value">{{ $totalTamu }}</div></div><div class="summary-card"><div class="summary-label">Tamu Hari Ini</div><div class="summary-value">{{ $totalTamuHariIni }}</div></div></div>
            <div class="report-grid">
                <article class="report-card"><h2>Data Tamu</h2><p>Pilih satu bulan tertentu atau ekspor seluruh data kunjungan tamu yang tersimpan di database KPU Kota Ternate.</p><button class="export" id="openExportConfirmation" type="button"><svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v12m0 0 5-5m-5 5-5-5M5 16v4h14v-4"/></svg>Ekspor Data Tamu</button></article>
            </div>
            <div class="note">Data tamu yang diekspor berisi tanggal, jam, nama, instansi, alamat, keperluan, tujuan, nomor handphone, paraf, dan gambar dokumentasi.</div>
        </section>
    </main>
    <div class="export-confirmation" id="exportConfirmation" role="alertdialog" aria-modal="true" aria-labelledby="exportConfirmationTitle" aria-hidden="true">
        <section class="export-dialog">
            <div class="export-dialog__heading">
                <span class="export-dialog__icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v12m0 0 5-5m-5 5-5-5M5 16v4h14v-4"/></svg></span>
                <div><span class="export-dialog__eyebrow">Unduh Laporan Excel</span><h2 id="exportConfirmationTitle">Ekspor data tamu</h2></div>
            </div>
            <p>File Excel akan menyertakan data tamu, paraf, dan gambar dokumentasi sesuai periode yang dipilih.</p>
            @if($errors->hasAny(['jenis', 'bulan']))
                <div class="export-error" role="alert">{{ $errors->first('jenis') ?: $errors->first('bulan') }}</div>
            @endif
            <form class="export-form" id="exportForm" action="{{ route('admin.laporan.export.tamu') }}" method="GET">
                <label class="export-field" for="exportType">Periode ekspor
                    <select id="exportType" name="jenis" required>
                        <option value="all" @selected(old('jenis', 'all') === 'all')>Seluruh data</option>
                        <option value="bulan" @selected(old('jenis') === 'bulan')>Pilih bulan</option>
                    </select>
                </label>
                <label class="export-field" id="exportMonthField" for="exportMonth" hidden>Pilih bulan
                    <input id="exportMonth" name="bulan" type="month" value="{{ old('bulan') }}">
                </label>
                <div class="export-period-hint"><span aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path stroke-linecap="round" stroke-width="1.8" d="M12 11v5m0-8h.01"/></svg></span><span>Foto dan paraf akan disisipkan ke dalam file Excel. Proses unduhan akan dimulai setelah Anda memilih ekspor.</span></div>
                <div class="export-actions">
                    <button class="export-cancel" id="cancelExport" type="button">Batal</button>
                    <button class="export-confirm" type="submit"><svg aria-hidden="true" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v12m0 0 5-5m-5 5-5-5M5 16v4h14v-4"/></svg> Ekspor Data</button>
                </div>
            </form>
        </section>
    </div>
    <script>
        const exportConfirmation = document.getElementById('exportConfirmation');
        const openExportConfirmation = document.getElementById('openExportConfirmation');
        const cancelExport = document.getElementById('cancelExport');
        const exportType = document.getElementById('exportType');
        const exportMonthField = document.getElementById('exportMonthField');
        const exportMonth = document.getElementById('exportMonth');
        const exportForm = document.getElementById('exportForm');

        function closeExportConfirmation() {
            exportConfirmation.classList.remove('is-open');
            exportConfirmation.setAttribute('aria-hidden', 'true');
            openExportConfirmation.focus();
        }

        function updateExportPeriod() {
            const exportByMonth = exportType.value === 'bulan';
            exportMonthField.hidden = !exportByMonth;
            exportMonth.required = exportByMonth;
        }

        exportType.addEventListener('change', updateExportPeriod);
        updateExportPeriod();
        exportForm.addEventListener('submit', function () {
            if (exportForm.checkValidity()) {
                closeExportConfirmation();
            }
        });

        openExportConfirmation.addEventListener('click', function () {
            exportConfirmation.classList.add('is-open');
            exportConfirmation.setAttribute('aria-hidden', 'false');
            cancelExport.focus();
        });
        cancelExport.addEventListener('click', closeExportConfirmation);
        exportConfirmation.addEventListener('click', function (event) {
            if (event.target === exportConfirmation) {
                closeExportConfirmation();
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && exportConfirmation.classList.contains('is-open')) {
                closeExportConfirmation();
            }
        });

        @if($errors->hasAny(['jenis', 'bulan']))
            exportConfirmation.classList.add('is-open');
            exportConfirmation.setAttribute('aria-hidden', 'false');
        @endif
    </script>
    @include('admin.partials.logout-confirmation')
    @include('layouts.loading')
</body>
</html>
