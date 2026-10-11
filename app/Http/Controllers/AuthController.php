<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nip' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string'],
        ]);

        $administrator = User::query()->where('nip', $credentials['nip'])->first();

        if (
            $administrator?->role === 'admin'
            && $administrator->admin_login_blocked_until?->isFuture()
        ) {
            return back()
                ->withErrors(['nip' => 'Administrator baru dapat login kembali satu menit setelah logout.'])
                ->withInput($request->only('nip'));
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['nip' => 'NIP atau kata sandi tidak sesuai.'])
                ->withInput($request->only('nip'));
        }

        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->role === 'admin') {
            $loginToken = Str::random(64);
            $updated = DB::transaction(function () use ($user, $loginToken): int {
                $administrator = User::query()
                    ->whereKey($user->getKey())
                    ->lockForUpdate()
                    ->first();

                if ($administrator === null) {
                    return 0;
                }

                if (
                    $administrator->active_login_token !== null
                    && $this->hasActiveAdminSession($administrator)
                ) {
                    return 0;
                }

                return User::query()
                    ->whereKey($administrator->getKey())
                    ->where(function ($query) {
                        $query->whereNull('admin_login_blocked_until')
                            ->orWhere('admin_login_blocked_until', '<=', now());
                    })
                    ->update([
                        'active_login_token' => $loginToken,
                        'admin_login_blocked_until' => null,
                    ]);
            });

            if ($updated !== 1) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withErrors(['nip' => 'Akun administrator sedang digunakan atau masih dalam masa tunggu.'])
                    ->withInput($request->only('nip'));
            }

            $request->session()->put('admin_login_token', $loginToken);
        }

        return redirect()->intended(
            $user->role === 'admin'
                ? route('admin.dashboard')
                : route('anggota.dashboard')
        );
    }

    private function hasActiveAdminSession(User $administrator): bool
    {
        if (config('session.driver') !== 'database' || config('session.encrypt')) {
            return true;
        }

        $minimumActivity = now()
            ->subMinutes((int) config('session.lifetime'))
            ->timestamp;
        $sessions = DB::table(config('session.table', 'sessions'))
            ->where('user_id', $administrator->getKey())
            ->where('last_activity', '>=', $minimumActivity)
            ->get(['payload']);

        foreach ($sessions as $session) {
            $payload = json_decode(base64_decode($session->payload, true) ?: '', true);
            $sessionToken = $payload['admin_login_token'] ?? null;

            if (
                is_string($sessionToken)
                && is_string($administrator->active_login_token)
                && hash_equals($administrator->active_login_token, $sessionToken)
            ) {
                return true;
            }
        }

        return false;
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user?->role === 'admin') {
            DB::table('users')
                ->where('id', $user->getKey())
                ->where('active_login_token', $request->session()->get('admin_login_token'))
                ->update([
                    'active_login_token' => null,
                    'admin_login_blocked_until' => now()->addMinute(),
                ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }
}
