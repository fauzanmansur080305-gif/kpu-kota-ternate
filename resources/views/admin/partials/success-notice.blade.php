@if($message)
    <div class="admin-success-notice" role="status" aria-live="polite" data-auto-dismiss="30000">
        <span class="admin-success-notice__icon" aria-hidden="true"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m5 12 4 4L19 6"/></svg></span>
        <span>{{ $message }}</span>
        <button class="admin-success-notice__dismiss" type="button" aria-label="Tutup pesan"><svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="m6 6 12 12M18 6 6 18"/></svg></button>
    </div>
@endif
