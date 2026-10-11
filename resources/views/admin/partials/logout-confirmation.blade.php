<style>
    .logout-confirmation {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: grid;
        place-items: center;
        padding: 20px;
        background: rgba(20, 8, 2, .52);
        backdrop-filter: blur(9px) saturate(120%);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .3s ease, visibility .3s ease;
    }

    .logout-confirmation.is-open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .logout-confirmation__card {
        position: relative;
        width: min(410px, 100%);
        padding: 34px 30px 28px;
        overflow: hidden;
        border: 1px solid rgba(196, 155, 44, .32);
        border-radius: 24px;
        background: linear-gradient(160deg, #3b1608 0%, #2a0f04 55%, #1d0a02 100%);
        box-shadow: 0 34px 80px rgba(15, 5, 1, .55), inset 0 1px 0 rgba(255, 255, 255, .07);
        text-align: center;
        transform: translateY(16px) scale(.96);
        transition: transform .34s cubic-bezier(.2, .8, .25, 1);
    }

    .logout-confirmation.is-open .logout-confirmation__card {
        transform: translateY(0) scale(1);
    }

    .logout-confirmation__card::before {
        position: absolute;
        top: -92px;
        left: 50%;
        width: 262px;
        height: 262px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(196, 155, 44, .3), transparent 68%);
        content: "";
        pointer-events: none;
    }

    .logout-confirmation__icon {
        position: relative;
        z-index: 1;
        display: grid;
        width: 76px;
        height: 76px;
        margin: 0 auto 20px;
        place-items: center;
        border: 1px solid rgba(196, 155, 44, .35);
        border-radius: 50%;
        color: white;
        background: linear-gradient(140deg, #d8ae3d, #8a6512);
        box-shadow: 0 14px 30px rgba(196, 155, 44, .35), inset 0 2px 0 rgba(255, 255, 255, .32);
        font-size: 25px;
        font-weight: 800;
    }

    .logout-confirmation__icon::after {
        position: absolute;
        inset: -9px;
        border: 1px solid rgba(196, 155, 44, .35);
        border-radius: 50%;
        animation: logout-confirmation-ring 2.4s ease-out infinite;
        content: "";
    }

    .logout-confirmation__card h2 {
        position: relative;
        z-index: 1;
        margin: 0;
        color: white;
        font-size: 23px;
        line-height: 1.25;
    }

    .logout-confirmation__card p {
        position: relative;
        z-index: 1;
        margin: 12px 0 24px;
        color: rgba(255, 255, 255, .72);
        font-size: 13px;
        line-height: 1.6;
    }

    .logout-confirmation__actions {
        position: relative;
        z-index: 1;
        display: flex;
        gap: 9px;
    }

    .logout-confirmation__button {
        flex: 1;
        min-height: 42px;
        padding: 0 16px;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 12px;
        font: inherit;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .logout-confirmation__button:hover,
    .logout-confirmation__button:focus-visible {
        transform: translateY(-2px);
    }

    .logout-confirmation__button--cancel {
        color: rgba(255, 255, 255, .86);
        background: rgba(255, 255, 255, .08);
    }

    .logout-confirmation__button--confirm {
        border-color: transparent;
        color: #2a0f04;
        background: linear-gradient(135deg, #e0bd52, #c49b2c 55%, #8a6512);
        box-shadow: 0 12px 24px rgba(196, 155, 44, .25);
    }

    .logout-confirmation__button--confirm:hover,
    .logout-confirmation__button--confirm:focus-visible {
        box-shadow: 0 16px 30px rgba(196, 155, 44, .36);
    }

    @keyframes logout-confirmation-ring {
        0% { transform: scale(.92); opacity: .85; }
        70% { transform: scale(1.16); opacity: 0; }
        100% { transform: scale(1.16); opacity: 0; }
    }

    @media (max-width: 750px) {
        .logout-confirmation__card { padding: 30px 22px 26px; }
        .logout-confirmation__card h2 { font-size: 20px; }
        .logout-confirmation__icon { width: 66px; height: 66px; margin-bottom: 18px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .logout-confirmation,
        .logout-confirmation__card,
        .logout-confirmation__icon::after,
        .logout-confirmation__button { animation: none; transition: none; }
    }
</style>

<div class="logout-confirmation" id="logoutConfirmation" role="alertdialog" aria-modal="true" aria-labelledby="logoutConfirmationTitle" aria-describedby="logoutConfirmationMessage" aria-hidden="true">
    <section class="logout-confirmation__card">
        <div class="logout-confirmation__icon" aria-hidden="true">?</div>
        <h2 id="logoutConfirmationTitle">Yakin ingin <span style="color:#e0bd52;">Logout?</span></h2>
        <p id="logoutConfirmationMessage">Anda akan keluar dari dashboard administrator. Pastikan pekerjaan Anda sudah selesai.</p>
        <div class="logout-confirmation__actions">
            <button class="logout-confirmation__button logout-confirmation__button--cancel" id="cancelLogout" type="button">Batal</button>
            <button class="logout-confirmation__button logout-confirmation__button--confirm" id="confirmLogout" type="button">Ya, Logout</button>
        </div>
    </section>
</div>

<script>
    (function () {
        const dialog = document.getElementById('logoutConfirmation');
        const cancelButton = document.getElementById('cancelLogout');
        const confirmButton = document.getElementById('confirmLogout');
        let pendingForm = null;
        let pendingTrigger = null;

        document.querySelectorAll('.js-logout-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                pendingForm = form;
                pendingTrigger = event.submitter;
                dialog.classList.add('is-open');
                dialog.setAttribute('aria-hidden', 'false');
                cancelButton.focus();
            });
        });

        function closeDialog() {
            dialog.classList.remove('is-open');
            dialog.setAttribute('aria-hidden', 'true');
            pendingForm = null;
            pendingTrigger?.focus();
            pendingTrigger = null;
        }

        cancelButton.addEventListener('click', closeDialog);

        dialog.addEventListener('click', function (event) {
            if (event.target === dialog) {
                closeDialog();
            }
        });

        confirmButton.addEventListener('click', function () {
            if (pendingForm) {
                pendingForm.submit();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (!dialog.classList.contains('is-open')) {
                return;
            }

            if (event.key === 'Escape') {
                closeDialog();
                return;
            }

            if (event.key === 'Tab') {
                const shiftTabOnCancel = event.shiftKey && document.activeElement === cancelButton;
                const tabOnConfirm = !event.shiftKey && document.activeElement === confirmButton;

                if (shiftTabOnCancel || tabOnConfirm) {
                    event.preventDefault();
                    (shiftTabOnCancel ? confirmButton : cancelButton).focus();
                }
            }
        });
    })();
</script>
