@extends('layouts.app')
@section('title', 'Beranda')
@section('meta_description', 'UPA Perkasa Unmul, Unit Pelayanan Alumni dan Pengembangan Karir Universitas Mulawarman.')


@section('content')

    <!-- HERO — banner carousel (slide aktif di tengah, kiri-kanan mengintip) -->
<section class="upa-banner">
    <div class="swiper upa-banner-swiper">
        <div class="swiper-wrapper">
            @foreach ($slides as $slide)
                @php
                    $slideUrl = $slide instanceof \App\Models\Berita
                        ? route('publikasi.berita-detail', $slide->route_slug)
                        : route('publikasi.event-detail', $slide->route_slug);
                @endphp
                <div class="swiper-slide">
                    <a href="{{ $slideUrl }}" class="upa-banner__card d-block" title="{{ $slide->title }}">
                        <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="upa-banner__img">
                    </a>
                </div>
            @endforeach
        </div>

        <button type="button" class="upa-banner__nav upa-banner__nav--prev" aria-label="Slide sebelumnya">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button type="button" class="upa-banner__nav upa-banner__nav--next" aria-label="Slide berikutnya">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</section>

    <!-- AGENDA KEGIATAN — carousel full-width, geser & mengulang terus -->
<section class="upa-announce-section">
    <div class="container px-3 px-lg-5">
        <div class="text-center mb-4">
            <h2 class="heading-bubble upa-announce-section__title">Agenda Kegiatan</h2>
            <p class="upa-announce-section__subtitle mb-0" data-aos="fade-up">Jangan lewatkan rangkaian kegiatan UPA Perkasa mendatang</p>
        </div>
    </div>

    {{-- Swiper di luar .container supaya card mengalir penuh sampai tepi layar kiri & kanan --}}
    <div class="swiper upa-announce-swiper" data-aos="fade-up">
        <div class="swiper-wrapper">
            @foreach ($agendaTerdekat as $item)
                <div class="swiper-slide">
                    @include('components.agenda-item', ['agenda' => $item])
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination upa-announce-swiper__pagination"></div>
    </div>

    <div class="container px-3 px-lg-5">
        <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('publikasi.agenda') }}" class="btn btn-upa-light">Lihat Selengkapnya</a>
        </div>
    </div>
</section>

    <!-- LOWONGAN KERJA TERBARU -->
<section class="upa-section bg-white">
    <div class="container px-3 px-lg-5">

        @include('components.section-header', [
            'icon'     => 'bi-briefcase',
            'title'    => 'Lowongan Kerja Terbaru',
            'subtitle' => 'Temukan peluang karir yang sesuai dengan kompetensi Anda.',
            'linkText' => 'Lihat Semua Lowongan',
            'linkUrl'  => route('publikasi.lowongan'),
        ])

        <div class="row g-4 d-none d-lg-flex">
            @foreach ($lowonganTerbaru->take(3) as $i => $item)
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                    @include('components.vacancy-card', ['loker' => $item])
                </div>
            @endforeach
        </div>

        {{-- Mobile / tablet: Swiper --}}
        <div class="swiper upa-lowongan-swiper d-lg-none">
            <div class="swiper-wrapper">
                @foreach ($lowonganTerbaru as $item)
                    <div class="swiper-slide">
                        @include('components.vacancy-card', ['loker' => $item])
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination upa-lowongan-swiper__pagination mt-4"></div>
        </div>
    </div>
</section>

    <!-- BERITA TERBARU — Swiper.js (mobile slide / desktop grid) -->
<section class="upa-section upa-section--alt">
    <div class="container px-3 px-lg-5">

        @include('components.section-header', [
            'icon'     => 'bi-newspaper',
            'title'    => 'Berita Terbaru',
            'subtitle' => 'Informasi seputar pengembangan karir dan kampus.',
            'linkText' => 'Lihat Semua Berita',
            'linkUrl'  => route('publikasi.berita'),
        ])

        {{-- Desktop grid: 3 cards side by side --}}
        <div class="row g-4 d-none d-lg-flex">
            @foreach ($beritaTerbaru->take(3) as $i => $item)
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                    @include('components.news-card', ['berita' => $item])
                </div>
            @endforeach
        </div>

        {{-- Mobile / tablet: Swiper --}}
        <div class="swiper upa-berita-swiper d-lg-none">
            <div class="swiper-wrapper">
                @foreach ($beritaTerbaru as $item)
                    <div class="swiper-slide">
                        @include('components.news-card', ['berita' => $item])
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination upa-berita-swiper__pagination mt-4"></div>
        </div>
    </div>
</section>

    <!-- PENGUMUMAN -->
<section class="upa-section bg-white">
    <div class="container px-3 px-lg-5">

        @include('components.section-header', [
            'icon'     => 'bi-megaphone',
            'title'    => 'Pengumuman',
            'subtitle' => 'Informasi resmi dan update terbaru seputar kegiatan UPA PERKASA',
            'linkText' => 'Lihat Semua Pengumuman',
            'linkUrl'  => route('publikasi.pengumuman'),
        ])

        <div class="upa-pengumuman-grid" data-aos="fade-up">
            @foreach ($pengumumanTerbaru as $item)
                @include('components.announcement-card', ['pengumuman' => $item])
            @endforeach
        </div>

        {{-- Mobile / tablet: Swiper --}}
        <div class="swiper upa-agenda-swiper d-lg-none">
            <div class="swiper-wrapper">
                @foreach ($pengumumanTerbaru as $item)
                    <div class="swiper-slide">
                        @include('components.announcement-card', ['pengumuman' => $item])
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination upa-agenda-swiper__pagination mt-4"></div>
        </div>
    </div>
</section>

@endsection