<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Controller;
use App\Models\Sejarah;
use App\Models\VisiMisi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Tabel sejarah dan visi_misi (masing-masing satu baris). Catatan: halaman publik
 * Sejarah dan Visi Misi sekarang memakai tabel pages; menu ini dipertahankan
 * karena ada di panel lama.
 */
class ProfileTextController extends Controller
{
    private const TYPES = ['sejarah' => [Sejarah::class, 'Sejarah'], 'visi-misi' => [VisiMisi::class, 'Visi dan Misi']];

    public function edit(string $type): View
    {
        [$model, $title] = self::TYPES[$type] ?? abort(404);

        return view('panel.admin-perkasa.profile-text', [
            'record' => $model::firstOrNew(),
            'title' => $title,
            'type' => $type,
        ]);
    }

    public function update(Request $request, string $type): RedirectResponse
    {
        [$model, $title] = self::TYPES[$type] ?? abort(404);
        $data = $request->validate(['text' => ['required', 'string']]);

        $model::firstOrNew()->fill($data)->save();

        return back()->with('status', $title.' berhasil disimpan.');
    }
}
