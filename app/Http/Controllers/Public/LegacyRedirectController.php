<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Support\LegacyUrl;
use Illuminate\Http\RedirectResponse;

/** /perkasa2/... (URL aplikasi lama yang terindeks Google) diarahkan 301 ke rute baru. */
class LegacyRedirectController extends Controller
{
    public function __invoke(?string $path = null): RedirectResponse
    {
        $target = LegacyUrl::fromPath('/perkasa2/'.ltrim((string) $path, '/'));

        abort_unless($target, 404);

        return redirect($target, 301);
    }
}
