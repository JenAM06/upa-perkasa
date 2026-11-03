@extends('layouts.app')
@section('title', $event->title)
@section('meta_description', $event->excerpt)
@push('meta')
    <meta property="og:title" content="{{ $event->title }}">
    <meta property="og:image" content="{{ $event->image_url }}">
@endpush

@section('content')
@include('components.breadcrumb', ['crumbs' => [
    ['label'=>'Beranda','url'=>route('home')],
    ['label'=>'Publikasi','url'=>route('publikasi.agenda')],
    ['label'=>'Event'],
]])

<section class="upa-section">
    <div class="container px-3 px-lg-5" style="max-width:980px;">
        <div data-aos="fade-up">
            <h1 class="upa-berita-detail__title">{{ $event->title }}</h1>
            <div class="upa-berita-detail__meta">
                <span><i class="bi bi-person-fill"></i> Diposting oleh {{ $event->post_by }}</span>
                <span><i class="bi bi-calendar3"></i> {{ $event->created_at?->translatedFormat('d F Y') }}</span>
            </div>
        </div>

        <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="upa-berita-detail__cover" data-aos="fade-up">

        <div class="upa-berita-detail__body upa-prose" data-aos="fade-up">{!! $event->body_html !!}</div>

        @include('components.share-footer', ['backUrl' => route('home')])
    </div>
</section>
@endsection
