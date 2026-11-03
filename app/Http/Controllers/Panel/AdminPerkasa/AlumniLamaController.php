<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Controller;
use App\Models\AlumniLama;
use App\Models\Fakultas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Data alumni lama dari form publik tracer study: filter, verifikasi, hapus, ekspor CSV. */
class AlumniLamaController extends Controller
{
    public function index(Request $request): View
    {
        return view('panel.admin-perkasa.alumni-lama', [
            'records' => $this->filtered($request)->with(['fakultas:id,fakultas', 'prodi:id,prodi'])
                ->latest('id')->paginate(20)->withQueryString(),
            'fakultas' => Fakultas::orderBy('fakultas')->pluck('fakultas', 'id'),
        ]);
    }

    public function verify(AlumniLama $alumni, Request $request): RedirectResponse
    {
        $alumni->update(['is_verified' => $request->boolean('verified')]);

        return back()->with('status', $alumni->nama.($alumni->is_verified ? ' diverifikasi.' : ' batal diverifikasi.'));
    }

    public function destroy(AlumniLama $alumni): RedirectResponse
    {
        $alumni->delete();

        return back()->with('status', 'Data alumni dihapus.');
    }

    public function export(Request $request): StreamedResponse
    {
        $columns = ['nama', 'nim', 'jenis_kelamin', 'no_hp', 'email', 'jenjang', 'tahun_lulus', 'ipk', 'status',
            'nama_instansi', 'jabatan', 'bidang_kerja', 'kota', 'kesesuaian', 'nama_usaha', 'bidang_usaha',
            'pt_lanjut', 'prodi_lanjut', 'is_verified', 'created_at'];

        return response()->streamDownload(function () use ($request, $columns) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
            fputcsv($out, array_merge(['fakultas', 'prodi'], $columns));

            $this->filtered($request)->with(['fakultas:id,fakultas', 'prodi:id,prodi'])->orderBy('id')
                ->chunk(500, function ($rows) use ($out, $columns) {
                    foreach ($rows as $row) {
                        fputcsv($out, array_merge(
                            [$row->fakultas?->fakultas, $row->prodi?->prodi],
                            array_map(fn ($c) => $row->getRawOriginal($c), $columns)
                        ));
                    }
                });
            fclose($out);
        }, 'alumni-lama-'.now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filtered(Request $request): Builder
    {
        $q = trim((string) $request->query('q'));

        return AlumniLama::query()
            ->when($q !== '', fn ($w) => $w->where(fn ($x) => $x->where('nama', 'like', "%{$q}%")->orWhere('nim', 'like', "%{$q}%")))
            ->when($request->filled('fakultas'), fn ($w) => $w->where('fakultas_id', $request->integer('fakultas')))
            ->when($request->filled('status'), fn ($w) => $w->where('status', $request->query('status')))
            ->when($request->filled('verifikasi'), fn ($w) => $w->where('is_verified', $request->query('verifikasi') === '1'));
    }
}
