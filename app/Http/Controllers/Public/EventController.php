<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    public function show(string $slug): View
    {
        $event = Event::whereSlug($slug)->firstOrFail();
        $lainnya = Event::whereKeyNot($event->id)->latest('id')->limit(3)->get();

        return view('publikasi.event-detail', compact('event', 'lainnya'));
    }
}
