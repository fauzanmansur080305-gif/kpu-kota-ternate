<style>
    .admin-shell-sidebar {
        position: fixed;
        inset: 0 auto 0 0;
        z-index: 40;
        display: flex;
        width: 272px;
        flex-direction: column;
        padding: 22px 16px;
        color: #fff;
        background:
            radial-gradient(circle at 100% 0, rgba(184, 134, 11, .2), transparent 15rem),
            linear-gradient(180deg, #3b1608, #6b2f16);
        box-shadow: 10px 0 34px rgba(42, 15, 4, .12);
        transition: transform .25s ease;
    }
    .admin-shell-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 5px 10px 27px;
    }
    .admin-shell-brand-mark {
        display: grid;
        width: 47px;
        height: 47px;
        flex: 0 0 47px;
        place-items: center;
        overflow: hidden;
        border: 2px solid #e9eef5;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(0, 0, 0, .16);
    }
    .admin-shell-brand-mark img { display: block; width: 38px; height: 38px; object-fit: contain; }
    .admin-shell-brand strong, .admin-shell-brand small { display: block; }
    .admin-shell-brand strong { font-size: 16px; letter-spacing: .3px; }
    .admin-shell-brand small { margin-top: 3px; color: rgba(255, 255, 255, .58); font-size: 10px; letter-spacing: 1px; }
    .admin-shell-menu-label {
        margin: 0 10px 9px;
        color: rgba(255, 255, 255, .48);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .admin-shell-nav { flex: 1; }
    .admin-shell-link {
        display: flex;
        min-height: 52px;
        align-items: center;
        gap: 12px;
        margin-bottom: 5px;
        padding: 9px 12px;
        border: 1px solid transparent;
        border-radius: 13px;
        color: rgba(255, 255, 255, .74);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: color .2s ease, background .2s ease, border-color .2s ease, transform .2s ease;
    }
    .admin-shell-link:hover, .admin-shell-link.is-active {
        border-color: rgba(255, 255, 255, .12);
        color: #fff;
        background: linear-gradient(120deg, #b8860b, #8a6512);
        box-shadow: 0 9px 20px rgba(184, 134, 11, .2);
        transform: translateX(2px);
    }
    .admin-shell-link:focus-visible, .admin-shell-logout:focus-visible, .admin-shell-toggle:focus-visible {
        outline: 3px solid #f0d27a;
        outline-offset: 3px;
    }
    .admin-shell-nav-icon {
        display: grid;
        width: 32px;
        height: 32px;
        flex: 0 0 32px;
        place-items: center;
        border-radius: 10px;
        color: #f6e6b8;
        background: rgba(255, 255, 255, .1);
        font-size: 16px;
    }
    .admin-shell-system-label { margin-top: 25px; }
    .admin-shell-footer { padding: 15px 10px 0; border-top: 1px solid rgba(255, 255, 255, .13); }
    .admin-shell-logout {
        display: flex;
        width: 100%;
        min-height: 43px;
        align-items: center;
        justify-content: center;
        gap: 9px;
        border: 1px solid rgba(255, 255, 255, .16);
        border-radius: 12px;
        color: rgba(255, 255, 255, .84);
        background: rgba(255, 255, 255, .08);
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease;
    }
    .admin-shell-logout:hover { border-color: #b8860b; color: #fff; background: #8a6512; }
    .admin-shell-overlay { display: none; }
    .admin-shell-topbar {
        position: sticky;
        top: 0;
        z-index: 30;
        display: flex;
        min-height: 78px;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 13px 34px;
        border-bottom: 3px solid #b8860b;
        background: linear-gradient(105deg, rgba(255, 255, 255, .98) 0%, rgba(255, 255, 255, .98) 66%, #6b2f16 66%, #3b1608 100%);
        box-shadow: 0 8px 24px rgba(25, 45, 75, .06);
        backdrop-filter: blur(14px);
    }
    .admin-shell-heading small, .admin-shell-heading strong { display: block; }
    .admin-shell-heading small {
        margin-bottom: 5px;
        color: #8a6512;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: 1.4px;
        text-transform: uppercase;
    }
    .admin-shell-heading strong { color: #2a0f04; font-size: 15px; letter-spacing: -.1px; }
    .admin-shell-account {
        display: flex;
        min-height: 44px;
        align-items: center;
        gap: 10px;
        padding: 6px 13px 6px 7px;
        border: 1px solid rgba(255, 255, 255, .22);
        border-radius: 14px;
        color: #fff;
        background: rgba(42, 15, 4, .24);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .12);
    }
    .admin-shell-avatar {
        display: grid;
        width: 31px;
        height: 31px;
        place-items: center;
        border: 1px solid rgba(255, 255, 255, .38);
        border-radius: 10px;
        color: #3b1608;
        background: linear-gradient(145deg, #fff8df, #e8ca74);
        font-size: 12px;
        font-weight: 900;
    }
    .admin-shell-account strong, .admin-shell-account small { display: block; }
    .admin-shell-account strong { font-size: 12px; }
    .admin-shell-account small { margin-top: 2px; color: rgba(255, 255, 255, .68); font-size: 9px; }
    .admin-shell-toggle {
        display: none;
        width: 40px;
        height: 40px;
        flex: 0 0 40px;
        place-items: center;
        border: 1px solid #e5ebf2;
        border-radius: 12px;
        color: #4a1e0e;
        background: #fff;
        font-size: 19px;
        cursor: pointer;
    }
    .admin-success-notice {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0 0 18px;
        padding: 13px 48px 13px 15px;
        border: 1px solid #ccebd9;
        border-radius: 13px;
        color: #17653e;
        background: linear-gradient(110deg, #effaf3, #fbfffc);
        box-shadow: 0 8px 20px rgba(23, 101, 62, .06);
        font-size: 12px;
        font-weight: 700;
        opacity: 1;
        transform: translateY(0);
        transition: opacity .25s ease, transform .25s ease;
    }
    .admin-success-notice.is-hiding { opacity: 0; transform: translateY(-5px); }
    .admin-success-notice__icon {
        display: grid;
        width: 29px;
        height: 29px;
        flex: 0 0 29px;
        place-items: center;
        border-radius: 9px;
        color: white;
        background: #219363;
        font-size: 14px;
    }
    .admin-success-notice__dismiss {
        position: absolute;
        top: 50%;
        right: 10px;
        display: grid;
        width: 30px;
        height: 30px;
        place-items: center;
        border: 0;
        border-radius: 9px;
        color: #17653e;
        background: transparent;
        font-size: 19px;
        cursor: pointer;
        transform: translateY(-50%);
    }
    .admin-success-notice__dismiss:hover { background: rgba(33, 147, 99, .1); }
    @media (max-width: 1100px) {
        .admin-shell-sidebar { transform: translateX(-102%); }
        .admin-shell-sidebar.is-open { transform: translateX(0); }
        .admin-shell-overlay.is-open {
            position: fixed;
            inset: 0;
            z-index: 39;
            display: block;
            background: rgba(20, 8, 2, .5);
            backdrop-filter: blur(3px);
        }
        .admin-shell-toggle { display: grid; }
        .main { margin-left: 0; }
        .admin-shell-topbar { justify-content: flex-start; padding: 12px 20px; }
        .admin-shell-account { margin-left: auto; }
    }
    @media (max-width: 600px) {
        .admin-shell-topbar { min-height: 68px; gap: 11px; padding: 10px 14px; }
        .admin-shell-heading small { font-size: 9px; }
        .admin-shell-heading strong { font-size: 13px; }
        .admin-shell-account { min-height: 39px; padding-right: 9px; }
        .admin-shell-account small { display: none; }
        .admin-shell-avatar { width: 28px; height: 28px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .admin-shell-sidebar, .admin-shell-link, .admin-shell-logout, .admin-success-notice { transition: none; }
    }
</style>

<aside class="admin-shell-sidebar" id="adminShellSidebar" aria-label="Navigasi administrator">
    <div class="admin-shell-brand">
        <div class="admin-shell-brand-mark"><img src="https://www.kpu.go.id/img/logo-kpu.png" alt="Logo KPU"></div>
        <div><strong>KPU</strong><small>KOTA TERNATE</small></div>
    </div>

    <nav class="admin-shell-nav" aria-label="Menu utama">
        <p class="admin-shell-menu-label">Menu Utama</p>
        <a class="admin-shell-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
            <span class="admin-shell-nav-icon" aria-hidden="true">⌂</span><span>Dashboard</span>
        </a>
        <a class="admin-shell-link {{ request()->routeIs('admin.tamu.*') ? 'is-active' : '' }}" href="{{ route('admin.tamu.index') }}" @if(request()->routeIs('admin.tamu.*')) aria-current="page" @endif>
            <span class="admin-shell-nav-icon" aria-hidden="true">♟</span><span>Data Tamu</span>
        </a>
        <a class="admin-shell-link {{ request()->routeIs('admin.laporan.*') ? 'is-active' : '' }}" href="{{ route('admin.laporan.index') }}" @if(request()->routeIs('admin.laporan.*')) aria-current="page" @endif>
            <span class="admin-shell-nav-icon" aria-hidden="true">▤</span><span>Laporan</span>
        </a>
        <p class="admin-shell-menu-label admin-shell-system-label">Sistem</p>
        <a class="admin-shell-link {{ request()->routeIs('admin.pengaturan.*') ? 'is-active' : '' }}" href="{{ route('admin.pengaturan.index') }}" @if(request()->routeIs('admin.pengaturan.*')) aria-current="page" @endif>
            <span class="admin-shell-nav-icon" aria-hidden="true">⚙</span><span>Pengaturan</span>
        </a>
    </nav>

    <div class="admin-shell-footer">
        <form class="js-logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="admin-shell-logout" type="submit" title="Keluar dari sistem">
                <span aria-hidden="true">⇥</span><span>Logout</span>
            </button>
        </form>
    </div>
</aside>
<div class="admin-shell-overlay" id="adminShellOverlay"></div>

<script>
    (function () {
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('adminShellSidebar');
            const overlay = document.getElementById('adminShellOverlay');
            const toggle = document.getElementById('adminShellToggle');

            function closeAdminMenu() {
                sidebar.classList.remove('is-open');
                overlay.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }

            toggle.addEventListener('click', function () {
                const isOpen = sidebar.classList.toggle('is-open');
                overlay.classList.toggle('is-open', isOpen);
                toggle.setAttribute('aria-expanded', String(isOpen));
            });
            overlay.addEventListener('click', closeAdminMenu);
            sidebar.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', closeAdminMenu);
            });
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeAdminMenu();
                }
            });

            document.querySelectorAll('.admin-success-notice[data-auto-dismiss]').forEach(function (notice) {
                let dismissTimer;
                const removeNotice = function () {
                    window.clearTimeout(dismissTimer);
                    if (notice.classList.contains('is-hiding')) {
                        return;
                    }
                    notice.classList.add('is-hiding');
                    window.setTimeout(function () {
                        notice.remove();
                    }, 260);
                };

                dismissTimer = window.setTimeout(removeNotice, Number(notice.dataset.autoDismiss));
                notice.querySelector('.admin-success-notice__dismiss').addEventListener('click', removeNotice);
            });
        });
    }());
</script>
