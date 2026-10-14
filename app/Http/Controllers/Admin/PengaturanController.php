<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->role === 'admin', 403);

        return view('admin.pengaturan.index', [
            'administrator' => $request->user(),
        ]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin', 403);

        $messages = [
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ];
        $rules = [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $recoveryVerifiedAt = $request->session()->get('password_recovery_verified_at');
        $passwordRecoveryIsRecent = is_numeric($recoveryVerifiedAt)
            && (int) $recoveryVerifiedAt >= now()->subMinutes(15)->timestamp;

        if (! $passwordRecoveryIsRecent) {
            $request->session()->forget('password_recovery_verified_at');
            $rules['current_password'] = ['required', 'string', 'current_password'];
        }

        $validated = $request->validate($rules, $messages);

        $request->user()->update([
            'password' => $validated['password'],
        ]);
        $request->session()->forget('password_recovery_verified_at');

        return redirect()
            ->route('admin.pengaturan.index')
            ->with('success', 'Kata sandi berhasil diperbarui.');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin', 403);

        $validated = $request->validate([
            'nip' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'nip')->ignore($request->user()->getKey()),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($request->user()->getKey()),
            ],
        ], [
            'nip.unique' => 'NIP tersebut sudah digunakan oleh akun lain.',
            'email.unique' => 'Email tersebut sudah digunakan oleh akun lain.',
        ]);

        $request->user()->update($validated);

        return redirect()
            ->route('admin.pengaturan.index')
            ->with('profile_success', 'Data administrator berhasil diperbarui.');
    }

    public function resetSystem(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin', 403);

        $defaultConnection = config('database.default');
        $sessionConnection = config('session.connection') ?? $defaultConnection;
        $cacheConnection = config('cache.stores.database.connection') ?? $defaultConnection;
        $cacheLockConnection = config('cache.stores.database.lock_connection') ?? $cacheConnection;
        $cacheTable = config('cache.stores.database.table', 'cache');
        $tablesByConnection = [
            $defaultConnection => ['tamus', 'password_reset_tokens'],
        ];
        $tablesByConnection[$sessionConnection][] = config('session.table', 'sessions');
        $tablesByConnection[$cacheConnection][] = $cacheTable;
        $tablesByConnection[$cacheLockConnection][] = config('cache.stores.database.lock_table')
            ?? $cacheTable.'_locks';

        foreach ($tablesByConnection as $connection => $tables) {
            DB::connection($connection)->transaction(function () use ($connection, $tables): void {
                $database = DB::connection($connection);

                foreach (array_unique($tables) as $table) {
                    $database->table($table)->delete();
                }
            });
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'Reset sistem berhasil. Seluruh sesi telah dihapus; silakan masuk kembali.');
    }
}
