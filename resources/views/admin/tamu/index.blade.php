<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <link rel="icon" type="image/png" href="{{ asset('kpu-logo.png') }}?v=20261005">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Data Tamu | KPU Kota Ternate</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Inter, Arial, sans-serif;
            background: #f4f7fb;
            color: #172b4d;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */

        .sidebar {
            width: 272px;
            background: linear-gradient(180deg, #4a1e0e, #6b2f16);

            color: white;
            padding: 22px 16px;
            display: flex;
            flex-direction: column;

            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 20;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;

            padding: 5px 10px 27px;
        }

        .logo {
            width: 47px;
            height: 47px;
            overflow: hidden;
            border: 2px solid #e9eef5;
            border-radius: 13px;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 8px 18px rgba(0,0,0,.16);
        }

        .logo img {
            width: 38px;
            height: 38px;
            object-fit: contain;
            display: block;
        }

        .brand strong {
            display: block;
            font-size: 16px;
        }

        .brand span {
            font-size: 10px;
            opacity: .6;
        }

        .menu-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            opacity: .45;
            margin: 0 10px 9px;
            padding: 0;
        }

        .system-label {
            margin-top: 28px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex: 1;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: rgba(255,255,255,.7);
            margin-bottom: 0;
            padding: 12px 13px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 650;
            transition: .2s;
        }

        .menu a:hover {
            background: rgba(255,255,255,.08);
            color: white;
        }

        .menu a.active {
            background: linear-gradient(135deg, #b8860b, #8a6308);

            color: white;

            box-shadow: 0 9px 20px rgba(184,134,11,.22);
        }

        .nav-icon {
            display: grid;
            width: 31px;
            height: 31px;
            flex-shrink: 0;
            place-items: center;
            border-radius: 9px;
            color: #d6e3f2;
            background: rgba(255,255,255,.1);
            font-size: 15px;
        }

        .menu a.active .nav-icon {
            background: rgba(255,255,255,.15);
        }

        /* MAIN */

        .main {
            margin-left: 272px;
            width: calc(100% - 272px);
        }

        /* TOPBAR */

        .topbar {
            min-height: 76px;
            background: linear-gradient(108deg, #ffffff 0%, #ffffff 58%, #6b2f16 58%, #4a1e0e 100%);
            border-bottom: 3px solid #b8860b;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 15px 34px;
        }

        .topbar-title strong {
            display: block;
            font-size: 15px;
        }

        .topbar-title span {
            font-size: 11px;
            color: #8a98aa;
        }

        .admin {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 14px;
            border: 1px solid rgba(255,255,255,.28);
            border-radius: 12px;
            background: rgba(42,15,4,.22);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.12);
        }

        .admin-avatar {
            width: 38px;
            height: 38px;

            border-radius: 50%;

            background: white;
            border: 2px solid white;

            display: flex;
            align-items: center;
            justify-content: center;

            font-weight: 700;
            color: #8a6512;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
        }

        .admin-info strong {
            display: block;
            font-size: 12px;
            color: white;
        }

        .admin-info span {
            font-size: 10px;
            color: rgba(255,255,255,.72);
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 15px 10px 0;
            border-top: 1px solid rgba(255,255,255,.12);
        }

        .logout-button {
            display: flex;
            width: fit-content;
            min-width: 104px;
            height: 42px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 0 auto;
            padding: 0 13px;
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 12px;
            color: rgba(255,255,255,.78);
            background: rgba(255,255,255,.08);
            font-size: 19px;
            cursor: pointer;
            transition: .2s;
        }

        .logout-text {
            font-size: 11px;
            font-weight: 800;
        }

        .logout-button:hover {
            color: white;
            background: #b8860b;
            border-color: #b8860b;
            transform: translateY(-2px);
        }

        /* CONTENT */

        .content {
            padding: 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 26px;
            margin-bottom: 5px;
        }

        .page-header p {
            font-size: 12px;
            color: #8290a3;
        }

        .btn-primary {
            border: none;

            background: linear-gradient(
                135deg,
                #4a1e0e,
                #8a6512
            );

            color: white;

            text-decoration: none;

            padding: 12px 18px;

            border-radius: 10px;

            font-size: 12px;
            font-weight: 700;

            box-shadow:
                0 8px 18px rgba(74,30,14,.18);
        }

        .page-header-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-bulk-delete {
            padding: 12px 16px;
            border: 1px solid #f0c4ca;
            border-radius: 10px;
            color: #a20a20;
            background: linear-gradient(135deg, #fff, #fff4f5);
            box-shadow: 0 6px 16px rgba(176,8,33,.08);
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: .2s;
        }

        .btn-bulk-delete:hover {
            border-color: #b00821;
            color: white;
            background: linear-gradient(135deg, #b00821, #8d071a);
            transform: translateY(-1px);
        }

        /* STAT */

        .stats {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 18px;

            margin-bottom: 22px;
        }

        .stat-card {
            display: block;
            position: relative;
            overflow: hidden;
            background: white;

            border: 1px solid #e7edf4;

            border-radius: 16px;

            padding: 20px;

            box-shadow:
                0 8px 25px rgba(25,45,75,.05);
            transition: .2s;
        }

        a.stat-card {
            color: inherit;
            text-decoration: none;
        }

        a.stat-card[aria-current="page"] {
            border-color: rgba(184,134,11,.5);
            box-shadow: 0 0 0 3px rgba(184,134,11,.1), 0 8px 25px rgba(25,45,75,.05);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(25,45,75,.1);
        }

        .stat-card:focus-visible {
            outline: 3px solid rgba(184,134,11,.45);
            outline-offset: 3px;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: 0 0 auto;
            height: 4px;
            background: #b8860b;
        }

        .stat-label {
            font-size: 11px;
            color: #8492a4;

            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 27px;
            font-weight: 800;
        }

        .stat-note {
            font-size: 10px;
            color: #4d8a65;
            margin-top: 6px;
        }

        /* TABLE CARD */

        .table-card {
            background: white;

            border-radius: 18px;

            border: 1px solid #e7edf4;

            box-shadow:
                0 8px 30px rgba(25,45,75,.05);

            overflow: hidden;
        }

        .table-header {
            padding: 20px 22px;

            border-bottom: 1px solid #edf1f5;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-header h2 {
            font-size: 16px;
        }

        .table-header span {
            font-size: 11px;
            color: #8996a8;
        }

        .guest-search {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 22px;
            border-bottom: 1px solid #edf1f5;
            background: linear-gradient(100deg, #fff, #fcfdff);
        }

        .guest-search-field {
            display: flex;
            min-width: 0;
            height: 44px;
            flex: 1;
            align-items: center;
            gap: 10px;
            padding: 0 13px;
            border: 1px solid #e1e8f0;
            border-radius: 12px;
            color: #8a98aa;
            background: white;
            box-shadow: 0 3px 10px rgba(25,45,75,.03);
            transition: border-color .2s, box-shadow .2s;
        }

        .guest-search-field:focus-within {
            border-color: #b8860b;
            box-shadow: 0 0 0 3px rgba(184,134,11,.12);
        }

        .guest-search-field svg {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
        }

        .guest-search-field input {
            width: 100%;
            min-width: 0;
            border: 0;
            outline: 0;
            color: #263a55;
            background: transparent;
            font: inherit;
            font-size: 12px;
        }

        .guest-search-field input::placeholder {
            color: #a0aaba;
        }

        .guest-search-clear:focus-visible {
            outline: 3px solid rgba(184,134,11,.35);
            outline-offset: 3px;
        }

        .guest-search-live {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #728197;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .guest-search-live i {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #32a36b;
            box-shadow: 0 0 0 4px rgba(50,163,107,.1);
        }

        .guest-search-live.is-busy i {
            background: #b8860b;
            animation: search-pulse .8s ease-in-out infinite alternate;
        }

        @keyframes search-pulse {
            to { opacity: .35; transform: scale(.8); }
        }

        .guest-search-clear {
            color: #718096;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .guest-search-clear:hover {
            color: #6b2f16;
        }

        .guest-search-clear[hidden] {
            display: none;
        }

        .guest-search-results {
            padding: 0 22px 13px;
            color: #8996a8;
            font-size: 11px;
        }

        .guest-search-results strong {
            color: #6b2f16;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;

            min-width: 900px;
        }

        th {
            background: #f8fafc;

            color: #7c8a9d;

            font-size: 10px;

            text-transform: uppercase;

            letter-spacing: .5px;

            text-align: left;

            padding: 13px 18px;

            white-space: nowrap;
        }

        td {
            padding: 15px 18px;

            border-top: 1px solid #edf1f5;

            font-size: 12px;

            color: #43546c;
        }

        tr:hover td {
            background: #fafcfe;
        }

        .number {
            color: #9aa7b7;
            font-size: 11px;
        }

        .visitor-name {
            font-weight: 700;
            color: #263a55;
        }

        .institution {
            font-size: 11px;
            color: #8190a3;
            margin-top: 3px;
        }

        .purpose {
            max-width: 180px;
        }

        .badge {
            display: inline-block;

            padding: 5px 9px;

            border-radius: 50px;

            background: #faf1da;

            color: #8a6512;

            font-size: 10px;

            font-weight: 700;
        }

        .action {
            display: flex;
            gap: 6px;
        }

        .action a,
        .action button {
            width: 37px;
            height: 37px;

            border-radius: 10px;

            display: flex;
            justify-content: center;
            align-items: center;

            border: none;

            cursor: pointer;

            text-decoration: none;

            font-size: 15px;
            transition: .2s;
        }

        .action a:hover,
        .action button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(25,45,75,.12);
        }

        .btn-view {
            background: #faf1da;
            color: #8a6512;
        }

        .btn-edit {
            background: #fff7e8;
            color: #a66a00;
        }

        .btn-delete {
            background: #fff0f1;
            color: #b00821;
        }

        .empty {
            padding: 60px 20px;

            text-align: center;

            color: #8996a8;
        }

        .empty-icon {
            font-size: 40px;
            margin-bottom: 12px;
        }

        /* PAGINATION */

        .pagination {
            padding: 18px 22px;

            border-top: 1px solid #edf1f5;
        }
        .pagination nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .pagination nav > div:first-child { color: #718096; font-size: 11px; }
        .pagination nav > div:last-child { display: flex; align-items: center; gap: 5px; }
        .pagination nav a, .pagination nav span[aria-current="page"] span, .pagination nav span[aria-disabled="true"] span {
            display: inline-flex;
            min-width: 38px;
            height: 38px;
            align-items: center;
            justify-content: center;
            padding: 0 11px;
            border: 1px solid #e5ebf2;
            border-radius: 10px;
            color: #65401f;
            background: white;
            font-size: 12px;
            font-weight: 750;
            box-shadow: 0 3px 9px rgba(25,45,75,.04);
        }
        .pagination nav a:hover { border-color: #b8860b; color: #4a1e0e; background: #fffaf0; }
        .pagination nav span[aria-current="page"] span { border-color: #6b2f16; color: white; background: linear-gradient(135deg, #6b2f16, #b8860b); }
        .pagination nav span[aria-disabled="true"] span { color: #aab4c0; background: #f7f9fb; }
        .success-alert { display: flex; align-items: center; gap: 12px; margin: 0 0 20px; padding: 14px 17px; border: 1px solid #ccebd9; border-radius: 13px; color: #17653e; background: linear-gradient(110deg, #effaf3, #fbfffc); box-shadow: 0 8px 20px rgba(23,101,62,.06); font-size: 12px; font-weight: 700; }
        .success-alert span { display: grid; width: 28px; height: 28px; flex-shrink: 0; place-items: center; border-radius: 9px; color: white; background: #219363; font-size: 14px; }

        .layout.modal-blur {
            filter: blur(4px);
            transform: scale(.995);
            transition: filter .2s, transform .2s;
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(7,26,54,.48);
            backdrop-filter: blur(8px);
        }

        .modal-backdrop.open {
            display: flex;
        }

        .modal-card {
            width: min(760px, 100%);
            max-height: 92vh;
            overflow-y: auto;
            border: 1px solid rgba(255,255,255,.7);
            border-radius: 20px;
            background: white;
            box-shadow: 0 24px 70px rgba(0,0,0,.25);
            animation: modalIn .2s ease-out;
        }

        @keyframes modalIn {
            from { opacity: 0; transform: translateY(12px) scale(.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            padding: 22px 24px;
            border-bottom: 1px solid #edf1f5;
        }

        .modal-kicker { color: #8a6512; font-size: 10px; font-weight: 800; letter-spacing: 1.2px; text-transform: uppercase; }
        .modal-header h2 { margin: 6px 0 0; font-size: 20px; }
        .modal-close { display: grid; width: 34px; height: 34px; place-items: center; border: 0; border-radius: 9px; color: #718096; background: #f2f5f8; font-size: 20px; cursor: pointer; }
        .modal-close:hover { color: #8a6512; background: #faf1da; }
        .modal-body { padding: 24px; }
        .modal-detail-grid, .modal-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .modal-detail { padding: 14px; border-radius: 12px; background: #f7f9fb; }
        .modal-detail.full, .modal-field.full { grid-column: 1 / -1; }
        .modal-form-grid[hidden], #bulkDeleteConfirmField[hidden] { display: none; }
        .modal-detail small, .modal-detail strong { display: block; }
        .modal-detail small { margin-bottom: 6px; color: #8a98aa; font-size: 10px; font-weight: 800; letter-spacing: .4px; text-transform: uppercase; }
        .modal-detail strong { color: #263a55; font-size: 13px; line-height: 1.5; white-space: pre-line; }
        .modal-field label { display: block; margin-bottom: 6px; color: #52637a; font-size: 11px; font-weight: 800; }
        .modal-field input, .modal-field textarea { width: 100%; padding: 11px 12px; border: 1px solid #dbe4ed; border-radius: 9px; outline: 0; color: #172b4d; background: #fbfcfe; font: inherit; font-size: 12px; }
        .modal-field textarea { min-height: 82px; resize: vertical; }
        .modal-field input:focus, .modal-field textarea:focus { border-color: #b8860b; box-shadow: 0 0 0 3px rgba(184,134,11,.12); }
        .modal-footer { display: flex; justify-content: flex-end; gap: 9px; padding: 18px 24px; border-top: 1px solid #edf1f5; }
        .modal-button { padding: 11px 15px; border: 0; border-radius: 9px; font-size: 12px; font-weight: 800; cursor: pointer; }
        .modal-button.secondary { color: #52637a; background: #f0f3f6; }
        .modal-button.primary { color: white; background: linear-gradient(135deg, #4a1e0e, #8a6512); }
        .modal-button.danger { color: white; background: #b00821; }
        .bulk-delete-card { width: min(560px, 100%); border-color: rgba(176, 8, 33, .12); box-shadow: 0 30px 90px rgba(20, 8, 2, .3); }
        .bulk-delete-header { align-items: center; padding: 24px 26px; background: linear-gradient(110deg, #fff, #fff8f2 58%, #fff2f3); }
        .bulk-delete-heading { display: flex; align-items: center; gap: 14px; }
        .bulk-delete-icon { display: grid; width: 48px; height: 48px; flex: 0 0 48px; place-items: center; border: 1px solid #f2d3d6; border-radius: 15px; color: #a20a20; background: linear-gradient(145deg, #fff, #ffecee); box-shadow: 0 8px 18px rgba(176, 8, 33, .1); font-size: 20px; }
        .bulk-delete-heading .modal-kicker { color: #a20a20; }
        .bulk-delete-heading h2 { font-size: 21px; letter-spacing: -.35px; }
        .bulk-delete-intro { display: flex; gap: 11px; margin: 0 0 20px; padding: 13px 14px; border: 1px solid #f5e2bd; border-radius: 13px; color: #76531a; background: linear-gradient(110deg, #fffaf0, #fff); font-size: 12px; line-height: 1.6; }
        .bulk-delete-intro span:first-child { display: grid; width: 24px; height: 24px; flex: 0 0 24px; place-items: center; border-radius: 8px; color: white; background: #b8860b; font-weight: 900; }
        .bulk-delete-mode { margin-bottom: 15px; }
        .bulk-delete-mode select { width: 100%; min-height: 45px; padding: 10px 12px; border: 1px solid #dbe4ed; border-radius: 11px; outline: 0; color: #172b4d; background: #fbfcfe; font: inherit; font-size: 12px; }
        .bulk-delete-mode select:focus, .bulk-delete-date input:focus { border-color: #b8860b; box-shadow: 0 0 0 3px rgba(184, 134, 11, .12); }
        .bulk-delete-date { gap: 12px; padding: 15px; border: 1px solid #e7edf4; border-radius: 13px; background: #f8fafc; }
        .bulk-delete-date .modal-field label { color: #52637a; }
        .bulk-delete-date input { width: 100%; min-height: 42px; padding: 9px 10px; border: 1px solid #dbe4ed; border-radius: 10px; outline: 0; color: #172b4d; background: white; font: inherit; font-size: 12px; }
        .bulk-delete-confirm { display: flex; align-items: flex-start; gap: 10px; margin-top: 16px; padding: 13px; border: 1px solid #f1d0d4; border-radius: 12px; color: #902033; background: #fff7f7; font-size: 12px; font-weight: 750; line-height: 1.5; cursor: pointer; }
        .bulk-delete-confirm input { width: 17px; height: 17px; flex: 0 0 17px; margin: 1px 0 0; accent-color: #b00821; }
        .bulk-delete-footer { align-items: center; padding: 17px 24px; background: #fbfcfe; }
        .bulk-delete-footer .modal-button { min-height: 42px; padding-inline: 17px; border-radius: 11px; transition: transform .2s, box-shadow .2s, filter .2s; }
        .bulk-delete-footer .modal-button:hover { transform: translateY(-1px); }
        .bulk-delete-footer .modal-button.danger { background: linear-gradient(135deg, #b00821, #85091e); box-shadow: 0 8px 18px rgba(176, 8, 33, .18); }
        .signature-field { grid-column: 1 / -1; }
        .signature-canvas { display: block; width: 100%; height: 125px; border: 1px dashed #c8d2de; border-radius: 10px; background: linear-gradient(180deg, #fff, #fbfcfe); touch-action: none; cursor: crosshair; }
        .signature-hint { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: 7px; color: #8290a3; font-size: 10px; }
        .signature-clear { padding: 6px 9px; border: 1px solid #e5ebf2; border-radius: 8px; color: #65401f; background: white; font-size: 10px; font-weight: 750; cursor: pointer; }
        .media-preview { display: block; max-width: 100%; max-height: 220px; margin-top: 12px; border: 1px solid #e5ebf2; border-radius: 11px; object-fit: contain; background: #fff; }
        .media-preview[hidden] { display: none; }

        @media(max-width: 620px) {
            .modal-detail-grid, .modal-form-grid { grid-template-columns: 1fr; }
            .modal-detail.full, .modal-field.full { grid-column: auto; }
            .modal-header, .modal-body, .modal-footer { padding: 18px; }
            .guest-search { flex-wrap: wrap; padding: 13px 16px; }
            .guest-search-field { flex-basis: 100%; }
            .guest-search-results { padding: 0 16px 12px; }
        }

        /* RESPONSIVE */

        @media(max-width: 900px) {

            .sidebar {
                display: none;
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 18px;
            }

            .topbar {
                padding: 0 18px;
            }

            .page-header {
                align-items: flex-start;
                gap: 15px;
                flex-direction: column;
            }

        }

    </style>

</head>

<body>

<div class="layout">

    @include('admin.partials.navigation')

    <main class="main">
        @include('admin.partials.header', ['pageTitle' => 'Data Tamu - Dashboard Admin'])

        <section class="content">

            <!-- PAGE HEADER -->

            <div class="page-header">

                <div>

                    <h1>Data Tamu</h1>

                    <p>
                        Kelola seluruh data kunjungan tamu
                        KPU Kota Ternate.
                    </p>

                </div>

                @include('admin.partials.success-notice', ['message' => session('success')])

                <div class="page-header-actions">
                    <button class="btn-bulk-delete" type="button" data-modal="bulk-delete">Hapus Data Tamu</button>
                    <button class="btn-primary" type="button" data-modal="create">
                        + Tambah Data
                    </button>
                </div>

            </div>


            <!-- STATISTICS -->

            <div class="stats">

                <a class="stat-card" href="{{ route('admin.tamu.index', ['filter' => 'all']) }}" @if($filter === 'all') aria-current="page" @endif>

                    <div class="stat-label">
                        TOTAL TAMU
                    </div>

                    <div class="stat-value">
                        {{ $totalTamu }}
                    </div>

                    <div class="stat-note">
                        Total seluruh data
                    </div>

                </a>


                <a class="stat-card" href="{{ route('admin.tamu.index', ['filter' => 'today']) }}" @if($filter === 'today') aria-current="page" @endif>

                    <div class="stat-label">
                        TOTAL TAMU HARI INI
                    </div>

                    <div class="stat-value">
                        {{ $totalTamuHariIni }}
                    </div>

                    <div class="stat-note">
                        Kunjungan hari ini
                    </div>

                </a>


                <div class="stat-card">

                    <div class="stat-label">
                        HALAMAN
                    </div>

                    <div class="stat-value">
                        <span id="guestCurrentPage">{{ $tamus->currentPage() }}</span>
                    </div>

                    <div class="stat-note">
                        Dari <span id="guestLastPage">{{ $tamus->lastPage() }}</span> halaman
                    </div>

                </div>

            </div>


            <!-- TABLE -->

            <div class="table-card">

                <div class="table-header">

                    <div>

                        <h2>{{ $filter === 'today' ? 'Daftar Tamu Hari Ini' : ($filter === 'month' ? 'Daftar Tamu Bulan Ini' : 'Daftar Total Tamu') }}</h2>

                        <span>{{ $filter === 'today' ? 'Data kunjungan hari ini dari database' : ($filter === 'month' ? 'Data kunjungan bulan ini dari database' : 'Seluruh data kunjungan dari database') }}</span>

                    </div>

                </div>

                <form class="guest-search" id="guestSearchForm" action="{{ route('admin.tamu.index') }}" method="GET" role="search">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <label class="guest-search-field" for="guestSearch">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="10.8" cy="10.8" r="6.8" stroke="currentColor" stroke-width="1.8"></circle>
                            <path d="m16 16 4.2 4.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                        </svg>
                        <input
                            id="guestSearch"
                            type="search"
                            name="q"
                            value="{{ $search }}"
                            maxlength="100"
                            placeholder="Cari nama, instansi, tujuan, atau nomor HP..."
                            aria-label="Cari data tamu"
                        >
                    </label>
                    <span class="guest-search-live" id="guestSearchLive"><i aria-hidden="true"></i><span>Pencarian langsung</span></span>
                    <a
                        class="guest-search-clear"
                        id="guestSearchClear"
                        href="{{ route('admin.tamu.index', ['filter' => $filter]) }}"
                        @if($search === '') hidden @endif
                    >Hapus pencarian</a>
                </form>

                <div id="guestSearchResults" aria-busy="false">
                    @include('admin.tamu.partials.search-results')
                </div>

            </div>

        </section>

    </main>

</div>

<div class="modal-backdrop" id="viewModal" role="dialog" aria-modal="true" aria-labelledby="viewModalTitle">
    <section class="modal-card">
        <header class="modal-header">
            <div><div class="modal-kicker">Detail Kunjungan</div><h2 id="viewModalTitle">Data Tamu</h2></div>
            <button class="modal-close" type="button" data-close-modal aria-label="Tutup">&times;</button>
        </header>
        <div class="modal-body">
            <div class="modal-detail-grid">
                <div class="modal-detail"><small>Nama Tamu</small><strong id="viewNama"></strong></div>
                <div class="modal-detail"><small>Instansi</small><strong id="viewInstansi"></strong></div>
                <div class="modal-detail"><small>Tujuan</small><strong id="viewTujuan"></strong></div>
                <div class="modal-detail"><small>Waktu Kunjungan</small><strong id="viewHari"></strong><strong id="viewJam"></strong></div>
                <div class="modal-detail"><small>Nomor Handphone</small><strong id="viewHandphone"></strong></div>
                <div class="modal-detail"><small>Paraf</small><strong id="viewParaf"></strong><img class="media-preview" id="viewParafImage" alt="Paraf tamu" hidden></div>
                <div class="modal-detail full"><small>Dokumentasi</small><img class="media-preview" id="viewGambar" alt="Dokumentasi tamu" hidden></div>
                <div class="modal-detail full"><small>Alamat</small><strong id="viewAlamat"></strong></div>
                <div class="modal-detail full"><small>Keperluan</small><strong id="viewKeperluan"></strong></div>
            </div>
        </div>
        <footer class="modal-footer"><button class="modal-button secondary" type="button" data-close-modal>Tutup</button></footer>
    </section>
</div>

<div class="modal-backdrop" id="editModal" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
    <section class="modal-card">
        <header class="modal-header">
            <div><div class="modal-kicker">Kelola Data</div><h2 id="editModalTitle">Edit Data Tamu</h2></div>
            <button class="modal-close" type="button" data-close-modal aria-label="Tutup">&times;</button>
        </header>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body"><div class="modal-form-grid">
                <div class="modal-field"><label for="editHari">Hari / Tanggal</label><input id="editHari" name="hari_tanggal" type="date" required></div>
                <div class="modal-field"><label for="editJam">Jam</label><input id="editJam" name="jam" type="time" required></div>
                <div class="modal-field"><label for="editNama">Nama Lengkap</label><input id="editNama" name="nama" required></div>
                <div class="modal-field"><label for="editInstansi">Instansi</label><input id="editInstansi" name="instansi" required></div>
                <div class="modal-field full"><label for="editAlamat">Alamat</label><textarea id="editAlamat" name="alamat" required></textarea></div>
                <div class="modal-field full"><label for="editKeperluan">Keperluan</label><textarea id="editKeperluan" name="keperluan" required></textarea></div>
                <div class="modal-field"><label for="editTujuan">Tujuan</label><input id="editTujuan" name="tujuan" required></div>
                <div class="modal-field"><label for="editHandphone">Nomor Handphone</label><input id="editHandphone" name="no_handphone" required></div>
                <div class="modal-field signature-field">
                    <label for="editSignatureCanvas">Paraf</label>
                    <canvas class="signature-canvas" id="editSignatureCanvas" aria-label="Area menggambar paraf"></canvas>
                    <input id="editParaf" name="paraf" type="hidden">
                    <div class="signature-hint"><span>Gambar paraf dengan mouse atau layar sentuh.</span><button class="signature-clear" id="editSignatureClear" type="button">Hapus paraf</button></div>
                    <small class="field-error" id="editSignatureError" role="alert" hidden>Paraf sebelumnya gagal dimuat. Silakan gambar ulang sebelum menyimpan.</small>
                </div>
                <div class="modal-field full">
                    <label for="editGambar">Gambar Dokumentasi</label>
                    <input id="editGambar" name="gambar" type="file" accept="image/png,image/jpeg">
                    <img class="media-preview" id="editGambarPreview" alt="Pratinjau dokumentasi" hidden>
                </div>
            </div></div>
            <footer class="modal-footer"><button class="modal-button secondary" type="button" data-close-modal>Batal</button><button class="modal-button primary" type="submit">Simpan Perubahan</button></footer>
        </form>
    </section>
</div>

<div class="modal-backdrop" id="createModal" role="dialog" aria-modal="true" aria-labelledby="createModalTitle">
    <section class="modal-card">
        <header class="modal-header">
            <div><div class="modal-kicker">Buku Tamu</div><h2 id="createModalTitle">Tambah Data Tamu</h2></div>
            <button class="modal-close" type="button" data-close-modal aria-label="Tutup">&times;</button>
        </header>
        <form action="{{ route('admin.tamu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body"><div class="modal-form-grid">
                <div class="modal-field"><label for="createHari">Hari / Tanggal</label><input id="createHari" name="hari_tanggal" type="date" value="{{ now('Asia/Jayapura')->toDateString() }}" required></div>
                <div class="modal-field"><label for="createJam">Jam</label><input id="createJam" name="jam" type="time" value="{{ now('Asia/Jayapura')->format('H:i') }}" required></div>
                <div class="modal-field"><label for="createNama">Nama Lengkap</label><input id="createNama" name="nama" required></div>
                <div class="modal-field"><label for="createInstansi">Instansi</label><input id="createInstansi" name="instansi" required></div>
                <div class="modal-field full"><label for="createAlamat">Alamat</label><textarea id="createAlamat" name="alamat" required></textarea></div>
                <div class="modal-field full"><label for="createKeperluan">Keperluan</label><textarea id="createKeperluan" name="keperluan" required></textarea></div>
                <div class="modal-field"><label for="createTujuan">Tujuan</label><input id="createTujuan" name="tujuan" required></div>
                <div class="modal-field"><label for="createHandphone">Nomor Handphone</label><input id="createHandphone" name="no_handphone" required></div>
                <div class="modal-field signature-field">
                    <label for="createSignatureCanvas">Paraf</label>
                    <canvas class="signature-canvas" id="createSignatureCanvas" aria-label="Area menggambar paraf"></canvas>
                    <input id="createParaf" name="paraf" type="hidden">
                    <div class="signature-hint"><span>Gambar paraf dengan mouse atau layar sentuh.</span><button class="signature-clear" id="createSignatureClear" type="button">Hapus paraf</button></div>
                </div>
                <div class="modal-field full">
                    <label for="createGambar">Gambar Dokumentasi</label>
                    <input id="createGambar" name="gambar" type="file" accept="image/png,image/jpeg">
                    <img class="media-preview" id="createGambarPreview" alt="Pratinjau dokumentasi" hidden>
                </div>
            </div></div>
            <footer class="modal-footer"><button class="modal-button secondary" type="button" data-close-modal>Batal</button><button class="modal-button primary" type="submit">Simpan Data</button></footer>
        </form>
    </section>
</div>

<div class="modal-backdrop" id="deleteModal" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
    <section class="modal-card">
        <header class="modal-header">
            <div><div class="modal-kicker">Konfirmasi</div><h2 id="deleteModalTitle">Hapus Data Tamu?</h2></div>
            <button class="modal-close" type="button" data-close-modal aria-label="Tutup">&times;</button>
        </header>
        <div class="modal-body"><p style="margin:0;color:#718096;font-size:13px;line-height:1.6;">Data kunjungan <strong id="deleteNama"></strong> akan dihapus dari database. Tindakan ini tidak dapat dibatalkan.</p></div>
        <footer class="modal-footer bulk-delete-footer">
            <button class="modal-button secondary" type="button" data-close-modal>Batal</button>
            <form id="deleteForm" method="POST">@csrf @method('DELETE')<button class="modal-button danger" type="submit">Hapus Data</button></form>
        </footer>
    </section>
</div>

<div class="modal-backdrop" id="bulkDeleteModal" role="dialog" aria-modal="true" aria-labelledby="bulkDeleteModalTitle">
    <section class="modal-card bulk-delete-card">
        <header class="modal-header bulk-delete-header">
            <div class="bulk-delete-heading"><span class="bulk-delete-icon" aria-hidden="true">⌫</span><div><div class="modal-kicker">Kelola Data Tamu</div><h2 id="bulkDeleteModalTitle">Hapus Data Tamu</h2></div></div>
            <button class="modal-close" type="button" data-close-modal aria-label="Tutup">&times;</button>
        </header>
        <form action="{{ route('admin.tamu.destroy-matching') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <p class="bulk-delete-intro"><span aria-hidden="true">!</span><span>Pilih rentang tanggal tertentu atau seluruh data. Data yang dihapus beserta dokumentasinya tidak dapat dipulihkan.</span></p>
                @if($errors->hasAny(['target', 'date_from', 'date_to', 'confirm_all']))
                    <div class="errors" role="alert">{{ $errors->first('target') ?: ($errors->first('date_from') ?: ($errors->first('date_to') ?: $errors->first('confirm_all'))) }}</div>
                @endif
                <div class="modal-field bulk-delete-mode">
                    <label for="bulkDeleteTarget">Data yang dihapus</label>
                    <select id="bulkDeleteTarget" name="target" required>
                        <option value="range" @selected(old('target') === 'range')>Rentang tanggal</option>
                        <option value="all" @selected(old('target', 'all') === 'all')>Semua data tamu</option>
                    </select>
                </div>
                <div class="modal-form-grid bulk-delete-date" id="bulkDeleteDateFields" hidden>
                    <div class="modal-field"><label for="bulkDeleteDateFrom">Dari tanggal</label><input id="bulkDeleteDateFrom" name="date_from" type="date" value="{{ old('date_from') }}"></div>
                    <div class="modal-field"><label for="bulkDeleteDateTo">Sampai tanggal</label><input id="bulkDeleteDateTo" name="date_to" type="date" value="{{ old('date_to') }}"></div>
                </div>
                <label class="bulk-delete-confirm" id="bulkDeleteConfirmField">
                    <input id="bulkDeleteConfirm" name="confirm_all" type="checkbox" value="1" @checked(old('confirm_all'))>
                    <span>Saya memahami bahwa seluruh data tamu dan dokumentasinya akan dihapus permanen.</span>
                </label>
            </div>
            <footer class="modal-footer">
                <button class="modal-button secondary" type="button" data-close-modal>Batal</button>
                <button class="modal-button danger" type="submit">Hapus Data Tamu</button>
            </footer>
        </form>
    </section>
</div>

<script>
    const modalRoot = document.body;
    const pageLayout = document.querySelector('.layout');
    const modalBaseUrl = '{{ url('/admin/tamu') }}';
    const modals = document.querySelectorAll('.modal-backdrop');
    const signatureResizeHandlers = {};

    function closeModals() {
        modals.forEach(function (modal) { modal.classList.remove('open'); });
        pageLayout.classList.remove('modal-blur');
        modalRoot.style.overflow = '';
    }

    function openModal(id) {
        closeModals();
        document.getElementById(id).classList.add('open');
        pageLayout.classList.add('modal-blur');
        modalRoot.style.overflow = 'hidden';
        if (id === 'createModal' || id === 'editModal') {
            requestAnimationFrame(signatureResizeHandlers[id === 'createModal' ? 'create' : 'edit']);
        }
    }

    document.querySelectorAll('[data-close-modal]').forEach(function (button) {
        button.addEventListener('click', closeModals);
    });

    modals.forEach(function (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) closeModals();
        });
    });

    const guestSearchForm = document.getElementById('guestSearchForm');
    const guestSearchInput = document.getElementById('guestSearch');
    const guestSearchResults = document.getElementById('guestSearchResults');
    const guestSearchLive = document.getElementById('guestSearchLive');
    const guestSearchLiveText = guestSearchLive.querySelector('span');
    const guestSearchClear = document.getElementById('guestSearchClear');
    let guestSearchTimer;
    let guestSearchController;

    async function refreshGuestSearch() {
        if (guestSearchController) {
            guestSearchController.abort();
        }

        const searchUrl = new URL(guestSearchForm.action, window.location.origin);
        const searchTerm = guestSearchInput.value.trim();
        searchUrl.searchParams.set('filter', guestSearchForm.elements.filter.value);
        searchUrl.searchParams.delete('page');
        if (searchTerm) {
            searchUrl.searchParams.set('q', searchTerm);
        } else {
            searchUrl.searchParams.delete('q');
        }

        const controller = new AbortController();
        guestSearchController = controller;
        guestSearchResults.setAttribute('aria-busy', 'true');
        guestSearchLive.classList.add('is-busy');
        guestSearchLiveText.textContent = 'Mencari...';
        guestSearchResults.querySelector('#guestSearchStatus').textContent = 'Memperbarui daftar tamu...';

        try {
            const response = await fetch(searchUrl, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: controller.signal
            });

            if (!response.ok) {
                throw new Error('Pencarian gagal dimuat.');
            }

            const result = await response.json();
            if (typeof result.html !== 'string') {
                throw new Error('Respons pencarian tidak valid.');
            }

            guestSearchResults.innerHTML = result.html;
            document.getElementById('guestCurrentPage').textContent = result.currentPage;
            document.getElementById('guestLastPage').textContent = result.lastPage;
            guestSearchClear.hidden = !searchTerm;
            window.history.replaceState({}, '', searchUrl);
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            guestSearchResults.querySelector('#guestSearchStatus').textContent = 'Pencarian gagal dimuat. Periksa koneksi Anda lalu coba lagi.';
            console.error('Pencarian data tamu gagal dimuat.', error);
        } finally {
            if (guestSearchController === controller) {
                guestSearchResults.setAttribute('aria-busy', 'false');
                guestSearchLive.classList.remove('is-busy');
                guestSearchLiveText.textContent = 'Pencarian langsung';
                guestSearchController = null;
            }
        }
    }

    guestSearchInput.addEventListener('input', function () {
        window.clearTimeout(guestSearchTimer);
        if (guestSearchController) {
            guestSearchController.abort();
            guestSearchController = null;
        }
        guestSearchResults.setAttribute('aria-busy', 'false');
        guestSearchLive.classList.remove('is-busy');
        guestSearchLiveText.textContent = 'Pencarian langsung';

        guestSearchTimer = window.setTimeout(refreshGuestSearch, 250);
    });

    guestSearchForm.addEventListener('submit', function (event) {
        event.preventDefault();
        window.clearTimeout(guestSearchTimer);
        refreshGuestSearch();
    });

    guestSearchClear.addEventListener('click', function (event) {
        event.preventDefault();
        guestSearchInput.value = '';
        window.clearTimeout(guestSearchTimer);
        refreshGuestSearch();
        guestSearchInput.focus();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeModals();
    });

    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-modal="view"]');
        if (!button) {
            return;
        }

        const fields = ['Nama', 'Instansi', 'Tujuan', 'Hari', 'Jam', 'Handphone', 'Alamat', 'Keperluan', 'Paraf'];
        fields.forEach(function (field) { document.getElementById('view' + field).textContent = button.dataset[field.toLowerCase()]; });
        const signature = document.getElementById('viewParafImage');
        signature.hidden = !button.dataset.parafUrl;
        signature.src = button.dataset.parafUrl || '';
        if (button.dataset.parafUrl) {
            document.getElementById('viewParaf').textContent = '';
        }
        const documentation = document.getElementById('viewGambar');
        documentation.hidden = !button.dataset.gambarUrl;
        documentation.src = button.dataset.gambarUrl || '';
        openModal('viewModal');
    });

    function drawSavedSignature(prefix, imageData) {
        const canvas = document.getElementById(prefix + 'SignatureCanvas');
        const context = canvas.getContext('2d');
        const image = new Image();
        image.onload = function () {
            const bounds = canvas.getBoundingClientRect();
            const scale = Math.min(bounds.width / image.naturalWidth, bounds.height / image.naturalHeight);
            const width = image.naturalWidth * scale;
            const height = image.naturalHeight * scale;
            context.drawImage(image, (bounds.width - width) / 2, (bounds.height - height) / 2, width, height);
        };
        image.onerror = function () {
            if (prefix === 'edit') {
                document.getElementById('editSignatureError').hidden = false;
            }
        };
        image.src = imageData;
    }

    function setupSignaturePad(prefix) {
        const canvas = document.getElementById(prefix + 'SignatureCanvas');
        const context = canvas.getContext('2d');
        const input = document.getElementById(prefix + 'Paraf');
        const error = prefix === 'edit' ? document.getElementById('editSignatureError') : null;
        let drawing = false;

        function resize() {
            const bounds = canvas.getBoundingClientRect();
            const ratio = window.devicePixelRatio || 1;
            canvas.width = Math.max(1, Math.round(bounds.width * ratio));
            canvas.height = Math.max(1, Math.round(bounds.height * ratio));
            context.setTransform(ratio, 0, 0, ratio, 0, 0);
            context.lineWidth = 2.5;
            context.lineCap = 'round';
            context.lineJoin = 'round';
            context.strokeStyle = '#172b4d';
            if (input.value) {
                drawSavedSignature(prefix, input.value);
            }
            signatureResizeHandlers[prefix] = resize;
        }

        canvas.addEventListener('pointerdown', function (event) {
            drawing = true;
            canvas.setPointerCapture(event.pointerId);
            const bounds = canvas.getBoundingClientRect();
            context.beginPath();
            context.moveTo(event.clientX - bounds.left, event.clientY - bounds.top);
            if (error) {
                error.hidden = true;
            }
        });

        canvas.addEventListener('pointermove', function (event) {
            if (!drawing) {
                return;
            }

            const bounds = canvas.getBoundingClientRect();
            context.lineTo(event.clientX - bounds.left, event.clientY - bounds.top);
            context.stroke();
        });

        function stopDrawing() {
            if (drawing) {
                drawing = false;
                input.value = canvas.toDataURL('image/png');
            }
        }

        canvas.addEventListener('pointerup', stopDrawing);
        canvas.addEventListener('pointercancel', stopDrawing);
        document.getElementById(prefix + 'SignatureClear').addEventListener('click', function () {
            context.clearRect(0, 0, canvas.width, canvas.height);
            input.value = '';
            if (error) {
                error.hidden = true;
            }
        });
        window.addEventListener('resize', resize);
        resize();
    }

    function previewDocumentation(inputId, previewId, source) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        input.dataset.currentSource = source || '';
        preview.hidden = !source;
        preview.src = source || '';

        input.addEventListener('change', function () {
            const file = input.files[0];
            if (!file) {
                preview.src = input.dataset.currentSource || '';
                preview.hidden = !input.dataset.currentSource;
                return;
            }

            if (input.dataset.objectUrl) {
                URL.revokeObjectURL(input.dataset.objectUrl);
            }

            input.dataset.objectUrl = URL.createObjectURL(file);
            preview.src = input.dataset.objectUrl;
            preview.hidden = false;
        });
    }

    setupSignaturePad('create');
    setupSignaturePad('edit');
    previewDocumentation('createGambar', 'createGambarPreview', '');
    previewDocumentation('editGambar', 'editGambarPreview', '');

    document.querySelector('[data-modal="create"]').addEventListener('click', function () {
        openModal('createModal');
    });

    document.addEventListener('click', async function (event) {
        const button = event.target.closest('[data-modal="edit"]');
        if (!button) {
            return;
        }

        const fields = ['Hari', 'Jam', 'Nama', 'Instansi', 'Alamat', 'Keperluan', 'Tujuan', 'Handphone'];
        fields.forEach(function (field) { document.getElementById('edit' + field).value = button.dataset[field.toLowerCase()] || ''; });
        document.getElementById('editParaf').value = '';
        document.getElementById('editSignatureError').hidden = true;
        document.getElementById('editGambar').value = '';
        document.getElementById('editGambar').dataset.currentSource = button.dataset.gambarUrl || '';
        const imagePreview = document.getElementById('editGambarPreview');
        imagePreview.src = button.dataset.gambarUrl || '';
        imagePreview.hidden = !button.dataset.gambarUrl;
        document.getElementById('editForm').action = modalBaseUrl + '/' + button.dataset.id;
        openModal('editModal');

        if (button.dataset.parafUrl) {
            try {
                const response = await fetch(button.dataset.parafUrl);
                if (!response.ok) {
                    throw new Error('Paraf gagal dimuat.');
                }

                const blob = await response.blob();
                const imageData = await new Promise(function (resolve, reject) {
                    const reader = new FileReader();
                    reader.onload = function () { resolve(reader.result); };
                    reader.onerror = function () { reject(reader.error); };
                    reader.readAsDataURL(blob);
                });
                document.getElementById('editParaf').value = imageData;
                drawSavedSignature('edit', imageData);
            } catch (error) {
                document.getElementById('editSignatureError').hidden = false;
                console.error('Paraf tamu gagal dimuat untuk diedit.', error);
            }
        }
    });

    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-modal="delete"]');
        if (!button) {
            return;
        }

        document.getElementById('deleteNama').textContent = button.dataset.nama;
        document.getElementById('deleteForm').action = modalBaseUrl + '/' + button.dataset.id;
        openModal('deleteModal');
    });

    const bulkDeleteTarget = document.getElementById('bulkDeleteTarget');
    const bulkDeleteDateFields = document.getElementById('bulkDeleteDateFields');
    const bulkDeleteDateFrom = document.getElementById('bulkDeleteDateFrom');
    const bulkDeleteDateTo = document.getElementById('bulkDeleteDateTo');
    const bulkDeleteConfirmField = document.getElementById('bulkDeleteConfirmField');
    const bulkDeleteConfirm = document.getElementById('bulkDeleteConfirm');

    function updateBulkDeleteFields() {
        const deleteAll = bulkDeleteTarget.value === 'all';
        bulkDeleteDateFields.hidden = deleteAll;
        bulkDeleteDateFrom.required = !deleteAll;
        bulkDeleteDateTo.required = !deleteAll;
        bulkDeleteConfirmField.hidden = !deleteAll;
        bulkDeleteConfirm.required = deleteAll;
    }

    bulkDeleteTarget.addEventListener('change', updateBulkDeleteFields);
    updateBulkDeleteFields();

    document.querySelector('[data-modal="bulk-delete"]').addEventListener('click', function () {
        openModal('bulkDeleteModal');
    });

    @if($errors->hasAny(['target', 'date_from', 'date_to', 'confirm_all']))
        openModal('bulkDeleteModal');
    @endif
</script>

@include('admin.partials.logout-confirmation')
@include('layouts.loading')
</body>

</html>