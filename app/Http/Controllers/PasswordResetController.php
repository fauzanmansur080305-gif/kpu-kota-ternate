<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AdminPasswordResetCodeNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    private const CODE_EXPIRE_MINUTES = 10;

    public function showLinkRequestForm(): View
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);
        $email = Str::lower($validated['email']);

        $administratorExists = User::query()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->where('role', 'admin')
            ->first();

        if ($administratorExists !== null) {
            if (in_array(config('mail.default'), ['log', 'array'], true)) {
                return redirect()
                    ->route('password.request')
                    ->withErrors([
                        'email' => 'Pengiriman email belum aktif. Atur SMTP Gmail pada file .env sebelum meminta kode.',
                    ]);
            }

            $resetRequest = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->first();

            if (
                $resetRequest === null
                || Carbon::parse($resetRequest->created_at)->addSeconds(60)->lessThanOrEqualTo(now())
            ) {
                $code = (string) random_int(100000, 999999);

                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $email],
                    [
                        'token' => Hash::make($code),
                        'created_at' => now(),
                    ]
                );
                RateLimiter::clear('admin-password-reset-code:'.hash('sha256', $email));

                try {
                    $administratorExists->notify(new AdminPasswordResetCodeNotification($code));
                } catch (\Throwable $exception) {
                    DB::table('password_reset_tokens')->where('email', $email)->delete();

                    throw $exception;
                }
            }
        }

        return redirect()
            ->route('password.reset', ['email' => $email])
            ->with('status', 'Jika email tersebut terdaftar sebagai email administrator, kode verifikasi akan dikirim.');
    }

    public function showResetForm(Request $request): View
    {
        return view('auth.passwords.reset', [
            'email' => $request->query('email'),
        ]);
    }

    public function verifyCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = Str::lower($validated['email']);
        $attemptKey = 'admin-password-reset-code:'.hash('sha256', $email);

        if (RateLimiter::tooManyAttempts($attemptKey, 5)) {
            throw ValidationException::withMessages([
                'code' => 'Terlalu banyak percobaan. Minta kode baru dan coba lagi nanti.',
            ]);
        }

        $administrator = DB::transaction(function () use ($email, $validated): ?User {
            $administrator = User::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->where('role', 'admin')
                ->lockForUpdate()
                ->first();
            $resetRequest = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->lockForUpdate()
                ->first();

            if (
                $administrator === null
                || $resetRequest === null
                || $resetRequest->created_at === null
                || Carbon::parse($resetRequest->created_at)
                    ->addMinutes(self::CODE_EXPIRE_MINUTES)
                    ->lessThanOrEqualTo(now())
                || ! Hash::check($validated['code'], $resetRequest->token)
            ) {
                return null;
            }

            $administrator->forceFill([
                'active_login_token' => Str::random(64),
                'admin_login_blocked_until' => null,
            ])->save();

            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return $administrator;
        });

        if ($administrator === null) {
            RateLimiter::hit($attemptKey, 900);

            throw ValidationException::withMessages([
                'code' => 'Kode verifikasi tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        RateLimiter::clear($attemptKey);
        Auth::login($administrator);
        $request->session()->regenerate();
        $request->session()->put('admin_login_token', $administrator->active_login_token);
        $request->session()->put('password_recovery_verified_at', now()->timestamp);

        return redirect()
            ->route('admin.dashboard')
            ->with('password_recovery_notice', 'Verifikasi berhasil. Silakan perbarui kata sandi Anda di menu Pengaturan dalam 30 detik.');
    }
}
