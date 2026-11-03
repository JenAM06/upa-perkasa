<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\LegacyCredentials;
use App\Services\Auth\OsmLogin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Satu halaman login untuk semua peran (pengganti portal start-session lama).
 * Kolom "Email atau NIM": admin dan company memakai email; mahasiswa memakai NIM.
 */
class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request, LegacyCredentials $credentials, OsmLogin $osm): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:191'],
            'password' => ['required', 'string'],
        ], [], ['login' => 'email atau NIM']);

        $login = trim($data['login']);
        $user = User::where('email', $login)->first();

        $authenticated = $user && $credentials->verify($user, $data['password'])
            ? $user
            : (ctype_digit($login) ? $osm->attempt($login, $data['password']) : null);

        if (! $authenticated) {
            throw ValidationException::withMessages(['login' => 'Email/NIM atau password salah.']);
        }

        if ($authenticated->isSuspended()) {
            throw ValidationException::withMessages(['login' => 'Akun Anda dinonaktifkan. Hubungi Admin UPA Perkasa.']);
        }

        Auth::login($authenticated, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('panel.home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
