{{--
    Navbar  —  UPA Perkasa Unmul
    @include('layouts.navbar') dipanggil dari layouts/app.blade.php
--}}
{{-- Data dropdown dari App\View\Composers\NavigationComposer (tabel menus/submenus) --}}

<header class="upa-navbar sticky-top">
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container-fluid px-3 px-lg-5">

            {{-- Logo --}}
            <a class="navbar-brand upa-navbar__brand" href="{{ route('home') }}">
                <img src="{{ asset('img/logo/logo-full.png') }}" alt="UPA Perkasa Unmul">
            </a>

            {{-- Mobile toggler → custom full-screen nav (see below). Plain
                JS toggle, not Bootstrap's offcanvas component. --}}
            <button class="navbar-toggler border-0" type="button"
                    id="upaMenuToggle" aria-controls="upaMobileMenu"
                    aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Desktop nav (d-none on mobile, shown on lg+) --}}
            <div class="collapse navbar-collapse d-none d-lg-flex">
                <ul class="navbar-nav mx-auto upa-navbar__nav align-items-lg-center">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}"><span class="upa-navlink__label">Beranda</span></a>
                    </li>

                    @foreach ([
                        ['label'=>'Profil',    'items'=>$profilDropdown,   'route'=>'profile.*'],
                        ['label'=>'Publikasi', 'items'=>$publikasiDropdown,'route'=>'publikasi.*'],
                        ['label'=>'PPID',      'items'=>$ppidDropdown,     'route'=>'ppid.*'],
                        ['label'=>'Report',    'items'=>$reportDropdown,   'route'=>'report.*'],
                    ] as $menu)
                    @continue(empty($menu['items']))
                    <li class="nav-item dropdown upa-dropdown">
                        <a class="nav-link dropdown-toggle upa-dropdown__toggle {{ $menu['route'] && request()->routeIs($menu['route']) ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="upa-navlink__label">{{ $menu['label'] }}</span>
                            <i class="bi bi-chevron-down upa-dropdown__caret"></i>
                        </a>
                        <ul class="dropdown-menu upa-dropdown__menu">
                            @foreach ($menu['items'] as $item)
                                <li>
                                    <a class="dropdown-item upa-dropdown__item" href="{{ $item['url'] }}">
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tracer.index') }}"><span class="upa-navlink__label">Tracer Study</span></a>
                    </li>
                </ul>

                {{-- Desktop: login --}}
                <div class="d-flex align-items-center upa-navbar__actions">
                    <a href="{{ auth()->check() ? route('panel.home') : route('login') }}" class="btn btn-upa-primary upa-navbar__login">{{ auth()->check() ? 'Panel Saya' : 'Login' }}</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Mobile nav (full-screen) --}}
    <div class="upa-mobile-nav" id="upaMobileMenu" aria-hidden="true">
        <div class="upa-mobile-nav__header">
            <img src="{{ asset('img/logo/logo-icon.png') }}" alt="Logo UPA Perkasa" class="upa-mobile-nav__logo">
            <button type="button" class="upa-mobile-nav__close" id="upaMenuClose" aria-label="Tutup menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="upa-mobile-nav__body">

            <nav class="upa-mobile-nav__list">
                <a href="{{ route('home') }}"
                class="upa-mobile-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>

                @foreach ([
                    ['id'=>'mobileProfil',    'label'=>'Profil',    'items'=>$profilDropdown],
                    ['id'=>'mobilePublikasi', 'label'=>'Publikasi', 'items'=>$publikasiDropdown],
                    ['id'=>'mobilePpid',      'label'=>'PPID',      'items'=>$ppidDropdown],
                    ['id'=>'mobileReport',    'label'=>'Report',    'items'=>$reportDropdown],
                ] as $m)
                @continue(empty($m['items']))
                <div class="upa-mobile-accordion">
                    <button type="button" class="upa-mobile-accordion__btn"
                            data-accordion-toggle aria-expanded="false" aria-controls="{{ $m['id'] }}">
                        <span>{{ $m['label'] }}</span>
                        <i class="bi bi-chevron-down upa-mobile-accordion__chevron"></i>
                    </button>
                    <div id="{{ $m['id'] }}" class="upa-mobile-accordion__panel">
                        @foreach ($m['items'] as $item)
                            <a href="{{ $item['url'] }}" class="upa-mobile-sublink">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <a href="{{ route('tracer.index') }}" class="upa-mobile-link">Tracer Study</a>
            </nav>

            <a href="{{ auth()->check() ? route('panel.home') : route('login') }}" class="btn btn-upa-primary w-100 mt-4 upa-mobile-nav__login">{{ auth()->check() ? 'Panel Saya' : 'Login' }}</a>
        </div>
    </div>
</header>