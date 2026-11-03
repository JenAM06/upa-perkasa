@extends('layouts.app')
@section('title', $pengumuman->title)
@section('meta_description', $pengumuman->excerpt)

@section('content')
@include('components.breadcrumb', ['crumbs' => [
    ['label'=>'Beranda','url'=>route('home')],
    ['label'=>'Publikasi','url'=>route('publikasi.pengumuman')],
    ['label'=>'Pengumuman','url'=>route('publikasi.pengumuman')],
    ['label'=>'Detail Pengumuman'],
]])

<section class="upa-section pt-4">
    <div class="container px-3 px-lg-5" style="max-width:980px;">
        <div class="upa-pengumuman-detail" data-aos="fade-up">

            <span class="upa-badge upa-badge-yellow"><i class="bi bi-patch-check-fill me-1"></i>Resmi</span>

            <h1 class="upa-pengumuman-detail__title">{{ $pengumuman->title }}</h1>

            <div class="upa-pengumuman-detail__meta">
                <span><i class="bi bi-calendar3"></i>{{ $pengumuman->created_at?->translatedFormat('d F Y') }}</span>
                <span><i class="bi bi-person"></i>{{ $pengumuman->post_by }}</span>
            </div>

            {{-- Persyaratan, timeline, dan tautan lampiran ada di dalam isi HTML --}}
            <div class="upa-pengumuman-detail__body upa-prose">
                {!! $pengumuman->body_html !!}
            </div>

            <div class="upa-pengumuman-detail__footer">
                <a href="{{ route('publikasi.pengumuman') }}" class="btn btn-upa-primary d-inline-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>

                <div class="upa-pengumuman-detail__share">
                    Bagikan:
                    <button type="button" class="upa-pengumuman-detail__share-btn" title="Bagikan" onclick="if(navigator.share){navigator.share({title:document.title,url:location.href})}">
                        <i class="bi bi-share"></i>
                    </button>
                    <button type="button" class="upa-pengumuman-detail__share-btn" title="Salin tautan" onclick="navigator.clipboard.writeText(location.href)">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
            </div>
        </div>

        @if ($lainnya->isNotEmpty())
            <h2 class="h5 mt-5 mb-3">Pengumuman Lainnya</h2>
            <div class="upa-pengumuman-grid">
                @foreach ($lainnya as $item)
                    @include('components.announcement-card', ['pengumuman' => $item])
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
