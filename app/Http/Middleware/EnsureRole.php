<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Pemakaian: ->middleware('role:admin_perkasa') atau 'role:superadmin|admin_perkasa' */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        abort_unless($request->user()?->hasRole($roles), 403, 'Anda tidak memiliki akses ke halaman ini.');

        return $next($request);
    }
}
