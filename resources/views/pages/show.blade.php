{{--
    Halaman yang isinya dari tabel `pages` (diedit Admin Perkasa lewat menu Pages).
    Dipakai untuk Profil, Report, PPID, dan /halaman/{slug}.
    Parameter: $pages (Collection<Page>), $title, $group ('Profil'|'Report'|'PPID'|null), $stacked (opsional)
--}}
@extends('layouts.app')
@section('title', $title)
@section('meta_description', $pages->first()?->excerpt)

@php
    $routeName = request()->route()?->getName();
    $groupUrl = match ($group) {
        'Profil' => route('profile.sejarah'),
        'Report' => route('report.laporan-tahunan'),
        'PPID' => route('ppid.tentang'),
        default => null,
    };
@endphp

@section('content')
@include('components.breadcrumb', ['crumbs' => array_values(array_filter([
    ['label' => 'Beranda', 'url' => route('home')],
    $group ? ['label' => $group, 'url' => $groupUrl] : null,
    ['label' => $title],
]))])

@include('components.page-hero', ['variant' => 'bubble', 'title' => $title])

<section class="upa-section pt-0">
    <div class="container px-3 px-lg-5" style="max-width:1080px;">

        @if ($group === 'PPID')
            @include('components.ppid-tabs', ['active' => \Illuminate\Support\Str::after($routeName, 'ppid.')])
        @elseif ($group === 'Report')
            @include('components.report-tabs', ['active' => [
                'report.laporan-tahunan' => 'tahunan',
                'report.tracer-study' => 'tracer',
                'report.capaian-iku1' => 'iku1',
            ][$routeName] ?? ''])
        @endif

        @forelse ($pages as $page)
            <article class="upa-page-block" data-aos="fade-up">
                @if ($pages->count() > 1)
                    <h2 class="upa-page-block__title">{{ $page->title }}</h2>
                @endif
                <div class="upa-prose">{!! $page->body_html !!}</div>
            </article>
        @empty
            @include('components.empty-state', ['text' => 'Konten halaman ini belum diisi.'])
        @endforelse

        <p class="text-muted small mt-4 mb-0">
            Terakhir diperbarui {{ $pages->max('updated_at')?->translatedFormat('d F Y') }}
        </p>
    </div>
</section>
@endsection
