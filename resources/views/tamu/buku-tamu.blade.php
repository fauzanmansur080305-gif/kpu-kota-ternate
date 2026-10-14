<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('kpu-logo.png') }}?v=20261005">

    <title>Buku Tamu Digital | KPU Kota Ternate</title>

    <style>
        .success-alert {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            border-radius: 14px;
            padding: 16px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 5px 20px rgba(22, 101, 52, .06);
        }

        .success-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dcfce7;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 800;
            font-size: 18px;
        }
        .success-icon svg { width: 20px; height: 20px; }

        .success-alert strong {
            display: block;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .success-alert p {
            font-size: 12px;
            margin: 0;
        }

        .visit-feedback {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 60;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            width: min(380px, calc(100% - 32px));
            padding: 16px 18px;
            border: 1px solid;
            border-radius: 16px;
            box-shadow: 0 16px 38px rgba(7, 26, 54, .18);
            opacity: 0;
            transform: translateY(-14px);
            pointer-events: none;
            transition: opacity .25s ease, transform .25s ease;
        }

        .visit-feedback.is-visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .visit-feedback--success {
            border-color: #bbf7d0;
            color: #166534;
            background: #f0fdf4;
        }

        .visit-feedback--error {
            border-color: #fecaca;
            color: #991b1b;
            background: #fef2f2;
        }

        .visit-feedback__icon {
            display: grid;
            place-items: center;
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            border-radius: 50%;
            color: white;
            background: currentColor;
            font-size: 16px;
            font-weight: 900;
        }

        .visit-feedback--success .visit-feedback__icon { color: #16a34a; }
        .visit-feedback--error .visit-feedback__icon { color: #dc2626; }
        .visit-feedback__icon svg { width: 16px; height: 16px; stroke: white; }

        .visit-feedback strong {
            display: block;
            margin-bottom: 3px;
            font-size: 13px;
        }

        .visit-feedback span {
            color: inherit;
            font-size: 11px;
            line-height: 1.45;
        }

        .visit-confirm {
            position: fixed;
            inset: 0;
            z-index: 35;
            display: grid;
            place-items: center;
            padding: 20px;
            background: rgba(7, 26, 54, .5);
            -webkit-backdrop-filter: blur(7px);
            backdrop-filter: blur(7px);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .25s ease, visibility .25s ease;
        }

        .visit-confirm.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .visit-confirm__card {
            width: min(410px, 100%);
            padding: 30px;
            border: 1px solid rgba(255,255,255,.8);
            border-radius: 22px;
            background: white;
            box-shadow: 0 28px 70px rgba(7, 26, 54, .28);
            text-align: center;
            transform: translateY(10px) scale(.98);
            transition: transform .25s ease;
        }

        .visit-confirm.is-visible .visit-confirm__card {
            transform: translateY(0) scale(1);
        }

        .visit-confirm__icon {
            display: grid;
            place-items: center;
            width: 58px;
            height: 58px;
            margin: 0 auto 16px;
            border-radius: 18px;
            color: white;
            background: linear-gradient(135deg, #c49b2c, #e6ce8a);
            box-shadow: 0 10px 24px rgba(196, 155, 44, .24);
            font-size: 25px;
        }

        .visit-confirm h2 {
            margin: 0 0 8px;
            color: #172b4d;
            font-size: 20px;
        }

        .visit-confirm p {
            margin: 0;
            color: #718096;
            font-size: 13px;
            line-height: 1.6;
        }

        .visit-confirm__actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 24px;
        }

        .visit-confirm__button {
            padding: 12px 15px;
            border: 0;
            border-radius: 11px;
            font: inherit;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: .2s;
        }

        .visit-confirm__button:hover { transform: translateY(-1px); }
        .visit-confirm__button--cancel { color: #52637a; background: #eef2f7; }
        .visit-confirm__button--continue { color: white; background: linear-gradient(135deg, #c49b2c, #8a6512); box-shadow: 0 8px 18px rgba(196, 155, 44, .2); }

        /* ================= NOTIFIKASI TERIMA KASIH ================= */

        .visit-success {
            position: fixed;
            inset: 0;
            z-index: 60;
            display: grid;
            place-items: center;
            padding: 20px;
            background: rgba(20, 8, 2, .52);
            -webkit-backdrop-filter: blur(9px) saturate(120%);
            backdrop-filter: blur(9px) saturate(120%);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .3s ease, visibility .3s ease;
        }

        .visit-success.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        body.success-open {
            overflow: hidden;
        }

        .visit-success__card {
            position: relative;
            width: min(420px, 100%);
            padding: 38px 32px 32px;
            overflow: hidden;
            border: 1px solid rgba(196, 155, 44, .32);
            border-radius: 24px;
            background: linear-gradient(160deg, #3b1608 0%, #2a0f04 55%, #1d0a02 100%);
            box-shadow: 0 34px 80px rgba(15, 5, 1, .55), inset 0 1px 0 rgba(255, 255, 255, .07);
            text-align: center;
            transform: translateY(16px) scale(.96);
            transition: transform .34s cubic-bezier(.2, .8, .25, 1);
        }

        .visit-success.is-visible .visit-success__card {
            transform: translateY(0) scale(1);
        }

        .visit-success__card::before {
            content: "";
            position: absolute;
            top: -92px;
            left: 50%;
            width: 262px;
            height: 262px;
            transform: translateX(-50%);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(196, 155, 44, .3), transparent 68%);
            pointer-events: none;
        }

        .visit-success__icon {
            position: relative;
            z-index: 1;
            display: grid;
            place-items: center;
            width: 76px;
            height: 76px;
            margin: 0 auto 22px;
            border-radius: 50%;
            color: #fff;
            background: linear-gradient(140deg, #d8ae3d, #8a6512);
            box-shadow: 0 14px 30px rgba(196, 155, 44, .35), inset 0 2px 0 rgba(255, 255, 255, .32);
        }

        .visit-success__icon svg {
            width: 34px;
            height: 34px;
        }

        .visit-success__icon::after {
            content: "";
            position: absolute;
            inset: -9px;
            border: 1px solid rgba(196, 155, 44, .35);
            border-radius: 50%;
            animation: visit-success-ring 2.4s ease-out infinite;
        }

        .visit-success__check {
            stroke-dasharray: 26;
            stroke-dashoffset: 26;
        }

        .visit-success.is-visible .visit-success__check {
            animation: visit-success-draw .5s .16s ease-out forwards;
        }

        @keyframes visit-success-ring {
            0% { transform: scale(.92); opacity: .85; }
            70% { transform: scale(1.16); opacity: 0; }
            100% { transform: scale(1.16); opacity: 0; }
        }

        @keyframes visit-success-draw {
            to { stroke-dashoffset: 0; }
        }

        .visit-success__eyebrow {
            position: relative;
            z-index: 1;
            display: inline-block;
            margin-bottom: 12px;
            padding: 5px 13px;
            border: 1px solid rgba(196, 155, 44, .3);
            border-radius: 999px;
            color: #e2c477;
            font-size: 9.5px;
            font-weight: 800;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .visit-success h2 {
            position: relative;
            z-index: 1;
            margin: 0 0 12px;
            color: #fff;
            font-size: 23px;
            font-weight: 800;
            line-height: 1.25;
        }

        .visit-success h2 em {
            font-style: normal;
            background: linear-gradient(100deg, #f2d98b, #c49b2c);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .visit-success__text {
            position: relative;
            z-index: 1;
            margin: 0 0 26px;
            color: rgba(255, 255, 255, .72);
            font-size: 13px;
            line-height: 1.7;
        }

        .visit-success__ok {
            position: relative;
            z-index: 1;
            width: 100%;
            padding: 15px;
            border: 0;
            border-radius: 13px;
            font: inherit;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: .02em;
            cursor: pointer;
            color: #2a0f04;
            background: linear-gradient(135deg, #e0bd52, #c49b2c 55%, #8a6512);
            box-shadow: 0 14px 28px rgba(196, 155, 44, .3);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .visit-success__ok:hover,
        .visit-success__ok:focus-visible {
            transform: translateY(-2px);
            box-shadow: 0 18px 34px rgba(196, 155, 44, .38);
        }

        .visit-success__ok:active {
            transform: translateY(0);
        }

        @media (prefers-reduced-motion: reduce) {
            .visit-success__card,
            .visit-success__icon::after,
            .visit-success__check {
                animation: none;
                transition: none;
                stroke-dashoffset: 0;
            }
        }

        @media (max-width: 750px) {
            .visit-feedback { top: 16px; right: 16px; }
            .visit-confirm__card { padding: 25px 20px; }
            .visit-success__card { padding: 30px 22px 26px; }
            .visit-success h2 { font-size: 20px; }
            .visit-success__icon { width: 66px; height: 66px; margin-bottom: 18px; }
            .visit-success__icon svg { width: 30px; height: 30px; }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Inter, Arial, sans-serif;
            background: #dfe4eb;
            color: #172b4d;
        }

        button,
        input,
        textarea,
        select {
            font-family: inherit;
        }

        .app {
            min-height: 100vh;
            display: flex;
        }

        /* ================= SIDEBAR ================= */

        .sidebar {
            width: 255px;
            min-height: 100vh;
            background: linear-gradient(180deg, #4a1e0e, #2a0f04);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            padding: 25px 15px;
            z-index: 10;
            transition: transform .25s ease;
        }

        .menu-toggle {
            display: none;
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 10px;
            background: #c49b2c;
            color: white;
            font-size: 23px;
            line-height: 1;
            cursor: pointer;
            flex-shrink: 0;
        }

        .sidebar-overlay {
            display: none;
        }

        .brand {
            padding: 8px 15px 28px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 52px;
            height: 54px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .kpu-logo {
            width: 52px;
            height: 52px;
            display: block;
            filter: drop-shadow(0 5px 6px rgba(0,0,0,.2));
        }

        .brand-text strong {
            display: block;
            font-size: 18px;
            letter-spacing: .5px;
        }

        .brand-text span {
            font-size: 11px;
            opacity: .65;
        }

        .menu-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            opacity: .45;
            padding: 10px 15px;
            margin-top: 10px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 12px;
            color: rgba(255,255,255,.75);
            text-decoration: none;
            margin-bottom: 6px;
            transition: .2s;
        }

        .menu-item:hover {
            background: rgba(255,255,255,.08);
            color: white;
        }

        .menu-item.active {
            background: linear-gradient(135deg, #c49b2c, #8a6512);
            color: white;
            box-shadow: 0 10px 25px rgba(196, 155, 44, .25);
        }

        .menu-icon {
            width: 22px;
            text-align: center;
            font-size: 17px;
        }
        .menu-icon svg {
            display: block;
            width: 18px;
            height: 18px;
            margin: 0 auto;
        }

        .sidebar-bottom {
            position: absolute;
            left: 20px;
            right: 20px;
            bottom: 30px;
            text-align: center;
            opacity: .6;
            font-size: 11px;
            line-height: 1.6;
        }

        /* ================= MAIN ================= */

        .main {
            margin-left: 255px;
            width: calc(100% - 255px);
        }

        /* ================= TOPBAR ================= */

        .topbar {
            height: 78px;
            background: linear-gradient(108deg, #ffffff 0%, #ffffff 61%, #c49b2c 61%, #8a6512 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 35px;
            border-bottom: 3px solid #c49b2c;
        }

        .topbar-sticky {
            position: sticky;
            top: 0;
            z-index: 8;
        }

        .topbar-sticky.is-scrolled {
            box-shadow: 0 6px 18px rgba(7, 26, 54, .14);
        }

        .guest-login-overlay {
            position: fixed;
            inset: 0;
            z-index: 30;
            display: none;
            background: rgba(7, 26, 54, .48);
            backdrop-filter: blur(7px);
            -webkit-backdrop-filter: blur(7px);
        }

        .guest-login-overlay.open {
            display: block;
        }

        .guest-login-frame {
            width: 100%;
            height: 100%;
            border: 0;
        }

        body.login-open {
            overflow: hidden;
        }

        .top-brand {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }

        .top-brand__mark {
            position: relative;
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            border-radius: 13px;
            color: #f6e6b8;
            background: linear-gradient(140deg, #4a1e0e, #2a0f04);
            box-shadow: 0 8px 18px rgba(42, 15, 4, .3), inset 0 1px 0 rgba(255, 255, 255, .12);
        }

        .top-brand__mark::after {
            content: "";
            position: absolute;
            inset: 0;
            border: 1px solid rgba(196, 155, 44, .45);
            border-radius: inherit;
            pointer-events: none;
        }

        .top-brand__mark svg {
            width: 22px;
            height: 22px;
        }

        .top-brand__text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .top-brand__eyebrow {
            font-size: 9.5px;
            font-weight: 800;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: #8a6512;
        }

        .top-brand__name {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.01em;
            line-height: 1.15;
            white-space: nowrap;
            background: linear-gradient(100deg, #2a0f04 0%, #4a1e0e 45%, #8a6512 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* ================= WARNA AKSEN KPU ================= */

        .eyebrow,
        .modal-kicker {
            color: #8a6512;
        }

        .card-header .header-icon {
            color: #8a6512;
            background: #faf1da;
        }

        .required {
            color: #c49b2c;
        }

        .input-wrap input:focus,
        .input-wrap select:focus,
        .input-wrap textarea:focus {
            border-color: #c49b2c;
            background: white;
            box-shadow: 0 0 0 3px rgba(196,155,44,.12);
        }

        .signature-footer .clear-signature {
            color: #8a6512;
        }

        .value-icon {
            color: #8a6512;
        }

        /* ================= WIDGET HARI / TANGGAL / WAKTU ================= */

        .top-right {
            position: relative;
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 9px 18px;
            overflow: hidden;
            border: 1px solid rgba(196, 155, 44, .38);
            border-radius: 14px;
            color: white;
            background: linear-gradient(135deg, #2a0f04 0%, #4a1e0e 55%, #5f2811 100%);
            box-shadow: 0 12px 26px rgba(42, 15, 4, .32), inset 0 1px 0 rgba(255, 255, 255, .08);
        }

        .top-right::after {
            content: "";
            position: absolute;
            top: -46px;
            right: -26px;
            width: 112px;
            height: 112px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(196, 155, 44, .3), transparent 70%);
            pointer-events: none;
        }

        .top-right__live {
            position: relative;
            z-index: 1;
            width: 8px;
            height: 8px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #e2b93b;
            animation: top-right-pulse 1.9s ease-out infinite;
        }

        @keyframes top-right-pulse {
            0% { box-shadow: 0 0 0 0 rgba(226, 185, 59, .55); }
            70% { box-shadow: 0 0 0 8px rgba(226, 185, 59, 0); }
            100% { box-shadow: 0 0 0 0 rgba(226, 185, 59, 0); }
        }

        .top-right__group {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            line-height: 1.2;
        }

        .top-right__label {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .48);
        }

        .top-right__value {
            display: flex;
            align-items: baseline;
            gap: 3px;
            white-space: nowrap;
        }

        .top-right__value strong {
            font-size: 13px;
            font-weight: 800;
            color: #f6e6b8;
        }

        .top-right__value small {
            font-size: 9px;
            font-weight: 700;
            color: rgba(255, 255, 255, .55);
        }

        .top-right__divider {
            position: relative;
            z-index: 1;
            width: 1px;
            align-self: stretch;
            background: linear-gradient(180deg, transparent, rgba(196, 155, 44, .5), transparent);
        }

        @media (prefers-reduced-motion: reduce) {
            .top-right__live {
                animation: none;
            }
        }

        .login-link {
            color: #8a6512;
            font-weight: 700;
            text-decoration: none;
        }

        .month-count {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 18px;
            padding: 8px 14px;
            border-radius: 50px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            font-size: 11px;
        }

        /* ================= HERO ================= */

        .hero {
            margin: 24px 30px 0;
            min-height: 225px;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            background:
                linear-gradient(90deg,
                    rgba(42, 15, 4, .96),
                    rgba(42, 15, 4, .72),
                    rgba(42, 15, 4, .25)),
                linear-gradient(135deg, #4a1e0e, #6b2f16);
            color: white;
            padding: 38px 40px;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            right: -100px;
            top: -120px;
            border: 50px solid rgba(255, 209, 130, .07);
        }

        .hero-small {
            font-size: 13px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            opacity: .75;
            margin-bottom: 7px;
        }

        .welcome-marquee {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 112px;
            align-items: center;
            width: min(100%, 880px);
            min-height: 82px;
            margin: 0 0 22px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.32);
            border-radius: 20px;
            background: linear-gradient(105deg, rgba(255,255,255,.2), rgba(255,255,255,.07) 68%, rgba(196,155,44,.2));
            box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 14px 28px rgba(0,0,0,.16);
        }

        .welcome-marquee::after {
            content: '';
            position: absolute;
            top: -70px;
            right: 70px;
            width: 180px;
            height: 180px;
            border: 22px solid rgba(255,255,255,.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .welcome-marquee__window {
            position: relative;
            z-index: 1;
            min-width: 0;
            padding: 17px 18px 17px 24px;
            overflow: hidden;
        }

        .welcome-marquee__window::before {
            content: '';
            position: absolute;
            inset: 0 auto 0 0;
            width: 4px;
            border-radius: 0 8px 8px 0;
            background: #c49b2c;
            box-shadow: 0 0 16px rgba(196, 155, 44, .75);
        }

        .welcome-marquee__track {
            display: inline-block;
            padding-left: 100%;
            color: #fff;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: .45px;
            line-height: 1.45;
            text-shadow: 0 2px 10px rgba(0,0,0,.2);
            white-space: nowrap;
            animation: welcome-marquee-scroll 18s linear infinite;
        }

        .welcome-marquee__mascot {
            position: relative;
            z-index: 2;
            align-self: end;
            width: 104px;
            height: 104px;
            margin: 0 8px 0 0;
            filter: drop-shadow(0 10px 8px rgba(0,0,0,.22));
            animation: welcome-mascot-float 3s ease-in-out infinite;
        }

        .welcome-marquee__mascot .mascot-hand {
            transform-box: fill-box;
            transform-origin: 82% 76%;
            animation: welcome-mascot-wave 1.2s ease-in-out infinite alternate;
        }

        .hero-title {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 18px;
            width: fit-content;
            max-width: 100%;
        }

        .hero-title h1 {
            margin: 0;
        }

        .hero-mascot {
            flex: 0 0 auto;
            width: 116px;
            height: 116px;
            filter: drop-shadow(0 10px 8px rgba(0,0,0,.22));
            animation: welcome-mascot-float 3s ease-in-out infinite;
        }

        .hero-mascot .mascot-hand {
            transform-box: fill-box;
            transform-origin: 82% 76%;
            animation: welcome-mascot-wave 1.2s ease-in-out infinite alternate;
        }

        @keyframes welcome-marquee-scroll {
            from { transform: translateX(0); }
            to { transform: translateX(-100%); }
        }

        @keyframes welcome-mascot-float {
            0%, 100% { transform: translateY(2px); }
            50% { transform: translateY(-4px); }
        }

        @keyframes welcome-mascot-wave {
            from { transform: rotate(-10deg); }
            to { transform: rotate(15deg); }
        }

        @media (max-width: 750px) {
            .welcome-marquee {
                grid-template-columns: minmax(0, 1fr) 78px;
                min-height: 72px;
                border-radius: 16px;
            }

            .welcome-marquee__window {
                padding: 14px 12px 14px 20px;
            }

            .welcome-marquee__track {
                font-size: 12px;
            }

            .welcome-marquee__mascot {
                width: 76px;
                height: 76px;
                margin-right: 2px;
            }

            .hero-mascot {
                width: 96px;
                height: 96px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .welcome-marquee__track,
            .welcome-marquee__mascot,
            .welcome-marquee__mascot .mascot-hand,
            .hero-mascot,
            .hero-mascot .mascot-hand {
                padding-left: 0;
                animation: none;
            }
        }

        .hero h1 {
            font-size: 34px;
            margin-bottom: 10px;
            font-weight: 800;
        }

        .hero p {
            max-width: 650px;
            font-size: 14px;
            line-height: 1.7;
            color: rgba(255,255,255,.85);
        }

        .hero-badge {
            display: inline-flex;
            margin-top: 18px;
            padding: 8px 14px;
            border-radius: 50px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            font-size: 11px;
        }
        .hero-badge svg { width: 13px; height: 13px; margin-right: 5px; }

        /* ================= CONTENT ================= */

        .content {
            padding: 25px 30px 45px;
        }

        .grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 330px;
            gap: 22px;
        }

        .home-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .card {
            background: white;
            border-radius: 18px;
            border: 1.5px solid #8a6512;
            box-shadow: 0 8px 30px rgba(25, 45, 75, .06);
        }

        .scroll-reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .65s ease, transform .65s cubic-bezier(.2, .75, .25, 1);
        }

        .scroll-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .scroll-reveal:nth-child(2) { transition-delay: .08s; }
        .scroll-reveal:nth-child(3) { transition-delay: .16s; }
        .scroll-reveal:nth-child(4) { transition-delay: .24s; }

        @media (prefers-reduced-motion: reduce) {
            .scroll-reveal,
            .scroll-reveal.is-visible {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }

        .form-card {
            padding: 28px;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 22px;
            border-bottom: 1px solid #edf1f5;
            margin-bottom: 22px;
        }

        .header-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: #edf4fb;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #173d67;
            font-size: 20px;
        }

        .card-header h2 {
            font-size: 18px;
            margin-bottom: 3px;
        }

        .card-header p {
            font-size: 12px;
            color: #8190a5;
        }

        /* ================= FORM ================= */

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 19px 22px;
        }

        .field {
            position: relative;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .field label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #344563;
        }

        .field-hint {
            display: block;
            margin-top: 7px;
            color: #8190a5;
            font-size: 11px;
            line-height: 1.5;
        }

        .guest-photo-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .guest-photo-button {
            min-height: 42px;
            padding: 10px 16px;
            border: 1px solid #315f8d;
            border-radius: 10px;
            color: white;
            background: #315f8d;
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .guest-photo-button--secondary {
            color: #315f8d;
            background: white;
        }

        .guest-camera {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: grid;
            place-items: center;
            padding: 20px;
            background: rgba(7, 26, 54, .72);
        }

        .guest-camera[hidden] {
            display: none;
        }

        .guest-camera__card {
            width: min(640px, 100%);
            padding: 26px;
            border: 1px solid rgba(255, 255, 255, .72);
            border-radius: 20px;
            background: linear-gradient(145deg, #fff, #f7f9fc);
            box-shadow: 0 28px 80px rgba(7, 26, 54, .36), 0 4px 14px rgba(7, 26, 54, .12);
        }

        .guest-camera__card h2 {
            margin: 0 0 18px;
            color: #263954;
            font-size: 19px;
            letter-spacing: -.25px;
        }

        .guest-camera__card video {
            display: block;
            width: 100%;
            max-height: 62vh;
            border: 1px solid #d9e2ec;
            border-radius: 14px;
            background: #111827;
            object-fit: contain;
            box-shadow: 0 8px 20px rgba(18, 42, 69, .12);
        }

        .guest-camera__card .guest-photo-actions {
            justify-content: center;
            gap: 12px;
            margin-top: 20px;
        }

        .guest-camera__card .guest-photo-button {
            min-height: 46px;
            padding: 11px 18px;
            border-radius: 12px;
            box-shadow: 0 5px 12px rgba(49, 95, 141, .12);
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .guest-camera__card .guest-photo-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 17px rgba(49, 95, 141, .2);
        }

        .guest-camera__card .guest-photo-button:focus-visible {
            outline: 3px solid rgba(49, 95, 141, .3);
            outline-offset: 3px;
        }

        .guest-camera__card .guest-photo-button--secondary {
            border-color: #d6e1ed;
            color: #315f8d;
            background: #fff;
        }

        @media (max-width: 520px) {
            .guest-camera { padding: 14px; }
            .guest-camera__card { padding: 19px; }
            .guest-camera__card .guest-photo-actions { justify-content: stretch; gap: 9px; margin-top: 16px; }
            .guest-camera__card .guest-photo-button { flex: 1 1 100%; }
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #8494a9;
            font-size: 14px;
        }
        .input-icon svg {
            display: block;
            width: 15px;
            height: 15px;
        }

        .input-wrap input,
        .input-wrap select,
        .input-wrap textarea {
            width: 100%;
            border: 1px solid #dce4ed;
            border-radius: 10px;
            background: #fbfcfe;
            padding: 12px 14px 12px 40px;
            outline: none;
            font-size: 13px;
            color: #263954;
            transition: .2s;
        }

        .input-wrap textarea {
            min-height: 100px;
            resize: vertical;
            padding-top: 13px;
        }

        .input-wrap input:focus,
        .input-wrap select:focus,
        .input-wrap textarea:focus {
            border-color: #315f8d;
            background: white;
            box-shadow: 0 0 0 3px rgba(49,95,141,.08);
        }

        .input-wrap textarea + .input-icon {
            top: 18px;
            transform: none;
        }

        .required {
            color: #bd1029;
        }

        /* ================= DATE TIME ================= */

        .readonly-info {
            background: #f5f8fb;
            border: 1px solid #e2e8ef;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            color: #4d6078;
        }

        /* ================= SIGNATURE ================= */

        .signature-box {
            border: 1.5px dashed #cbd7e4;
            border-radius: 12px;
            background: #fafcfe;
            overflow: hidden;
        }

        #signaturePad {
            width: 100%;
            height: 145px;
            display: block;
            cursor: crosshair;
            touch-action: none;
        }

        .signature-footer {
            border-top: 1px solid #e7edf3;
            padding: 8px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .signature-footer span {
            font-size: 11px;
            color: #8492a5;
        }

        .clear-signature {
            border: 0;
            background: transparent;
            color: #a20a20;
            cursor: pointer;
            font-size: 11px;
            font-weight: 700;
        }

        /* ================= BUTTON ================= */

        .submit-btn {
            width: 100%;
            border: none;
            border-radius: 11px;
            padding: 14px;
            margin-top: 24px;
            background: linear-gradient(135deg, #c49b2c, #8a6512);
            color: white;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(196,155,44,.2);
            transition: .2s;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 22px rgba(196,155,44,.25);
        }

        .scroll-controls {
            position: fixed;
            right: 24px;
            bottom: 24px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 20;
        }

        .scroll-btn {
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 10px;
            background: #c49b2c;
            color: white;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(196,155,44,.2);
        }

        .scroll-btn:hover {
            background: #8a6512;
        }

        /* ================= RIGHT PANEL ================= */

        .right-column {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .info-card {
            padding: 22px;
        }

        .general-info-card {
            color: white;
            background: linear-gradient(135deg, #4a1e0e, #6b2f16);
            border: 0;
            box-shadow: 0 12px 30px rgba(74, 30, 14, .2);
        }

        .general-info-card .info-title {
            color: white;
        }

        .general-info-card .info-icon {
            color: white;
            background: rgba(255,255,255,.13);
        }

        .general-info-card .info-row strong {
            color: white;
        }

        .general-info-card .info-row span {
            color: rgba(255,255,255,.7);
        }

        .org-chart {
            margin: 0 0 22px;
            padding: 16px 12px;
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 14px;
            background: rgba(255,255,255,.07);
        }

        .org-node {
            width: 100%;
            padding: 9px 10px;
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 9px;
            background: rgba(255,255,255,.13);
            color: white;
            font-size: 10px;
            font-weight: 700;
            line-height: 1.35;
            text-align: center;
        }

        .org-connector {
            width: 2px;
            height: 12px;
            margin: 0 auto;
            background: rgba(255,255,255,.55);
        }

        .org-branch {
            position: relative;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            padding-top: 12px;
        }

        .org-branch::before {
            content: "";
            position: absolute;
            top: 0;
            left: 25%;
            right: 25%;
            height: 1px;
            background: rgba(255,255,255,.55);
        }

        .mini-banner {
            background: linear-gradient(135deg, #0b315b, #194e7e);
            border-radius: 14px;
            color: white;
            padding: 24px;
            min-height: 165px;
            position: relative;
            overflow: hidden;
        }

        .mini-banner::after {
            content: "KPU";
            position: absolute;
            right: -10px;
            bottom: -32px;
            font-size: 100px;
            font-weight: 900;
            opacity: .06;
        }

        .mini-banner h3 {
            font-size: 23px;
            line-height: 1.1;
            margin-bottom: 12px;
        }

        .mini-banner p {
            font-size: 11px;
            line-height: 1.6;
            max-width: 190px;
            opacity: .85;
        }

        .info-title {
            display: flex;
            gap: 10px;
            align-items: center;
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .info-row {
            display: flex;
            gap: 13px;
            margin-bottom: 17px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-icon {
            width: 35px;
            height: 35px;
            border-radius: 10px;
            background: #f0f5fa;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #315c85;
            flex-shrink: 0;
        }
        .info-icon svg,
        .info-title svg {
            display: block;
            width: 18px;
            height: 18px;
        }

        .info-row strong {
            display: block;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .info-row span {
            display: block;
            font-size: 11px;
            line-height: 1.5;
            color: #738197;
        }

        .quote-card {
            background: linear-gradient(135deg, #f5f8fc, #eef3f8);
            padding: 22px;
            border: 1.5px solid #8a6512;
            border-radius: 16px;
            color: #596b82;
        }

        .quote-mark {
            font-size: 30px;
            font-weight: 900;
            color: #c49b2c;
            line-height: .7;
        }

        .quote-card p {
            font-size: 12px;
            line-height: 1.7;
            font-style: italic;
            margin: 10px 0 18px;
        }

        .contact-bar {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            background: linear-gradient(135deg, #4a1e0e, #6b2f16);
            padding: 19px;
            border: 0;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(7, 26, 54, .2);
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
            min-height: 56px;
            padding: 13px 14px;
            border-radius: 11px;
            color: rgba(255,255,255,.86);
            text-decoration: none;
            transition: .2s;
        }

        .contact-item:hover {
            background: rgba(255,255,255,.1);
            color: white;
        }

        .contact-icon {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            background: rgba(255,255,255,.13);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .contact-icon svg {
            width: 21px;
            height: 21px;
            display: block;
        }

        .contact-item span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .contact-item strong {
            display: block;
            font-size: 11px;
            margin-bottom: 2px;
            color: white;
        }

        .contact-item small {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: rgba(255,255,255,.62);
            font-size: 10px;
        }

        .values {
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 7px;
        }

        .value {
            text-align: center;
            font-size: 9px;
            color: #68798e;
        }

        .value-icon {
            margin: auto auto 6px;
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #315c85;
        }
        .value-icon svg { width: 18px; height: 18px; }

        /* ================= RESPONSIVE ================= */

        @media(max-width: 1050px) {
            .sidebar {
                width: 220px;
                transform: translateX(-100%);
                box-shadow: 12px 0 30px rgba(0,0,0,.22);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            .menu-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .header-icon svg {
                width: 23px;
                height: 23px;
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                z-index: 9;
                background: rgba(7,26,54,.52);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .right-column {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .contact-bar {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
                padding: 13px;
            }

            .contact-item {
                min-height: 48px;
                padding: 9px 10px;
                gap: 7px;
            }

            .contact-icon {
                width: 32px;
                height: 32px;
            }

            .contact-icon svg {
                width: 18px;
                height: 18px;
            }

            .contact-item strong {
                font-size: 10px;
            }

            .contact-item small {
                font-size: 9px;
            }
        }

        @media(max-width: 750px) {
            .main {
                margin-left: 0;
                width: 100%;
            }

            .topbar {
                min-height: 78px;
                height: auto;
                gap: 12px;
                padding: 12px 18px;
            }

            .top-brand {
                gap: 10px;
            }

            .top-brand__mark {
                width: 36px;
                height: 36px;
                border-radius: 11px;
            }

            .top-brand__mark svg {
                width: 18px;
                height: 18px;
            }

            .top-brand__eyebrow {
                font-size: 8px;
                letter-spacing: .18em;
            }

            .top-brand__name {
                font-size: 14px;
            }

            .top-right {
                flex-direction: column;
                align-items: flex-start;
                gap: 0;
                padding: 6px 10px;
                border-radius: 11px;
                line-height: 1.25;
                flex-shrink: 0;
            }

            .top-right__live,
            .top-right__label,
            .top-right__divider {
                display: none;
            }

            .top-right__value strong {
                font-size: 10px;
            }

            .top-right__value small {
                font-size: 8px;
            }

            .hero {
                margin: 15px;
                padding: 28px 22px;
            }

            .hero h1 {
                font-size: 27px;
            }

            .content {
                padding: 15px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .field.full {
                grid-column: auto;
            }

            .right-column {
                display: flex;
            }

            .contact-bar {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 6px;
                padding: 10px;
            }

            .contact-item {
                min-height: 43px;
                padding: 7px 8px;
                gap: 6px;
            }

            .contact-icon {
                width: 28px;
                height: 28px;
                border-radius: 8px;
            }

            .contact-icon svg {
                width: 16px;
                height: 16px;
            }

            .contact-item strong {
                font-size: 9px;
            }

            .contact-item small {
                font-size: 8px;
            }

            .form-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<div class="app">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="brand">
            <div class="brand-logo">
                <img class="kpu-logo" src="https://www.kpu.go.id/img/logo-kpu.png" alt="Logo KPU">
            </div>

            <div class="brand-text">
                <strong>KPU</strong>
                <span>KOTA TERNATE</span>
            </div>
        </div>

        <div class="menu-title">Menu Utama</div>

        <a href="{{ route('home') }}" class="menu-item{{ $isBeranda ? ' active' : '' }}" data-nav="beranda">
            <span class="menu-icon" aria-hidden="true">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 10 9-7 9 7M5 9v11h14V9M9 20v-6h6v6"/></svg>
            </span>
            <span>Beranda</span>
        </a>

        <a href="{{ route('tamu.index') }}" class="menu-item{{ !$isBeranda ? ' active' : '' }}" data-nav="buku-tamu">
            <span class="menu-icon" aria-hidden="true">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 4.75A2.75 2.75 0 0 1 8.75 2H20v17H8.75A2.75 2.75 0 0 0 6 21.75v-17Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18.5A2.5 2.5 0 0 0 8.5 21H20M10 7h6m-6 4h6"/></svg>
            </span>
            <span>Buku Tamu</span>
        </a>

        <a href="{{ route('home') }}#kontak-kpu" class="menu-item" data-nav="kontak">
            <span class="menu-icon" aria-hidden="true">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 16.5v3a1.5 1.5 0 0 1-1.64 1.5A17.85 17.85 0 0 1 3 5.14 1.5 1.5 0 0 1 4.5 3.5h3a1.5 1.5 0 0 1 1.5 1.29l.45 3.15a1.5 1.5 0 0 1-.43 1.28l-1.3 1.3a14.4 14.4 0 0 0 5.76 5.76l1.3-1.3a1.5 1.5 0 0 1 1.28-.43l3.15.45A1.5 1.5 0 0 1 21 16.5Z"/></svg>
            </span>
            <span>Kontak</span>
        </a>

        <a href="{{ route('login') }}" class="menu-item" id="openLogin">
            <span class="menu-icon" aria-hidden="true">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-3m-5-4h12m0 0-4-4m4 4-4 4"/></svg>
            </span>
            <span>Login</span>
        </a>

        <div class="sidebar-bottom">
            <strong>Komisi Pemilihan Umum</strong><br>
            Kota Ternate
            <br><br>
            <em>"Melayani dengan Profesional,<br>
            Transparan dan Berintegritas"</em>
        </div>

    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>


    <!-- MAIN -->
    <main class="main">

        <!-- TOPBAR -->
        <header class="topbar topbar-sticky">

            <button class="menu-toggle" id="menuToggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false">
                <svg aria-hidden="true" width="21" height="21" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <div class="top-brand">
                <span class="top-brand__mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 4.5h6a1 1 0 0 1 1 1V7H8V5.5a1 1 0 0 1 1-1z"/>
                        <path d="M8 6.5H6.5A1.5 1.5 0 0 0 5 8v10.5A1.5 1.5 0 0 0 6.5 20h11a1.5 1.5 0 0 0 1.5-1.5V8a1.5 1.5 0 0 0-1.5-1.5H16"/>
                        <path d="M9 12h6M9 15.5h4"/>
                    </svg>
                </span>

                <span class="top-brand__text">
                    <span class="top-brand__eyebrow">Sistem Informasi</span>
                    <span class="top-brand__name">Pelayanan Tamu</span>
                </span>
            </div>

            <div class="top-right" role="group" aria-label="Informasi hari, tanggal, dan waktu saat ini">

                <span class="top-right__live" aria-hidden="true"></span>

                <span class="top-right__group">
                    <span class="top-right__label">Hari / Tanggal</span>
                    <span class="top-right__value"><strong id="currentDate">-</strong></span>
                </span>

                <span class="top-right__divider" aria-hidden="true"></span>

                <span class="top-right__group">
                    <span class="top-right__label">Waktu</span>
                    <span class="top-right__value"><strong id="currentTime">-</strong><small>WIT</small></span>
                </span>

            </div>

        </header>


        <!-- HERO -->
        <section class="hero">

            <div class="hero-small">
                Komisi Pemilihan Umum
            </div>

            @if($isBeranda)
                <div class="welcome-marquee" role="status" aria-label="Ucapan selamat datang">
                    <div class="welcome-marquee__window">
                        <span class="welcome-marquee__track">Selamat datang di layanan digital Komisi Pemilihan Umum Kota Ternate &nbsp;•&nbsp; Melayani dengan profesional, transparan, dan berintegritas</span>
                    </div>
                    <img class="welcome-marquee__mascot" src="{{ asset('boneka-kpu.png') }}?v=20261014" alt="Maskot KPU Kota Ternate">
                </div>
                <h1>Buku Tamu Digital</h1>
            @else
            <div class="hero-title">
                <img class="hero-mascot" src="{{ asset('boneka-kpu.png') }}?v=20261014" alt="Boneka maskot KPU Kota Ternate">
                <h1>Buku Tamu Digital</h1>
            </div>
            @endif

            @if($isBeranda)
                <p>
                    Mengenal Komisi Pemilihan Umum Kota Ternate dan pelayanan publik yang kami berikan.
                </p>

                <div class="hero-badge">
                    <svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 3 2.3 6.7L21 12l-6.7 2.3L12 21l-2.3-6.7L3 12l6.7-2.3L12 3Z"/></svg>
                    KPU Kota Ternate
                </div>
            @else
                <p>
                    Silakan lengkapi data kunjungan Anda dengan benar dan valid.
                    Kami sangat menghargai setiap kunjungan Anda di KPU Kota Ternate.
                </p>

                <div class="hero-badge">
                    <svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 3 2.3 6.7L21 12l-6.7 2.3L12 21l-2.3-6.7L3 12l6.7-2.3L12 3Z"/></svg>
                    Pelayanan Tamu Digital
                </div>

                <div class="month-count">
                    <strong>{{ $totalBulanIni }}</strong> total pengunjung bulan ini
                </div>
            @endif

        </section>


        <!-- CONTENT -->
        <section class="content">
            @if(session('success'))

    <div class="success-alert">

        <div class="success-icon">
            <svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m5 12 4 4L19 6"/></svg>
        </div>

        <div>
            <strong>Berhasil!</strong>

            <p>
                {{ session('success') }}
            </p>
        </div>

    </div>

@endif

            @if($isBeranda)
                <div class="grid home-grid">

                    <div id="kontak-kpu" class="card info-card">

                        <div class="info-title">
                            <span aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 16.5v3a1.5 1.5 0 0 1-1.64 1.5A17.85 17.85 0 0 1 3 5.14 1.5 1.5 0 0 1 4.5 3.5h3a1.5 1.5 0 0 1 1.5 1.29l.45 3.15a1.5 1.5 0 0 1-.43 1.28l-1.3 1.3a14.4 14.4 0 0 0 5.76 5.76l1.3-1.3a1.5 1.5 0 0 1 1.28-.43l3.15.45A1.5 1.5 0 0 1 21 16.5Z"/></svg></span>
                            Kontak KPU Kota Ternate
                        </div>

                        <div class="info-row">
                            <div class="info-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5" stroke-width="1.8"/></svg></div>
                            <div>
                                <strong>Alamat</strong>
                                <span>
                                    KPU Kota Ternate<br>
                                    Maluku Utara
                                </span>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 7v5l3 2"/></svg></div>
                            <div>
                                <strong>Jam Pelayanan</strong>
                                <span>
                                    Senin - Jumat<br>
                                    08.00 - 16.00 WIT
                                </span>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 16.5v3a1.5 1.5 0 0 1-1.64 1.5A17.85 17.85 0 0 1 3 5.14 1.5 1.5 0 0 1 4.5 3.5h3a1.5 1.5 0 0 1 1.5 1.29l.45 3.15a1.5 1.5 0 0 1-.43 1.28l-1.3 1.3a14.4 14.4 0 0 0 5.76 5.76l1.3-1.3a1.5 1.5 0 0 1 1.28-.43l3.15.45A1.5 1.5 0 0 1 21 16.5Z"/></svg></div>
                            <div>
                                <strong>Informasi</strong>
                                <span>
                                    Silakan hubungi kantor KPU Kota Ternate untuk informasi lebih lanjut.
                                </span>
                            </div>
                        </div>

                    </div>

                    <div class="quote-card">
                        <div class="quote-mark">“</div>
                        <p>
                            Melayani dengan profesional, transparan, dan berintegritas untuk masyarakat Kota Ternate.
                        </p>
                    </div>

                    <div class="contact-bar" aria-label="Kontak dan profil KPU Kota Ternate">

                        <a class="contact-item" href="https://www.instagram.com/kpu.ternate?fbclid=IwY2xjawUvYpVleHRuA2FlbQIxMABwZG9mBWJyaWQRMVhiSk5hQkZ5STFSMEg3R1dzcnRjBmFwcF9pZBAyMjIwMzkxNzg4MjAwODkyAAEe9lKVz4_LF7O70RUyJraW68_i1vOCowxIESJsQrTRzRziC_gGi2l7qHp3CqI_aem_GxNAiVotSQsZ7qTBwGXg0A" target="_blank" rel="noopener noreferrer" title="Instagram KPU Kota Ternate">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="4" y="4" width="16" height="16" rx="4.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                                    <circle cx="12" cy="12" r="3.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                                    <circle cx="17.3" cy="6.8" r="1" fill="currentColor"/>
                                </svg>
                            </div>
                            <span>
                                <strong>Instagram</strong>
                                <small>KPU Kota Ternate</small>
                            </span>
                        </a>

                        <a class="contact-item" href="https://www.facebook.com/profile.php?id=100069048015333" target="_blank" rel="noopener noreferrer" title="Facebook KPU Kota Ternate">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M13.8 20v-7h2.4l.4-2.7h-2.8V8.6c0-.8.3-1.3 1.4-1.3h1.5V4.8c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8v1.8H8.2V13h2.6v7h3Z" fill="currentColor"/>
                                </svg>
                            </div>
                            <span>
                                <strong>Facebook</strong>
                                <small>KPU Kota Ternate</small>
                            </span>
                        </a>

                        <a class="contact-item" href="mailto:kpu.ternate17@gmail.com" title="Email KPU Kota Ternate">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M4 6.5h16v11H4z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                    <path d="m4.5 7 7.5 6 7.5-6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span>
                                <strong>Gmail</strong>
                                <small>kpu.ternate17@gmail.com</small>
                            </span>
                        </a>

                        <a class="contact-item" href="https://kota-ternate.kpu.go.id/" target="_blank" rel="noreferrer" title="Website resmi KPU Kota Ternate">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1.7"/>
                                    <path d="M4.5 12h15M12 4c2 2.1 3 4.8 3 8s-1 5.9-3 8c-2-2.1-3-4.8-3-8s1-5.9 3-8Z" fill="none" stroke="currentColor" stroke-width="1.5"/>
                                </svg>
                            </div>
                            <span>
                                <strong>Website</strong>
                                <small>Profil KPU Kota Ternate</small>
                            </span>
                        </a>

                    </div>

                </div>
            @else
            <div class="grid">

                <!-- FORM -->
                <div class="card form-card">

                    <div class="card-header">

                        <div class="header-icon">
                            <svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke-width="1.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 21a8 8 0 0 1 16 0"/></svg>
                        </div>

                        <div>
                            <h2>Formulir Buku Tamu</h2>

                            <p>
                                Isi data diri dan keperluan kunjungan Anda
                            </p>
                        </div>

                    </div>


                    <form id="guestBookForm" action="{{ route('tamu.store') }}" method="POST">

                        @csrf

                        <div class="form-grid">

                            <!-- TANGGAL -->
                            <div class="field">

                                <label>
                                    Hari / Tanggal Kunjungan
                                    <span class="required">*</span>
                                </label>

                                <div class="input-wrap">

                                    <span class="input-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2" stroke-width="1.8"/><path stroke-linecap="round" stroke-width="1.8" d="M16 3v4M8 3v4M3 10h18"/></svg></span>

                                    <input
                                        type="text"
                                        id="visitDate"
                                        name="hari_tanggal"
                                        readonly
                                    >

                                </div>

                            </div>


                            <!-- JAM -->
                            <div class="field">

                                <label>
                                    Jam Kunjungan
                                    <span class="required">*</span>
                                </label>

                                <div class="input-wrap">

                                    <span class="input-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 7v5l3 2"/></svg></span>

                                    <input
                                        type="text"
                                        id="visitTime"
                                        name="jam"
                                        readonly
                                    >

                                </div>

                            </div>


                            <!-- NAMA -->
                            <div class="field">

                                <label>
                                    Nama Lengkap
                                    <span class="required">*</span>
                                </label>

                                <div class="input-wrap">

                                    <span class="input-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke-width="1.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 21a8 8 0 0 1 16 0"/></svg></span>

                                    <input
                                        type="text"
                                        name="nama"
                                        placeholder="Masukkan nama lengkap"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- INSTANSI -->
                            <div class="field">

                                <label>
                                    Instansi
                                    <span class="required">*</span>
                                </label>

                                <div class="input-wrap">

                                    <span class="input-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5M9 9h.01M15 9h.01M9 12h.01M15 12h.01"/></svg></span>

                                    <input
                                        type="text"
                                        name="instansi"
                                        placeholder="Masukkan nama instansi / lembaga"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- ALAMAT -->
                            <div class="field">

                                <label>
                                    Alamat
                                    <span class="required">*</span>
                                </label>

                                <div class="input-wrap">

                                    <span class="input-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5" stroke-width="1.8"/></svg></span>

                                    <textarea
                                        name="alamat"
                                        placeholder="Masukkan alamat lengkap"
                                        required
                                    ></textarea>

                                </div>

                            </div>


                            <!-- KEPERLUAN -->
                            <div class="field">

                                <label>
                                    Keperluan
                                    <span class="required">*</span>
                                </label>

                                <div class="input-wrap">

                                    <span class="input-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 3v5h5M8 12h8M8 16h8"/></svg></span>

                                    <textarea
                                        name="keperluan"
                                        placeholder="Jelaskan keperluan kunjungan"
                                        required
                                    ></textarea>

                                </div>

                            </div>


                            <!-- TUJUAN -->
                            <div class="field">

                                <label>
                                    Tujuan
                                    <span class="required">*</span>
                                </label>

                                <div class="input-wrap">

                                    <span class="input-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><circle cx="12" cy="12" r="4" stroke-width="1.8"/><circle cx="12" cy="12" r="1" fill="currentColor" stroke="none"/></svg></span>

                                    <select id="visitPurpose" name="tujuan" required>

                                        <option value="">
                                            Pilih tujuan kunjungan
                                        </option>

                                        <option value="Ketua KPU">
                                            Ketua KPU
                                        </option>

                                        <option value="Sekretaris KPU">
                                            Sekretaris KPU
                                        </option>

                                        <option value="Kasubbag Keuangan">
                                            Kasubbag Keuangan
                                        </option>

                                        <option value="Kasubbag Teknis">
                                            Kasubbag Teknis
                                        </option>

                                        <option value="Kasubbag Hukum">
                                            Kasubbag Hukum
                                        </option>

                                        <option value="Kasubbag SDM">
                                            Kasubbag SDM
                                        </option>

                                        <option value="Lainnya">
                                            Lainnya
                                        </option>

                                    </select>

                                    <div class="input-wrap" id="otherPurposeWrap" hidden>
                                        <span class="input-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m16 4 4 4M4 20l4-.8L19 8a2.8 2.8 0 0 0-4-4L4 15l-.8 5Z"/></svg></span>
                                        <input
                                            type="text"
                                            id="otherPurpose"
                                            name="tujuan"
                                            placeholder="Tuliskan tujuan kunjungan lainnya"
                                            aria-label="Tuliskan tujuan kunjungan lainnya"
                                            disabled
                                        >
                                    </div>

                                </div>

                            </div>


                            <!-- HP -->
                            <div class="field">

                                <label>
                                    No. Handphone
                                    <span class="required">*</span>
                                </label>

                                <div class="input-wrap">

                                    <span class="input-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 16.5v3a1.5 1.5 0 0 1-1.64 1.5A17.85 17.85 0 0 1 3 5.14 1.5 1.5 0 0 1 4.5 3.5h3a1.5 1.5 0 0 1 1.5 1.29l.45 3.15a1.5 1.5 0 0 1-.43 1.28l-1.3 1.3a14.4 14.4 0 0 0 5.76 5.76l1.3-1.3a1.5 1.5 0 0 1 1.28-.43l3.15.45A1.5 1.5 0 0 1 21 16.5Z"/></svg></span>

                                    <input
                                        type="tel"
                                        name="no_handphone"
                                        placeholder="Contoh: 081234567890"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- FOTO -->
                            <div class="field full">

                                <label>
                                    Dokumentasi Foto
                                </label>

                                <div class="guest-photo-actions">
                                    <button class="guest-photo-button" id="openGuestCamera" type="button">Buka Kamera</button>
                                    <button class="guest-photo-button guest-photo-button--secondary" id="chooseGuestPhoto" type="button">Pilih File</button>
                                </div>

                                <input
                                    type="file"
                                    id="guestPhoto"
                                    name="dokumentasi"
                                    accept="image/png,image/jpeg"
                                    hidden
                                    aria-describedby="guestPhotoHint"
                                >
                                <input
                                    type="file"
                                    id="guestCameraPhoto"
                                    accept="image/png,image/jpeg"
                                    capture="environment"
                                    hidden
                                    tabindex="-1"
                                    aria-hidden="true"
                                >
                                <small class="field-hint" id="guestPhotoSelection" aria-live="polite">Belum ada foto dipilih.</small>

                                <div class="guest-camera" id="guestCameraDialog" role="dialog" aria-modal="true" aria-labelledby="guestCameraTitle" hidden>
                                    <div class="guest-camera__card">
                                        <h2 id="guestCameraTitle">Ambil Foto Dokumentasi</h2>
                                        <video id="guestCameraPreview" autoplay playsinline></video>
                                        <canvas id="guestCameraCanvas" hidden></canvas>
                                        <div class="guest-photo-actions">
                                            <button class="guest-photo-button guest-photo-button--secondary" id="switchGuestCamera" type="button" aria-label="Ganti ke kamera depan">
                                                <svg aria-hidden="true" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:5px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7v5h-5M4 17v-5h5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.5 9A7.5 7.5 0 0 1 18 6l2 2M4 16l2 2a7.5 7.5 0 0 0 12.5-3"/></svg>
                                                Balik Kamera
                                            </button>
                                            <button class="guest-photo-button" id="captureGuestPhoto" type="button">Ambil Foto</button>
                                            <button class="guest-photo-button guest-photo-button--secondary" id="closeGuestCamera" type="button">Batal</button>
                                        </div>
                                    </div>
                                </div>

                                <small class="field-hint" id="guestPhotoHint">
                                    Opsional. Buka kamera perangkat atau pilih foto dari file; foto akan disimpan bersama data kunjungan.
                                </small>

                            </div>


                            <!-- PARAF -->
                            <div class="field full">

                                <label>
                                    Paraf
                                    <span class="required">*</span>
                                </label>

                                <div class="signature-box">

                                    <canvas id="signaturePad"></canvas>

                                    <div class="signature-footer">

                                        <span>
                                            <svg aria-hidden="true" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:4px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m16 4 4 4M4 20l4-.8L19 8a2.8 2.8 0 0 0-4-4L4 15l-.8 5Z"/></svg>
                                            Buat paraf menggunakan mouse atau layar sentuh
                                        </span>

                                        <button
                                            type="button"
                                            class="clear-signature"
                                            id="clearSignature"
                                        >
                                            Hapus Paraf
                                        </button>

                                    </div>

                                </div>

                                <input
                                    type="hidden"
                                    name="paraf"
                                    id="signatureInput"
                                >

                            </div>

                        </div>


                        <button type="submit" class="submit-btn">
                            <svg aria-hidden="true" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:5px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m22 2-7 20-4-9-9-4 20-7ZM22 2 11 13"/></svg>
                            Simpan Kunjungan
                        </button>

                    </form>

                </div>


                <!-- RIGHT COLUMN -->
                <div class="right-column">

                    <!-- QUOTE -->
                    <div class="quote-card">

                        <div class="quote-mark">“</div>

                        <p>
                            Partisipasi Anda adalah bagian dari
                            pelayanan publik yang transparan,
                            profesional dan akuntabel.
                        </p>

                        <div class="values">

                            <div class="value">

                                <div class="value-icon">
                                    <svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6"/></svg>
                                </div>

                                Profesional

                            </div>

                            <div class="value">

                                <div class="value-icon">
                                    <svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3 21 12l-9 9-9-9 9-9Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 8 4 4-4 4-4-4 4-4Z"/></svg>
                                </div>

                                Transparan

                            </div>

                            <div class="value">

                                <div class="value-icon">
                                    <svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6"/></svg>
                                </div>

                                Akuntabel

                            </div>

                        </div>

                    </div>

                    <div class="contact-bar" aria-label="Kontak dan profil KPU Kota Ternate">

                        <a class="contact-item" href="https://www.facebook.com/profile.php?id=100069048015333" target="_blank" rel="noopener noreferrer" title="Facebook KPU Kota Ternate">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M13.8 20v-7h2.4l.4-2.7h-2.8V8.6c0-.8.3-1.3 1.4-1.3h1.5V4.8c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8v1.8H8.2V13h2.6v7h3Z" fill="currentColor"/>
                                </svg>
                            </div>
                            <span>
                                <strong>Facebook</strong>
                                <small>KPU Kota Ternate</small>
                            </span>
                        </a>

                        <a class="contact-item" href="https://www.instagram.com/kpu.ternate?fbclid=IwY2xjawUvYpVleHRuA2FlbQIxMABwZG9mBWJyaWQRMVhiSk5hQkZ5STFSMEg3R1dzcnRjBmFwcF9pZBAyMjIwMzkxNzg4MjAwODkyAAEe9lKVz4_LF7O70RUyJraW68_i1vOCowxIESJsQrTRzRziC_gGi2l7qHp3CqI_aem_GxNAiVotSQsZ7qTBwGXg0A" target="_blank" rel="noopener noreferrer" title="Instagram KPU Kota Ternate">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="4" y="4" width="16" height="16" rx="4.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                                    <circle cx="12" cy="12" r="3.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                                    <circle cx="17.3" cy="6.8" r="1" fill="currentColor"/>
                                </svg>
                            </div>
                            <span>
                                <strong>Instagram</strong>
                                <small>KPU Kota Ternate</small>
                            </span>
                        </a>

                        <a class="contact-item" href="mailto:kpu.ternate17@gmail.com" title="Email KPU Kota Ternate">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M4 6.5h16v11H4z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                    <path d="m4.5 7 7.5 6 7.5-6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span>
                                <strong>Gmail</strong>
                                <small>kpu.ternate17@gmail.com</small>
                            </span>
                        </a>

                        <a class="contact-item" href="https://kota-ternate.kpu.go.id/" target="_blank" rel="noreferrer" title="Website profil KPU Kota Ternate">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1.7"/>
                                    <path d="M4.5 12h15M12 4c2 2.1 3 4.8 3 8s-1 5.9-3 8c-2-2.1-3-4.8-3-8s1-5.9 3-8Z" fill="none" stroke="currentColor" stroke-width="1.5"/>
                                </svg>
                            </div>
                            <span>
                                <strong>Website</strong>
                                <small>Profil KPU Kota Ternate</small>
                            </span>
                        </a>

                    </div>

                </div>

            </div>
            @endif

        </section>

    </main>

</div>

<div class="guest-login-overlay" id="guestLoginOverlay" role="dialog" aria-modal="true" aria-label="Login KPU" hidden>
    <iframe class="guest-login-frame" id="guestLoginFrame" title="Form login KPU"></iframe>
</div>

<div class="visit-confirm" id="visitConfirm" role="dialog" aria-modal="true" aria-labelledby="visitConfirmTitle" aria-hidden="true">
    <div class="visit-confirm__card">
        <div class="visit-confirm__icon" aria-hidden="true"><svg width="27" height="27" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m22 2-7 20-4-9-9-4 20-7ZM22 2 11 13"/></svg></div>
        <h2 id="visitConfirmTitle">Simpan kunjungan?</h2>
        <p>Pastikan data kunjungan yang Anda isi sudah benar sebelum melanjutkan.</p>
        <div class="visit-confirm__actions">
            <button class="visit-confirm__button visit-confirm__button--cancel" id="cancelVisit" type="button">Batal</button>
            <button class="visit-confirm__button visit-confirm__button--continue" id="continueVisit" type="button">Lanjut</button>
        </div>
    </div>
</div>

<div class="visit-success" id="visitSuccess" role="dialog" aria-modal="true" aria-labelledby="visitSuccessTitle" aria-hidden="true">
    <div class="visit-success__card">

        <div class="visit-success__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path class="visit-success__check" d="M5 12.6l4.3 4.3L19 7" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <span class="visit-success__eyebrow">Buku Tamu Digital</span>

        <h2 id="visitSuccessTitle">Terima Kasih Telah <em>Berkunjung</em></h2>

        <p class="visit-success__text">
            Data anda telah tersimpan. Kami sangat menghargai kunjungan Anda di KPU Kota Ternate.
        </p>

        <button class="visit-success__ok" id="visitSuccessOk" type="button">Oke</button>

    </div>
</div>

<div class="visit-feedback" id="visitFeedback" role="status" aria-live="polite" aria-hidden="true">
    <div class="visit-feedback__icon" id="visitFeedbackIcon" aria-hidden="true"></div>
    <div><strong id="visitFeedbackTitle"></strong><span id="visitFeedbackMessage"></span></div>
</div>

<div class="scroll-controls" aria-label="Navigasi halaman">
    <button class="scroll-btn" type="button" title="Ke atas" aria-label="Ke atas" onclick="scrollToPosition('top')">↑</button>
    <button class="scroll-btn" type="button" title="Ke bawah" aria-label="Ke bawah" onclick="scrollToPosition('bottom')">↓</button>
</div>


<script>

    /* =========================
       JAM & TANGGAL
    ========================= */

    const guestBookForm = document.getElementById('guestBookForm');
    const visitConfirm = document.getElementById('visitConfirm');
    const cancelVisit = document.getElementById('cancelVisit');
    const continueVisit = document.getElementById('continueVisit');
    const visitFeedback = document.getElementById('visitFeedback');
    const visitFeedbackIcon = document.getElementById('visitFeedbackIcon');
    const visitFeedbackTitle = document.getElementById('visitFeedbackTitle');
    const visitFeedbackMessage = document.getElementById('visitFeedbackMessage');
    const visitSuccess = document.getElementById('visitSuccess');
    const visitSuccessOk = document.getElementById('visitSuccessOk');
    const visitPurpose = document.getElementById('visitPurpose');
    const otherPurposeWrap = document.getElementById('otherPurposeWrap');
    const otherPurpose = document.getElementById('otherPurpose');
    const guestPhoto = document.getElementById('guestPhoto');
    const guestCameraPhoto = document.getElementById('guestCameraPhoto');
    const guestPhotoSelection = document.getElementById('guestPhotoSelection');
    const guestCameraDialog = document.getElementById('guestCameraDialog');
    const guestCameraPreview = document.getElementById('guestCameraPreview');
    const guestCameraCanvas = document.getElementById('guestCameraCanvas');
    const signatureCanvas = document.getElementById('signaturePad');
    let feedbackTimer;
    let confirmedSubmit = false;
    let guestCameraStream;
    let guestCameraFacingMode = 'environment';

    function clearSignaturePad() {
        if (!signatureCanvas) {
            return;
        }

        const context = signatureCanvas.getContext('2d');
        context.save();
        context.setTransform(1, 0, 0, 1, 0, 0);
        context.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
        context.restore();

        document.getElementById('signatureInput').value = '';
    }

    function setGuestPhoto(file) {
        if (!file) {
            return;
        }

        const transfer = new DataTransfer();
        transfer.items.add(file);
        guestPhoto.files = transfer.files;
        guestPhotoSelection.textContent = file.name;
    }

    function closeGuestCamera() {
        if (guestCameraStream) {
            guestCameraStream.getTracks().forEach(function (track) {
                track.stop();
            });
            guestCameraStream = null;
        }

        guestCameraPreview.srcObject = null;
        guestCameraDialog.hidden = true;
    }

    async function switchGuestCamera(facingMode) {
        const previousStream = guestCameraStream;

        if (previousStream) {
            guestCameraStream = null;
            guestCameraPreview.srcObject = null;
            previousStream.getTracks().forEach(function (track) {
                track.stop();
            });
        }

        let nextStream;

        try {
            nextStream = await navigator.mediaDevices.getUserMedia({
                audio: false,
                video: { facingMode: { ideal: facingMode } }
            });
        } catch (error) {
            if (previousStream) {
                guestCameraStream = await navigator.mediaDevices.getUserMedia({
                    audio: false,
                    video: { facingMode: { ideal: guestCameraFacingMode } }
                });
                guestCameraPreview.srcObject = guestCameraStream;
            }

            throw error;
        }

        guestCameraStream = nextStream;
        guestCameraFacingMode = facingMode;
        guestCameraPreview.srcObject = nextStream;
        document.getElementById('switchGuestCamera').setAttribute(
            'aria-label',
            facingMode === 'environment' ? 'Ganti ke kamera depan' : 'Ganti ke kamera belakang'
        );
    }

    if (guestPhoto && guestCameraPhoto && guestPhotoSelection && guestCameraDialog && guestCameraPreview && guestCameraCanvas) {
        document.getElementById('chooseGuestPhoto').addEventListener('click', function () {
            guestPhoto.click();
        });

        document.getElementById('openGuestCamera').addEventListener('click', async function () {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                guestCameraPhoto.click();
                return;
            }

            try {
                await switchGuestCamera('environment');
                guestCameraDialog.hidden = false;
                document.getElementById('captureGuestPhoto').focus();
            } catch (error) {
                const message = window.isSecureContext
                    ? 'Kamera tidak dapat dibuka. Izinkan akses kamera pada browser atau gunakan tombol Pilih File.'
                    : 'Akses kamera membutuhkan HTTPS atau localhost. Buka aplikasi melalui koneksi aman atau gunakan tombol Pilih File.';
                showVisitFeedback('error', 'Kamera tidak tersedia', message);
            }
        });

        document.getElementById('switchGuestCamera').addEventListener('click', async function () {
            const nextFacingMode = guestCameraFacingMode === 'environment' ? 'user' : 'environment';

            try {
                await switchGuestCamera(nextFacingMode);
            } catch (error) {
                showVisitFeedback('error', 'Kamera tidak dapat diganti', 'Perangkat tidak dapat menggunakan kamera tersebut. Silakan coba kembali.');
            }
        });

        guestPhoto.addEventListener('change', function () {
            if (guestPhoto.files.length > 0) {
                guestPhotoSelection.textContent = guestPhoto.files[0].name;
            }
        });

        guestCameraPhoto.addEventListener('change', function () {
            if (guestCameraPhoto.files.length > 0) {
                setGuestPhoto(guestCameraPhoto.files[0]);
            }
        });

        document.getElementById('captureGuestPhoto').addEventListener('click', function () {
            const context = guestCameraCanvas.getContext('2d');
            guestCameraCanvas.width = guestCameraPreview.videoWidth;
            guestCameraCanvas.height = guestCameraPreview.videoHeight;
            context.drawImage(guestCameraPreview, 0, 0);
            guestCameraCanvas.toBlob(function (blob) {
                if (!blob) {
                    closeGuestCamera();
                    showVisitFeedback('error', 'Foto gagal diambil', 'Silakan coba kembali atau gunakan tombol Pilih File.');
                    return;
                }

                setGuestPhoto(new File([blob], 'foto-dokumentasi.jpg', { type: 'image/jpeg' }));
                closeGuestCamera();
            }, 'image/jpeg', 0.9);
        });

        document.getElementById('closeGuestCamera').addEventListener('click', closeGuestCamera);
    }

    function updateOtherPurposeField() {
        if (!visitPurpose || !otherPurposeWrap || !otherPurpose) {
            return;
        }

        const isOtherPurpose = visitPurpose.value === 'Lainnya';
        otherPurposeWrap.hidden = !isOtherPurpose;
        otherPurpose.disabled = !isOtherPurpose;
        otherPurpose.required = isOtherPurpose;
        visitPurpose.name = isOtherPurpose ? '' : 'tujuan';
        otherPurpose.name = isOtherPurpose ? 'tujuan' : '';
    }

    if (visitPurpose) {
        visitPurpose.addEventListener('change', function () {
            updateOtherPurposeField();

            if (visitPurpose.value === 'Lainnya') {
                otherPurpose.focus();
            }
        });

        updateOtherPurposeField();
    }

    function closeVisitConfirmation() {
        visitConfirm.classList.remove('is-visible');
        visitConfirm.setAttribute('aria-hidden', 'true');
    }

    function openVisitSuccess() {
        visitSuccess.classList.add('is-visible');
        visitSuccess.setAttribute('aria-hidden', 'false');
        document.body.classList.add('success-open');
        visitSuccessOk.focus();
    }

    function closeVisitSuccess() {
        visitSuccess.classList.remove('is-visible');
        visitSuccess.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('success-open');
    }

    if (visitSuccess) {
        visitSuccessOk.addEventListener('click', closeVisitSuccess);

        visitSuccess.addEventListener('click', function (event) {
            if (event.target === visitSuccess) {
                closeVisitSuccess();
            }
        });
    }

    function showVisitFeedback(type, title, message) {
        clearTimeout(feedbackTimer);
        visitFeedback.className = 'visit-feedback visit-feedback--' + type + ' is-visible';
        visitFeedbackIcon.innerHTML = type === 'success'
            ? '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m5 12 4 4L19 6"/></svg>'
            : '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v5m0 4h.01M10.3 3.9 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3l-7.5-13.1a2 2 0 0 0-3.4 0Z"/></svg>';
        visitFeedbackTitle.textContent = title;
        visitFeedbackMessage.textContent = message;
        visitFeedback.setAttribute('aria-hidden', 'false');
        feedbackTimer = setTimeout(function () {
            visitFeedback.classList.remove('is-visible');
            visitFeedback.setAttribute('aria-hidden', 'true');
        }, 6000);
    }

    if (guestBookForm) {
        guestBookForm.addEventListener('submit', function (event) {
            if (confirmedSubmit) {
                return;
            }

            event.preventDefault();
            if (!guestBookForm.checkValidity()) {
                guestBookForm.reportValidity();
                return;
            }

            visitConfirm.classList.add('is-visible');
            visitConfirm.setAttribute('aria-hidden', 'false');
            continueVisit.focus();
        });

        cancelVisit.addEventListener('click', function () {
            closeVisitConfirmation();
        });

        visitConfirm.addEventListener('click', function (event) {
            if (event.target === visitConfirm) {
                closeVisitConfirmation();
            }
        });

        continueVisit.addEventListener('click', async function () {
            closeVisitConfirmation();
            continueVisit.disabled = true;

            try {
                window.kpuLoading.show();

                const response = await fetch(guestBookForm.action, {
                    method: 'POST',
                    body: new FormData(guestBookForm),
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });

                const result = await response.json();
                if (!response.ok) {
                    const errors = result.errors ? Object.values(result.errors).flat().join(' ') : 'Data kunjungan gagal disimpan.';
                    throw new Error(errors);
                }

                guestBookForm.reset();
                clearSignaturePad();
                guestPhotoSelection.textContent = 'Belum ada foto dipilih.';
                guestCameraPhoto.value = '';
                updateOtherPurposeField();
                openVisitSuccess();
            } catch (error) {
                showVisitFeedback('error', 'Kunjungan gagal disimpan', error.message || 'Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                window.kpuLoading.hide();
                continueVisit.disabled = false;
            }
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') {
            return;
        }

        if (visitSuccess && visitSuccess.classList.contains('is-visible')) {
            closeVisitSuccess();
            return;
        }

        if (visitConfirm.classList.contains('is-visible')) {
            closeVisitConfirmation();
            return;
        }

        if (guestCameraDialog && !guestCameraDialog.hidden) {
            closeGuestCamera();
        }
    });

    function updateDateTime() {

        const now = new Date();

        const date = now.toLocaleDateString('id-ID', {
            weekday: window.innerWidth <= 750 ? 'short' : 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });

        const time = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        document.getElementById('currentDate').textContent = date;
        document.getElementById('currentTime').textContent = time;

        const visitDate = document.getElementById('visitDate');
        const visitTime = document.getElementById('visitTime');

        if (visitDate) {
            visitDate.value = now.toISOString().split('T')[0];
        }

        if (visitTime) {
            visitTime.value = now.toTimeString().split(' ')[0];
        }
    }

    updateDateTime();

    setInterval(updateDateTime, 1000);

    const stickyTopbar = document.querySelector('.topbar-sticky');

    if (stickyTopbar) {
        function syncTopbarShadow() {
            stickyTopbar.classList.toggle('is-scrolled', window.scrollY > 0);
        }

        window.addEventListener('scroll', syncTopbarShadow, { passive: true });
        syncTopbarShadow();
    }

    const revealWidgets = document.querySelectorAll('.hero, .card, .quote-card, .contact-bar');
    revealWidgets.forEach(function (widget) {
        widget.classList.add('scroll-reveal');
    });

    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: .12, rootMargin: '0px 0px -40px' });

        revealWidgets.forEach(function (widget) {
            revealObserver.observe(widget);
        });
    } else {
        revealWidgets.forEach(function (widget) {
            widget.classList.add('is-visible');
        });
    }

    function scrollToPosition(position) {
        window.scrollTo(0, position === 'top' ? 0 : document.documentElement.scrollHeight);
    }

    const navigationItems = document.querySelectorAll('.menu-item[data-nav]');
    const defaultNavigation = @json($isBeranda ? 'beranda' : 'buku-tamu');

    function syncActiveNavigation() {
        const activeNavigation = window.location.hash === '#kontak-kpu'
            ? 'kontak'
            : defaultNavigation;

        navigationItems.forEach(function (item) {
            item.classList.toggle('active', item.dataset.nav === activeNavigation);
        });
    }

    window.addEventListener('hashchange', syncActiveNavigation);
    syncActiveNavigation();

    const sidebar = document.querySelector('.sidebar');
    const menuToggle = document.getElementById('menuToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const openLogin = document.getElementById('openLogin');
    const guestLoginOverlay = document.getElementById('guestLoginOverlay');
    const guestLoginFrame = document.getElementById('guestLoginFrame');

    function closeLoginDialog() {
        guestLoginOverlay.classList.remove('open');
        guestLoginOverlay.hidden = true;
        document.body.classList.remove('login-open');
        guestLoginFrame.removeAttribute('src');
        openLogin.focus();
    }

    openLogin.addEventListener('click', function (event) {
        event.preventDefault();
        setSidebarState(false);
        sessionStorage.setItem('kpuLoginPreviousPage', window.location.href.split('#')[0]);
        sessionStorage.setItem('kpuLoginPreviousScroll', String(window.scrollY));
        guestLoginOverlay.hidden = false;
        guestLoginOverlay.classList.add('open');
        document.body.classList.add('login-open');
        guestLoginFrame.src = @json(route('login')) + '?modal=1';
    });

    guestLoginOverlay.addEventListener('click', function (event) {
        if (event.target === guestLoginOverlay) {
            closeLoginDialog();
        }
    });

    guestLoginFrame.addEventListener('load', function () {
        const frameUrl = guestLoginFrame.contentWindow.location.href;
        if (frameUrl !== 'about:blank' && new URL(frameUrl).pathname !== new URL(@json(route('login'))).pathname) {
            window.location.assign(frameUrl);
        }
    });

    function setSidebarState(isOpen) {
        sidebar.classList.toggle('open', isOpen);
        sidebarOverlay.classList.toggle('open', isOpen);
        menuToggle.setAttribute('aria-expanded', String(isOpen));
    }

    menuToggle.addEventListener('click', function () {
        setSidebarState(!sidebar.classList.contains('open'));
    });

    sidebarOverlay.addEventListener('click', function () {
        setSidebarState(false);
    });

    navigationItems.forEach(function (item) {
        item.addEventListener('click', function () {
            setSidebarState(false);
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            if (!guestLoginOverlay.hidden) {
                closeLoginDialog();
            } else {
                setSidebarState(false);
            }
        }
    });


    /* =========================
       PARAF DIGITAL
    ========================= */

    const canvas = signatureCanvas;

    if (canvas) {
        const ctx = canvas.getContext('2d');

    let drawing = false;

    function resizeCanvas() {

        const ratio = Math.max(window.devicePixelRatio || 1, 1);

        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;

        ctx.scale(ratio, ratio);

        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.strokeStyle = '#173d67';
    }

    resizeCanvas();

    window.addEventListener('resize', resizeCanvas);


    function getPosition(event) {

        const rect = canvas.getBoundingClientRect();

        if (event.touches) {

            return {
                x: event.touches[0].clientX - rect.left,
                y: event.touches[0].clientY - rect.top
            };

        }

        return {
            x: event.clientX - rect.left,
            y: event.clientY - rect.top
        };
    }


    function startDrawing(event) {

        drawing = true;

        const pos = getPosition(event);

        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);

        event.preventDefault();
    }


    function draw(event) {

        if (!drawing) return;

        const pos = getPosition(event);

        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();

        event.preventDefault();
    }


    function stopDrawing() {

        drawing = false;

        document.getElementById('signatureInput').value =
            canvas.toDataURL('image/png');
    }


    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseleave', stopDrawing);

    canvas.addEventListener('touchstart', startDrawing, {
        passive: false
    });

    canvas.addEventListener('touchmove', draw, {
        passive: false
    });

    canvas.addEventListener('touchend', stopDrawing);


    document.getElementById('clearSignature')
        .addEventListener('click', clearSignaturePad);
    }

</script>

@include('layouts.loading')
</body>
</html>
