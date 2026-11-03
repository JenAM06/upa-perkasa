@extends('panel.layouts.app')
@section('title', 'Dashboard')

@section('content')
<p class="mb-4">Selamat datang, <strong>{{ auth()->user()->name }}</strong>.</p>

@if ($stats)
    <div class="row g-3">
        @foreach ($stats as $s)
            <div class="col-sm-6 col-xl-4">
                <a href="{{ $s['url'] }}" class="card text-decoration-none h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="fs-2" style="color:var(--upa-green)"><i class="bi {{ $s['icon'] }}"></i></span>
                        <div>
                            <div class="fs-4 fw-semibold text-dark">{{ number_format($s['value'], 0, ',', '.') }}</div>
                            <div class="text-muted small">{{ $s['label'] }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@else
    {{-- Peran yang panelnya belum dipindah (tahap berikutnya PRD) --}}
    <div class="card">
        <div class="card-body">
            <h2 class="h6">Panel untuk peran Anda sedang dipindahkan ke sistem baru.</h2>
            <p class="text-muted mb-0">Peran: {{ $roles->implode(', ') ?: '-' }}.</p>
        </div>
    </div>
@endif
@endsection
