<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Support\ListFilter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        $pengumuman = ListFilter::apply(Pengumuman::query(), $request, 'title')
            ->latest('id')->paginate(10)->withQueryString();

        return view('publikasi.pengumuman', compact('pengumuman'));
    }

    public function show(string $slug): View
    {
        $pengumuman = Pengumuman::whereSlug($slug)->firstOrFail();
        $lainnya = Pengumuman::whereKeyNot($pengumuman->id)->latest('id')->limit(5)->get();

        return view('publikasi.pengumuman-detail', compact('pengumuman', 'lainnya'));
    }
}
