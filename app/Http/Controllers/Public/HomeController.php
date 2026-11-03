<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Berita;
use App\Models\Event;
use App\Models\Loker;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public const CACHE_KEY = 'home:data';

    public function __invoke(): View
    {
        $data = Cache::remember(self::CACHE_KEY, now()->addMinutes(10), fn () => [
            'slides' => Berita::slider()->latest('id')->limit(4)->get()
                ->concat(Event::slider()->latest('id')->limit(4)->get())
                ->sortByDesc('created_at')->values(),
            'beritaTerbaru' => Berita::latest('id')->limit(6)->get(),
            'pengumumanTerbaru' => Pengumuman::latest('id')->limit(5)->get(),
            'agendaTerdekat' => Agenda::upcoming()->limit(4)->get()
                ->whenEmpty(fn () => Agenda::latest('date')->limit(4)->get()),
            // Lowongan yang masih dibuka (batas terdekat dulu); jika tidak ada, tampilkan yang terbaru
            'lowonganTerbaru' => Loker::with('company.user:id,foto')->open()
                ->orderBy('tanggal_loker')->limit(6)->get()
                ->whenEmpty(fn () => Loker::with('company.user:id,foto')->latest('tanggal_loker')->limit(6)->get()),
        ]);

        return view('home.index', $data);
    }
}
