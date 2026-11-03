<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9, minimum-scale=0.9, maximum-scale=3.0">
    <title>@yield('title', 'UPA Perkasa Unmul') | UPA Perkasa Unmul</title>
    <meta name="description" content="@yield('meta_description', 'Unit Pelayanan Alumni dan Pengembangan Karir Universitas Mulawarman.')">
    <link rel="icon" type="image/png" href="{{ asset('img/logo/logo-icon.png') }}">
    @stack('meta')

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- AOS (Animate On Scroll) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    {{-- Swiper.js --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    {{-- Base/shared + page styles, now bundled via Vite from resources/css/app.css --}}
    @vite('resources/css/app.css')

    {{-- Page-specific styles (rarely needed now that app.css bundles everything,
        but left here in case a page needs a one-off <link>) --}}
    @stack('styles')
</head>
<body class="upa-body">

    @include('layouts.navbar')

    <main id="main-content">
        @yield('content')
    </main>

    @include('layouts.footer')

    {{-- ================================================================
            SCRIPTS — loaded bottom of body for performance
            Order: Bootstrap → Swiper → GSAP → ScrollTrigger → AOS → Lenis
                → custom shared → page-specific
        ================================================================ --}}

    {{-- Bootstrap 5 bundle --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

    {{-- Swiper.js --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- GSAP core + ScrollTrigger --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    {{-- AOS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    {{-- Lenis smooth scroll --}}
    <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.14/dist/lenis.min.js"></script>

    {{-- Shared custom scripts, now bundled via Vite from resources/js/app.js.
        @vite outputs a <script type="module" defer> tag, so it still runs
        AFTER all the CDN <script> tags above regardless of load order. --}}
    @vite('resources/js/app.js')

    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>
</html>
