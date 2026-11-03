@extends('layouts.app')
@section('title', 'Berita Terkini')



@section('content')
@include('components.breadcrumb', ['crumbs' => [
    ['label'=>'Beranda','url'=>route('home')],
    ['label'=>'Publikasi','url'=>route('publikasi.berita')],
    ['label'=>'Berita'],
]])
@include('components.page-hero', [
    'variant'  => 'panel',
    'title'    => 'Berita Terkini',
    'subtitle' => 'Kumpulan berita, liputan kegiatan, dan informasi terbaru dari UPA Perkasa Universitas Mulawarman.',
    'icon'     => 'bi-newspaper',
])

<section class="upa-section pt-4" id="berita-list">
    <div class="container px-3 px-lg-5">

        @include('components.list-filter', ['placeholder' => 'Cari berita'])

        {{-- Grid --}}
        <div class="row g-4 mb-4">
            @forelse ($berita as $i => $item)
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                    @include('components.berita-card', ['berita' => $item])
                </div>
            @empty
                <div class="col-12">@include('components.empty-state', ['text' => 'Berita tidak ditemukan.'])</div>
            @endforelse
        </div>

        @include('components.upa-pagination', ['paginator' => $berita, 'label' => 'Berita'])
    </div>
</section>
@endsection
