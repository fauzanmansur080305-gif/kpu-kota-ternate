<style>
    .kpu-loading {
        position: fixed;
        inset: 0;
        z-index: 2147483647;
        display: grid;
        place-items: center;
        padding: 24px;
        background: rgba(7, 26, 54, .56);
        -webkit-backdrop-filter: blur(8px);
        backdrop-filter: blur(8px);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .25s ease, visibility .25s ease;
    }

    .kpu-loading.is-visible {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .kpu-loading__logo-wrap {
        position: relative;
        display: grid;
        place-items: center;
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: rgba(243, 246, 250, .96);
        box-shadow: 0 10px 28px rgba(0, 0, 0, .2);
    }

    .kpu-loading__logo-wrap::before {
        content: '';
        position: absolute;
        inset: -5px;
        border-radius: 50%;
        background: repeating-conic-gradient(
            from 0deg,
            #b00821 0deg 18deg,
            #b8860b 18deg 36deg,
            transparent 36deg 45deg
        );
        -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 3px), #000 0);
        mask: radial-gradient(farthest-side, transparent calc(100% - 3px), #000 0);
        animation: kpu-loading-spin .9s linear infinite;
    }

    .kpu-loading__logo-wrap::after {
        content: '';
        position: absolute;
        inset: 3px;
        border-radius: 50%;
        background: rgba(243, 246, 250, .96);
    }

    .kpu-loading__logo {
        position: relative;
        z-index: 1;
        display: block;
        width: 52px;
        height: 52px;
        object-fit: contain;
        filter: drop-shadow(0 4px 6px rgba(7, 26, 54, .14));
    }

    @keyframes kpu-loading-spin {
        to { transform: rotate(360deg); }
    }

    @media (prefers-reduced-motion: reduce) {
        .kpu-loading, .kpu-loading__logo-wrap::before {
            animation: none;
            transition: none;
        }
    }
</style>

<div class="kpu-loading" id="kpuLoading" role="status" aria-live="polite" aria-label="Sedang memuat" aria-hidden="true">
    <div class="kpu-loading__logo-wrap">
        <img class="kpu-loading__logo" src="https://www.kpu.go.id/img/logo-kpu.png" alt="Logo KPU">
    </div>
</div>

<script>
    (function () {
        const loading = document.getElementById('kpuLoading');
        let loadingTimer;

        function showLoading() {
            clearTimeout(loadingTimer);
            loadingTimer = setTimeout(function () {
                loading.classList.add('is-visible');
                loading.setAttribute('aria-hidden', 'false');
            }, 150);
        }

        function hideLoading() {
            clearTimeout(loadingTimer);
            loading.classList.remove('is-visible');
            loading.setAttribute('aria-hidden', 'true');
        }

        if (document.readyState !== 'complete') {
            showLoading();
            window.addEventListener('load', hideLoading, { once: true });
        }

        window.addEventListener('pageshow', hideLoading);

        document.addEventListener('click', function (event) {
            const link = event.target.closest('a[href]');
            if (!link || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || link.hasAttribute('download') || (link.target && link.target !== '_self')) {
                return;
            }

            const destination = new URL(link.href, window.location.href);
            if (!['http:', 'https:'].includes(destination.protocol) || destination.origin !== window.location.origin || destination.pathname.includes('/export/')) {
                return;
            }

            if (destination.pathname === window.location.pathname && destination.search === window.location.search && destination.hash) {
                return;
            }

            setTimeout(function () {
                if (!event.defaultPrevented) {
                    showLoading();
                }
            }, 0);
        });

        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            const destination = new URL(form.action, window.location.href);
            if (destination.pathname.includes('/export/')) {
                return;
            }

            setTimeout(function () {
                if (!event.defaultPrevented && form.checkValidity()) {
                    showLoading();
                }
            }, 0);
        });
    })();
</script>
