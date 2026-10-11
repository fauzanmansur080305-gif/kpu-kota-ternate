@if($message)
    <div class="admin-success-notice" role="status" aria-live="polite" data-auto-dismiss="30000">
        <span class="admin-success-notice__icon" aria-hidden="true">✓</span>
        <span>{{ $message }}</span>
        <button class="admin-success-notice__dismiss" type="button" aria-label="Tutup pesan">×</button>
    </div>
@endif
