@extends('layouts.app')
@section('title', $berita->judul)
@section('meta_description', $berita->excerpt)
@push('meta')
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $berita->judul }}">
    <meta property="og:description" content="{{ $berita->excerpt }}">
    <meta property="og:image" content="{{ $berita->image_url }}">
@endpush

@section('content')
@include('components.breadcrumb', ['crumbs' => [
    ['label'=>'Beranda','url'=>route('home')],
    ['label'=>'Publikasi','url'=>route('publikasi.berita')],
    ['label'=>'Berita','url'=>route('publikasi.berita')],
    ['label'=>'Detail Berita'],
]])

<section class="upa-section">
    <div class="container px-3 px-lg-5" style="max-width:980px;">

        <div data-aos="fade-up">
            <h1 class="upa-berita-detail__title">{{ $berita->judul }}</h1>

            <div class="upa-berita-detail__meta">
                <span><i class="bi bi-person-fill"></i> Diposting oleh {{ $berita->post_by }}</span>
                <span><i class="bi bi-calendar3"></i> {{ $berita->created_at?->translatedFormat('d F Y') }}</span>
            </div>
        </div>

        <img src="{{ $berita->image_url }}" alt="{{ $berita->judul }}" class="upa-berita-detail__cover" data-aos="fade-up">

        <div data-aos="fade-up" data-aos-delay="80">
            <div class="upa-berita-detail__body upa-prose">
                {!! $berita->body_html !!}
            </div>

            @include('components.share-footer', ['backUrl' => route('publikasi.berita')])
        </div>

        @if ($lainnya->isNotEmpty())
            <h2 class="h5 mt-5 mb-3">Berita Lainnya</h2>
            <div class="row g-4">
                @foreach ($lainnya as $item)
                    <div class="col-md-4">@include('components.news-card', ['berita' => $item])</div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
