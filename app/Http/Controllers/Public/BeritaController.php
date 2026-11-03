<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Support\ListFilter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $berita = ListFilter::apply(Berita::query(), $request, 'judul')
            ->latest('id')->paginate(9)->withQueryString();

        return view('publikasi.berita', compact('berita'));
    }

    public function show(string $slug): View
    {
        $berita = Berita::whereSlug($slug)->firstOrFail();
        $lainnya = Berita::whereKeyNot($berita->id)->latest('id')->limit(3)->get();

        return view('publikasi.berita-detail', compact('berita', 'lainnya'));
    }
}
