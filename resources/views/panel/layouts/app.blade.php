<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('title', 'Panel') | Panel UPA Perkasa</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo/logo-icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --upa-green: #004d00; --upa-green-light: #2e8b3d; --upa-green-soft: #e8f0e6; --panel-sidebar: 260px; }
        body { font-family: 'Poppins', sans-serif; background: #f4f7f3; font-size: .925rem; }
        .panel-sidebar { width: var(--panel-sidebar); background: var(--upa-green); color: #fff; position: fixed; inset: 0 auto 0 0; overflow-y: auto; z-index: 1030; transition: transform .2s; }
        .panel-sidebar a { color: rgba(255,255,255,.85); text-decoration: none; display: flex; gap: .65rem; align-items: center; padding: .5rem 1.25rem; border-radius: 0 999px 999px 0; margin-right: .75rem; }
        .panel-sidebar a:hover, .panel-sidebar a.active { background: rgba(255,255,255,.12); color: #fff; }
        .panel-sidebar .heading { font-size: .7rem; letter-spacing: .08em; text-transform: uppercase; opacity: .55; padding: 1rem 1.25rem .35rem; }
        .panel-main { margin-left: var(--panel-sidebar); min-height: 100vh; }
        .panel-topbar { background: #fff; border-bottom: 1px solid #e3e9e1; }
        .btn-upa { background: var(--upa-green); color: #fff; border: 0; }
        .btn-upa:hover { background: var(--upa-green-light); color: #fff; }
        .card { border: 0; box-shadow: 0 1px 3px rgba(0,0,0,.06); border-radius: 14px; }
        .table > :not(caption) > * > * { vertical-align: middle; }
        .panel-thumb { width: 64px; height: 44px; object-fit: cover; border-radius: 6px; background: #eef2ec; }
        @media (max-width: 991.98px) {
            .panel-sidebar { transform: translateX(-100%); }
            body.sidebar-open .panel-sidebar { transform: none; }
            .panel-main { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
<aside class="panel-sidebar" id="panelSidebar">
    <a href="{{ route('home') }}" class="py-3 mb-2 fw-semibold" style="border-radius:0">
        <img src="{{ asset('img/logo/logo-icon.png') }}" alt="" width="32" height="32"> UPA Perkasa
    </a>
    @foreach ($panelMenu as $item)
        @if (isset($item['heading']))
            <div class="heading">{{ $item['heading'] }}</div>
        @else
            @php
                $url = route($item['route'], $item['params'] ?? []);
                $active = isset($item['params']) || ! str_ends_with($item['route'], '.index')
                    ? url()->current() === $url
                    : request()->routeIs(\Illuminate\Support\Str::beforeLast($item['route'], '.').'.*');
            @endphp
            <a href="{{ $url }}" class="{{ $active ? 'active' : '' }}">
                <i class="bi {{ $item['icon'] }}"></i> {{ $item['label'] }}
            </a>
        @endif
    @endforeach
</aside>

<div class="panel-main">
    <header class="panel-topbar px-3 px-lg-4 py-2 d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-light d-lg-none" type="button" onclick="document.body.classList.toggle('sidebar-open')" aria-label="Menu">
            <i class="bi bi-list"></i>
        </button>
        <h1 class="h6 mb-0 flex-grow-1">@yield('title', 'Panel')</h1>
        <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text small text-muted">{{ auth()->user()->roleNames()->implode(', ') }}</span></li>
                <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">Lihat Portal</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="dropdown-item text-danger">Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <main class="p-3 p-lg-4">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
