<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/** Akun dengan users.suspend = 'true' (biasanya company) dikeluarkan. */
class EnsureNotSuspended
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isSuspended()) {
            Auth::logout();
            $request->session()->invalidate();

            return redirect()->route('login')
                ->withErrors(['login' => 'Akun Anda dinonaktifkan. Hubungi Admin UPA Perkasa.']);
        }

        return $next($request);
    }
}
