<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Support\ListFilter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function index(Request $request): View
    {
        $query = ListFilter::apply(Agenda::query(), $request, 'title', 'date');

        $agenda = match ($request->query('waktu')) {
            'akan-datang' => $query->upcoming(),
            'selesai' => $query->whereDate('date', '<', today())->latest('date'),
            default => $query->latest('date'),
        };

        $agenda = $agenda->paginate(9)->withQueryString();

        return view('publikasi.agenda', compact('agenda'));
    }

    public function show(string $slug): View
    {
        $agenda = Agenda::whereSlug($slug)->firstOrFail();
        $lainnya = Agenda::whereKeyNot($agenda->id)->latest('date')->limit(4)->get();

        return view('publikasi.agenda-detail', compact('agenda', 'lainnya'));
    }
}
