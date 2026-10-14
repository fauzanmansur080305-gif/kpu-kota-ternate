<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard administrator KPU Kota Ternate">
    <link rel="icon" type="image/png" href="{{ asset('kpu-logo.png') }}?v=20261005">
    <title>Dashboard Administrator | KPU Kota Ternate</title>
    <style>
        :root {
            --navy: #4a1e0e;
            --navy-soft: #6b2f16;
            --red: #b8860b;
            --gold: #b8860b;
            --ink: #152943;
            --muted: #74849a;
            --line: #e5ebf2;
            --surface: #ffffff;
            --canvas: #f3f6fa;
            --green: #16845b;
            --amber: #b86c00;
            --violet: #6952a8;
        }

        * { box-sizing: border-box; }
        html { min-width: 320px; }
        body {
            min-height: 100vh;
            margin: 0;
            color: var(--ink);
            background:
                radial-gradient(circle at 88% 4%, rgba(184,134,11,.09), transparent 24rem),
                linear-gradient(135deg, #f7f9fc 0%, var(--canvas) 55%, #eef3f8 100%);
            font-family: Inter, Arial, sans-serif;
        }
        button, a { font: inherit; }
        button { cursor: pointer; }
        a { color: inherit; text-decoration: none; }

        .app-shell { min-height: 100vh; }
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 20;
            display: flex;
            width: 272px;
            flex-direction: column;
            padding: 22px 16px;
            color: white;
            background: linear-gradient(180deg, var(--navy), var(--navy-soft));
            transform: translateX(0);
            transition: transform .25s ease;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 5px 10px 27px;
        }
        .brand-mark {
            display: grid;
            width: 47px;
            height: 47px;
            place-items: center;
            border: 2px solid #e9eef5;
            border-radius: 13px;
            background: white;
            box-shadow: 0 8px 18px rgba(0,0,0,.16);
        }
        .brand-mark img { width: 38px; height: 38px; object-fit: contain; display: block; }
        .brand strong, .brand small { display: block; }
        .brand strong { font-size: 16px; letter-spacing: .5px; }
        .brand small { margin-top: 3px; color: rgba(255,255,255,.58); font-size: 10px; }
        .section-label {
            margin: 0 10px 9px;
            color: rgba(255,255,255,.42);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .nav { flex: 1; }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 5px;
            padding: 12px 13px;
            border-radius: 12px;
            color: rgba(255,255,255,.72);
            font-size: 13px;
            font-weight: 650;
            transition: .2s;
        }
        .nav-link:hover { color: white; background: rgba(255,255,255,.09); }
        .nav-link.active { color: white; background: linear-gradient(135deg, var(--red), #8a6308); box-shadow: 0 9px 20px rgba(184,134,11,.22); }
        .nav-icon {
            display: grid;
            width: 31px;
            height: 31px;
            place-items: center;
            border-radius: 9px;
            color: #d6e3f2;
            background: rgba(255,255,255,.1);
            font-size: 15px;
        }
        .nav-link.active .nav-icon { background: rgba(255,255,255,.15); }
        .nav-count { margin-left: auto; padding: 3px 7px; border-radius: 999px; color: #dbe8f5; background: rgba(255,255,255,.11); font-size: 10px; }
        .side-profile { padding: 15px 10px 0; border-top: 1px solid rgba(255,255,255,.12); }
        .logout { display: flex; width: fit-content; min-width: 104px; height: 42px; align-items: center; justify-content: center; gap: 8px; margin: 0 auto; padding: 0 13px; border: 1px solid rgba(255,255,255,.16); border-radius: 12px; color: rgba(255,255,255,.78); background: rgba(255,255,255,.08); font-size: 19px; transition: .2s; }
        .logout-text { font-size: 11px; font-weight: 800; }
        .logout:hover { color: white; background: var(--red); border-color: var(--red); transform: translateY(-2px); }

        .main { min-height: 100vh; margin-left: 272px; }
        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            min-height: 76px;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 15px 34px;
            border-bottom: 3px solid var(--red);
            background: linear-gradient(108deg, #ffffff 0%, #ffffff 58%, var(--navy-soft) 58%, var(--navy) 100%);
            backdrop-filter: blur(16px);
        }
        .menu-toggle { display: none; width: 40px; height: 40px; border: 1px solid var(--line); border-radius: 11px; color: var(--ink); background: white; font-size: 21px; }
        .crumb small, .crumb strong { display: block; }
        .crumb small { color: #91a0b2; font-size: 11px; }
        .crumb strong { margin-top: 4px; font-size: 14px; }
        .crumb .dashboard-title { margin: 0; padding-left: 13px; border-left: 3px solid var(--gold); color: var(--navy); font-size: 15px; font-weight: 900; letter-spacing: .15px; }
        .top-actions { display: flex; align-items: center; gap: 12px; }
        .admin-label { padding: 10px 15px; border: 1px solid rgba(255,255,255,.28); border-radius: 12px; color: white; background: rgba(42,15,4,.22); font-size: 12px; font-weight: 800; letter-spacing: .3px; box-shadow: inset 0 1px 0 rgba(255,255,255,.12); }
        .dashboard-time { position: relative; display: flex; align-items: center; justify-content: space-between; gap: 20px; min-height: 92px; margin: 20px 34px 0; padding: 18px 24px; overflow: hidden; border: 1px solid rgba(226,192,105,.32); border-radius: 18px; color: #fff; background: radial-gradient(circle at 90% 15%, rgba(226,192,105,.2), transparent 17rem), linear-gradient(115deg, #3b1018, #651b2a 58%, #3b1018); box-shadow: 0 14px 32px rgba(59,16,24,.18), inset 0 1px 0 rgba(255,255,255,.08); }
        .dashboard-time::after { position: absolute; right: -32px; bottom: -76px; width: 190px; height: 190px; border: 1px solid rgba(226,192,105,.14); border-radius: 50%; box-shadow: 0 0 0 18px rgba(226,192,105,.035), 0 0 0 38px rgba(226,192,105,.025); content: ""; pointer-events: none; }
        .dashboard-time-info { display: flex; align-items: center; gap: 14px; }
        .dashboard-time-icon { display: grid; width: 46px; height: 46px; flex: 0 0 46px; place-items: center; border: 1px solid rgba(226,192,105,.4); border-radius: 14px; color: #f1d27b; background: rgba(255,255,255,.08); box-shadow: inset 0 1px 0 rgba(255,255,255,.1); }
        .dashboard-time-icon svg { width: 22px; height: 22px; }
        .dashboard-time-label { display: block; color: #e4c66f; font-size: 10px; font-weight: 900; letter-spacing: 1.4px; text-transform: uppercase; }
        .dashboard-date { display: block; margin-top: 6px; color: rgba(255,255,255,.76); font-size: 12px; font-weight: 600; }
        .dashboard-clock { position: relative; z-index: 1; display: flex; align-items: baseline; gap: 8px; padding: 8px 14px; border: 1px solid rgba(226,192,105,.28); border-radius: 13px; background: rgba(24,7,12,.22); }
        .dashboard-time-value { color: #f4d477; font-size: clamp(22px, 3vw, 30px); font-variant-numeric: tabular-nums; font-weight: 900; letter-spacing: 1px; line-height: 1; }
        .dashboard-time-zone { color: rgba(255,255,255,.7); font-size: 10px; font-weight: 900; letter-spacing: 1.2px; }

        .content { width: min(1380px, 100%); margin: 0 auto; padding: 38px 34px 46px; }
        .welcome { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 27px; padding: 3px 0 0; }
        .eyebrow { margin-bottom: 9px; color: var(--red); font-size: 10px; font-weight: 900; letter-spacing: 1.6px; text-transform: uppercase; }
        h1 { margin: 0; color: var(--ink); font-size: clamp(26px, 3vw, 38px); letter-spacing: -.8px; }
        .lead { max-width: 650px; margin: 9px 0 0; color: var(--muted); font-size: 13px; line-height: 1.7; }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .stat-card, .panel, .quick-panel { border: 1px solid var(--line); border-radius: 17px; background: var(--surface); box-shadow: 0 11px 30px rgba(25,45,75,.065); }
        .stat-card { position: relative; display: block; min-height: 165px; overflow: hidden; padding: 21px; color: inherit; text-decoration: none; transition: transform .2s, box-shadow .2s, border-color .2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 15px 32px rgba(25,45,75,.11); }
        .stat-card:focus-visible { outline: 3px solid rgba(184,134,11,.45); outline-offset: 3px; }
        .stat-card::before { content: ""; position: absolute; inset: 0 0 auto; height: 4px; background: #b8860b; }
        .stat-card:nth-child(2)::before { background: var(--green); }
        .stat-card:nth-child(3)::before { background: var(--amber); }
        .stat-card:nth-child(4)::before { background: var(--violet); }

        .recent-list { padding: 10px 22px 18px; }
        .recent-item { display: flex; align-items: center; gap: 13px; padding: 14px 0; border-bottom: 1px solid #f0f4f8; }
        .recent-item:last-child { border-bottom: 0; }
        .recent-avatar { display: grid; width: 40px; height: 40px; flex-shrink: 0; place-items: center; border-radius: 12px; color: #8a6308; background: #fdf6e3; font-size: 15px; font-weight: 900; }
        .recent-body { min-width: 0; flex: 1; }
        .recent-body strong { display: block; color: var(--ink); font-size: 13px; }
        .recent-body small { display: block; margin-top: 4px; overflow: hidden; color: #98a6b6; font-size: 11px; text-overflow: ellipsis; white-space: nowrap; }
        .recent-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; }
        .recent-time { color: #9aa8b8; font-size: 11px; font-weight: 700; }
        .stat-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
        .stat-label { color: var(--muted); font-size: 12px; font-weight: 650; }
        .stat-number { margin-top: 8px; color: var(--ink); font-size: 30px; font-weight: 900; letter-spacing: -.8px; }
        .stat-icon { display: grid; width: 42px; height: 42px; place-items: center; border-radius: 12px; font-size: 18px; }
        .stat-icon svg { width: 20px; height: 20px; }
        .stat-icon.blue { color: #8a6308; background: #fdf6e3; }
        .stat-icon.green { color: var(--green); background: #ecfaf4; }
        .stat-icon.amber { color: var(--amber); background: #fff6e6; }
        .stat-icon.violet { color: var(--violet); background: #f3efff; }
        .stat-meta { display: flex; align-items: center; gap: 7px; margin-top: 19px; color: #9aa8b8; font-size: 10px; }
        .pill { padding: 4px 7px; border-radius: 999px; font-size: 10px; font-weight: 800; }
        .pill.gray { color: #64748b; background: #f0f3f6; }
        .pill.green { color: var(--green); background: #e9f8f0; }
        .pill.amber { color: var(--amber); background: #fff3d8; }
        .pill.violet { color: var(--violet); background: #f1edff; }

        .main-grid { display: grid; grid-template-columns: minmax(0, 2fr) minmax(290px, 1fr); gap: 18px; margin-top: 18px; }
        .panel-head { display: flex; align-items: center; justify-content: space-between; gap: 15px; padding: 20px 22px; border-bottom: 1px solid #eef2f6; background: linear-gradient(180deg, #ffffff, #fbfcfe); }
        .panel-title h2 { margin: 0; color: var(--ink); font-size: 15px; letter-spacing: -.1px; }
        .panel-title p { margin: 6px 0 0; color: #98a6b6; font-size: 11px; }
        .period { padding: 7px 10px; border: 1px solid var(--line); border-radius: 8px; color: var(--muted); background: white; font-size: 10px; }
        .chart-wrap { padding: 22px; }
        .chart { display: flex; height: 220px; align-items: flex-end; gap: 10px; padding: 17px 15px 0; border: 1px solid #edf1f5; border-radius: 13px; background-color: #f7f9fb; background-image: repeating-linear-gradient(to bottom, transparent 0, transparent 43px, rgba(145,160,178,.12) 44px); }
        .bar { flex: 1; min-width: 8px; border-radius: 6px 6px 2px 2px; background: linear-gradient(180deg, #b8860b, #e2c069); transition: .2s; }
        .bar:hover { background: linear-gradient(180deg, #6b2f16, #b8602f); }
        .chart-labels { display: grid; grid-template-columns: repeat(7, 1fr); margin-top: 10px; color: #a1adba; font-size: 10px; text-align: center; }
        .empty { display: grid; min-height: 280px; place-items: center; padding: 25px; text-align: center; }
        .empty-icon { display: grid; width: 62px; height: 62px; place-items: center; border: 7px solid #fbf1d8; border-radius: 20px; color: var(--gold); background: #fffaf0; font-size: 25px; box-shadow: 0 8px 18px rgba(184,134,11,.1); }
        .empty-icon svg { width: 25px; height: 25px; }
        .empty h3 { margin: 14px 0 5px; font-size: 13px; }
        .empty p { max-width: 190px; margin: 0; color: #99a8b8; font-size: 11px; line-height: 1.6; }

        .panel, .quick-panel { position: relative; overflow: hidden; }
        .panel::before, .quick-panel::before { content: ""; position: absolute; inset: 0 0 auto; height: 3px; background: linear-gradient(90deg, var(--gold), #e2c069); }
        .quick-panel { margin-top: 18px; padding: 22px; background: linear-gradient(145deg, #ffffff, #fbfcfe); }
        .quick-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 18px; }
        .quick-item { display: block; min-height: 145px; overflow: hidden; padding: 18px; border: 1px solid var(--line); border-radius: 15px; background: rgba(255,255,255,.82); transition: .22s; }
        .quick-item:hover { border-color: #c1cfdd; background: white; transform: translateY(-4px); box-shadow: 0 13px 25px rgba(25,45,75,.1); }
        .quick-icon { display: grid; width: 42px; height: 42px; place-items: center; margin-bottom: 18px; border-radius: 12px; color: #8a6308; background: linear-gradient(145deg, #fdf6e3, #f7ead0); font-size: 18px; box-shadow: inset 0 1px 0 rgba(255,255,255,.8); }
        .quick-icon svg { width: 20px; height: 20px; }
        .quick-item:nth-child(1) { border-top: 3px solid #b8860b; background: linear-gradient(145deg, #ffffff, #fdf6e3); }
        .quick-item:nth-child(2) { border-top: 3px solid #2e9b72; background: linear-gradient(145deg, #ffffff, #f1fbf6); }
        .quick-item:nth-child(3) { border-top: 3px solid #8067c5; background: linear-gradient(145deg, #ffffff, #f7f4ff); }
        .quick-item:nth-child(4) { border-top: 3px solid #d18a20; background: linear-gradient(145deg, #ffffff, #fffaf0); }
        .quick-item:nth-child(2) .quick-icon { color: var(--green); background: linear-gradient(145deg, #ecfaf4, #d8f3e7); }
        .quick-item:nth-child(3) .quick-icon { color: var(--violet); background: linear-gradient(145deg, #f3efff, #e8e0ff); }
        .quick-item:nth-child(4) .quick-icon { color: var(--amber); background: linear-gradient(145deg, #fff6e6, #ffebbd); }
        .quick-item strong, .quick-item small { display: block; }
        .quick-item strong { font-size: 13px; }
        .quick-item small { display: block; max-width: 145px; margin-top: 6px; color: #98a6b6; font-size: 10px; line-height: 1.45; }
        .visitor-matrix { padding: 18px; background: radial-gradient(circle at 100% 0, rgba(226,192,105,.14), transparent 12rem), linear-gradient(145deg, #3b1018, #611b2b); color: white; }
        .visitor-matrix__total { position: relative; display: flex; align-items: flex-end; justify-content: space-between; gap: 14px; padding: 2px 0 17px; }
        .visitor-matrix__eyebrow { display: block; color: #e8ca75; font-size: 9px; font-weight: 900; letter-spacing: 1.3px; text-transform: uppercase; }
        .visitor-matrix__number { display: block; margin-top: 7px; color: #fff; font-size: 36px; font-weight: 900; letter-spacing: -1px; line-height: 1; }
        .visitor-matrix__caption { display: block; margin-top: 6px; color: rgba(255,255,255,.64); font-size: 10px; }
        .visitor-matrix__emblem { display: grid; width: 48px; height: 48px; flex: 0 0 48px; place-items: center; border: 1px solid rgba(232,202,117,.35); border-radius: 15px; color: #f1d27b; background: rgba(255,255,255,.07); }
        .visitor-matrix__emblem svg { width: 24px; height: 24px; }
        .visitor-matrix__grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 9px; }
        .visitor-matrix__cell { min-width: 0; padding: 12px; border: 1px solid rgba(255,255,255,.11); border-radius: 13px; background: rgba(255,255,255,.065); }
        .visitor-matrix__cell-label { display: block; overflow: hidden; color: rgba(255,255,255,.7); font-size: 9px; font-weight: 750; text-overflow: ellipsis; white-space: nowrap; }
        .visitor-matrix__cell-value { display: flex; align-items: baseline; justify-content: space-between; gap: 8px; margin-top: 7px; }
        .visitor-matrix__cell-value strong { color: #fff; font-size: 20px; font-weight: 900; }
        .visitor-matrix__cell-value small { color: #e8ca75; font-size: 9px; font-weight: 800; white-space: nowrap; }
        .visitor-matrix__track { height: 5px; margin-top: 9px; overflow: hidden; border-radius: 99px; background: rgba(255,255,255,.13); }
        .visitor-matrix__track span { display: block; height: 100%; border-radius: inherit; background: linear-gradient(90deg, #b8860b, #f1d27b); }

        .sidebar-overlay { display: none; }
        @media (max-width: 1100px) {
            .sidebar { width: 240px; transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .menu-toggle { display: grid; }
            .sidebar-overlay.open { display: block; position: fixed; inset: 0; z-index: 19; background: rgba(7,26,54,.52); }
            .content { padding: 28px 24px; }
            .dashboard-time { margin-right: 24px; margin-left: 24px; }
            .stats { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 760px) {
            .topbar { padding: 13px 16px; }
            .crumb { display: none; }
            .content { padding: 23px 16px; }
            .welcome { display: block; }
            .main-grid { grid-template-columns: 1fr; }
            .quick-grid { grid-template-columns: repeat(2, 1fr); }
            .dashboard-time { align-items: flex-start; flex-direction: column; gap: 13px; margin: 16px 16px 0; padding: 15px; }
            .dashboard-time-info { gap: 11px; }
            .dashboard-clock { align-self: flex-end; }
            .visitor-matrix { padding: 15px; }
        }
        @media (max-width: 460px) {
            .stats { grid-template-columns: 1fr; }
            .quick-grid { grid-template-columns: 1fr; }
            .stat-card { padding: 17px; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('admin.partials.navigation')

        <div class="main">
            @include('admin.partials.header', ['pageTitle' => 'Dashboard Admin'])
            @include('admin.partials.success-notice', ['message' => session('password_recovery_notice')])

            <div class="dashboard-time" role="status">
                <div class="dashboard-time-info">
                    <span class="dashboard-time-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 7v5l3 2"/></svg></span>
                    <span><span class="dashboard-time-label">Waktu Administrator</span><time class="dashboard-date" id="dashboardDate">{{ now('Asia/Jayapura')->locale('id')->translatedFormat('l, d F Y') }}</time></span>
                </div>
                <span class="dashboard-clock"><time class="dashboard-time-value" id="dashboardClock" datetime="{{ now('Asia/Jayapura')->toIso8601String() }}">{{ now('Asia/Jayapura')->format('H:i:s') }}</time><span class="dashboard-time-zone">WIT</span></span>
            </div>

            <main class="content">
                <section class="welcome">
                    <div><div class="eyebrow">Ringkasan Sistem</div><h1>Selamat datang, Admin</h1><p class="lead">Kelola data kunjungan dan laporan melalui dashboard administrator.</p></div>
                </section>

                <section class="stats">
                    <a class="stat-card" href="{{ route('admin.tamu.index', ['filter' => 'all']) }}"><div class="stat-top"><div><div class="stat-label">Total Tamu</div><div class="stat-number">{{ $totalTamu }}</div></div><div class="stat-icon blue"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 4.75A2.75 2.75 0 0 1 8.75 2H20v17H8.75A2.75 2.75 0 0 0 6 21.75v-17Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18.5A2.5 2.5 0 0 0 8.5 21H20M10 7h6m-6 4h6"/></svg></div></div><div class="stat-meta"><span class="pill gray">Keseluruhan</span>Lihat semua data</div></a>
                    <a class="stat-card" href="{{ route('admin.tamu.index', ['filter' => 'today']) }}"><div class="stat-top"><div><div class="stat-label">Tamu Hari Ini</div><div class="stat-number">{{ $tamuHariIni }}</div></div><div class="stat-icon green"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6"/></svg></div></div><div class="stat-meta"><span class="pill green">Hari ini</span>Lihat kunjungan hari ini</div></a>
                    <a class="stat-card" href="{{ route('admin.tamu.index', ['filter' => 'month']) }}"><div class="stat-top"><div><div class="stat-label">Kunjungan Bulan Ini</div><div class="stat-number">{{ $tamuBulanIni }}</div></div><div class="stat-icon amber"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 3v5h5M8 12h8M8 16h8"/></svg></div></div><div class="stat-meta"><span class="pill amber">Bulan berjalan</span>Lihat data bulan ini</div></a>
                </section>

                @php
                    $tamuHariIniShare = $totalTamu > 0 ? min(100, (int) round(($tamuHariIni / $totalTamu) * 100)) : 0;
                    $tamuBulanIniShare = $totalTamu > 0 ? min(100, (int) round(($tamuBulanIni / $totalTamu) * 100)) : 0;
                @endphp

                <section class="main-grid">
                    <article class="panel"><div class="panel-head"><div class="panel-title"><h2>Daftar Tamu Terbaru</h2><p>Lima kunjungan terakhir</p></div></div>@if($tamuTerbaru->isEmpty())<div class="empty"><div><div class="empty-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 4.75A2.75 2.75 0 0 1 8.75 2H20v17H8.75A2.75 2.75 0 0 0 6 21.75v-17Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18.5A2.5 2.5 0 0 0 8.5 21H20M10 7h6m-6 4h6"/></svg></div><h3>Belum ada data tamu</h3><p>Data kunjungan tamu akan muncul di sini.</p></div></div>@else<div class="recent-list">@foreach($tamuTerbaru as $tamu)<article class="recent-item"><div class="recent-avatar">{{ strtoupper(substr($tamu->nama, 0, 1)) }}</div><div class="recent-body"><strong>{{ $tamu->nama }}</strong><small>{{ $tamu->instansi }} &middot; {{ $tamu->tujuan }}</small></div><div class="recent-meta"><span class="pill gray">{{ \Carbon\Carbon::parse($tamu->hari_tanggal)->locale('id')->translatedFormat('d M Y') }}</span><span class="recent-time">{{ $tamu->jam }}</span></div></article>@endforeach</div>@endif</article>
                    <article class="panel">
                        <div class="panel-head">
                            <div class="panel-title">
                                <h2>Matriks Data Tamu</h2>
                                <p>Distribusi periode dari seluruh kunjungan</p>
                            </div>
                        </div>
                        <div class="visitor-matrix">
                            <div class="visitor-matrix__total">
                                <div>
                                    <span class="visitor-matrix__eyebrow">Total Keseluruhan</span>
                                    <strong class="visitor-matrix__number">{{ $totalTamu }}</strong>
                                    <span class="visitor-matrix__caption">kunjungan tercatat di sistem</span>
                                </div>
                                <span class="visitor-matrix__emblem" aria-hidden="true">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 4.75A2.75 2.75 0 0 1 8.75 2H20v17H8.75A2.75 2.75 0 0 0 6 21.75v-17Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18.5A2.5 2.5 0 0 0 8.5 21H20M10 7h6m-6 4h6"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="visitor-matrix__grid">
                                <div class="visitor-matrix__cell">
                                    <span class="visitor-matrix__cell-label">Hari ini</span>
                                    <div class="visitor-matrix__cell-value">
                                        <strong>{{ $tamuHariIni }}</strong>
                                        <small>{{ $tamuHariIniShare }}%</small>
                                    </div>
                                    <div class="visitor-matrix__track" role="progressbar" aria-label="Persentase kunjungan hari ini dari seluruh data" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $tamuHariIniShare }}">
                                        <span style="width: {{ $tamuHariIniShare }}%"></span>
                                    </div>
                                </div>
                                <div class="visitor-matrix__cell">
                                    <span class="visitor-matrix__cell-label">Bulan ini</span>
                                    <div class="visitor-matrix__cell-value">
                                        <strong>{{ $tamuBulanIni }}</strong>
                                        <small>{{ $tamuBulanIniShare }}%</small>
                                    </div>
                                    <div class="visitor-matrix__track" role="progressbar" aria-label="Persentase kunjungan bulan ini dari seluruh data" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $tamuBulanIniShare }}">
                                        <span style="width: {{ $tamuBulanIniShare }}%"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </section>

                <section class="quick-panel"><div class="panel-title"><h2>Akses Cepat</h2><p>Fitur yang sering digunakan administrator</p></div><div class="quick-grid"><a class="quick-item" href="{{ route('admin.tamu.index', ['filter' => 'all']) }}"><span class="quick-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 4.75A2.75 2.75 0 0 1 8.75 2H20v17H8.75A2.75 2.75 0 0 0 6 21.75v-17Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18.5A2.5 2.5 0 0 0 8.5 21H20M10 7h6m-6 4h6"/></svg></span><strong>Data Tamu</strong><small>Kelola seluruh data kunjungan</small></a><a class="quick-item" href="{{ route('admin.laporan.index') }}"><span class="quick-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v12m0 0 5-5m-5 5-5-5M5 16v4h14v-4"/></svg></span><strong>Export Data</strong><small>Download laporan Excel</small></a><a class="quick-item" href="{{ route('admin.pengaturan.index') }}"><span class="quick-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm7.4 6.5.1.1a1.8 1.8 0 1 1-2.5 2.5l-.1-.1a1.8 1.8 0 0 0-3.1 1.3v.2a1.8 1.8 0 1 1-3.6 0v-.2a1.8 1.8 0 0 0-3.1-1.3l-.1.1a1.8 1.8 0 1 1-2.5-2.5l.1-.1a1.8 1.8 0 0 0-1.3-3.1h-.2a1.8 1.8 0 1 1 0-3.6h.2a1.8 1.8 0 0 0 1.3-3.1l-.1-.1a1.8 1.8 0 1 1 2.5-2.5l.1.1a1.8 1.8 0 0 0 3.1-1.3v-.2a1.8 1.8 0 1 1 3.6 0v.2a1.8 1.8 0 0 0 3.1 1.3l.1-.1a1.8 1.8 0 1 1 2.5 2.5l-.1.1a1.8 1.8 0 0 0 1.3 3.1h.2a1.8 1.8 0 1 1 0 3.6h-.2a1.8 1.8 0 0 0-1.3 3.1Z"/></svg></span><strong>Pengaturan</strong><small>Data administrator dan kata sandi</small></a></div></section>
            </main>
        </div>
    </div>
    <script>
        const dashboardClock = document.getElementById('dashboardClock');
        const dashboardDate = document.getElementById('dashboardDate');
        const jayapuraWeekdays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const jayapuraMonths = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        function updateDashboardClock() {
            if (!dashboardClock || !dashboardDate) {
                return;
            }

            const now = new Date();
            const jayapuraTime = new Date(now.getTime() + (9 * 60 * 60 * 1000));
            const pad = function (value) {
                return String(value).padStart(2, '0');
            };
            const date = jayapuraWeekdays[jayapuraTime.getUTCDay()] + ', '
                + pad(jayapuraTime.getUTCDate()) + ' '
                + jayapuraMonths[jayapuraTime.getUTCMonth()] + ' '
                + jayapuraTime.getUTCFullYear();
            const time = pad(jayapuraTime.getUTCHours()) + ':'
                + pad(jayapuraTime.getUTCMinutes()) + ':'
                + pad(jayapuraTime.getUTCSeconds());

            dashboardDate.textContent = date;
            dashboardClock.textContent = time;
            dashboardClock.dateTime = now.toISOString();
        }

        updateDashboardClock();
        window.setInterval(updateDashboardClock, 1000);

    </script>
    <script>
        window.history.pushState({ adminDashboard: true }, '', window.location.href);
        window.addEventListener('popstate', function () {
            window.history.pushState({ adminDashboard: true }, '', window.location.href);
        });
    </script>
    @include('admin.partials.logout-confirmation')
    @include('layouts.loading')
</body>
</html>
