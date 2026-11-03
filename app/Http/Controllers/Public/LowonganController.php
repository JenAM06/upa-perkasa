<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Loker;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LowonganController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));

        $lowongan = Loker::with('company.user:id,foto')
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('name_loker', 'like', "%{$q}%")
                ->orWhere('lokasi', 'like', "%{$q}%")
                ->orWhereHas('company', fn ($c) => $c->where('company_name', 'like', "%{$q}%"))))
            ->status($request->query('status'))
            ->orderByRaw('tanggal_loker < CURDATE()') // yang masih dibuka di atas
            ->latest('tanggal_loker')
            ->paginate(10)->withQueryString();

        $jumlahDibuka = Loker::open()->count();

        return view('publikasi.lowongan', compact('lowongan', 'jumlahDibuka'));
    }

    public function show(Request $request, string $slug): View
    {
        $loker = Loker::with('company.user:id,foto')->whereSlug($slug)->firstOrFail();

        // Halaman menampilkan profil perusahaan + semua lowongannya; lowongan yang dibuka tampil paling atas
        $jobs = Loker::where('company_id', $loker->company_id)->whereKeyNot($loker->id)
            ->status($request->query('status'))
            ->latest('tanggal_loker')->limit(20)->get()
            ->prepend($loker);

        return view('publikasi.lowongan-perusahaan', [
            'loker' => $loker,
            'company' => $loker->company,
            'jobs' => $jobs,
        ]);
    }
}
