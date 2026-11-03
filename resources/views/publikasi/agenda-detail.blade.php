@extends('layouts.app')
@section('title', $agenda->title)
@section('meta_description', $agenda->excerpt)

@section('content')
@include('components.breadcrumb', ['crumbs' => [
    ['label'=>'Beranda','url'=>route('home')],
    ['label'=>'Publikasi','url'=>route('publikasi.agenda')],
    ['label'=>'Agenda Kegiatan','url'=>route('publikasi.agenda')],
    ['label'=> \Illuminate\Support\Str::limit($agenda->title, 60)],
]])

@php $past = $agenda->isPast(); @endphp
<section class="upa-section pt-4">
    <div class="container px-3 px-lg-5" style="max-width:980px;">
        <div class="upa-agenda-detail" data-aos="fade-up">

            <div class="upa-agenda-detail__top">
                <div class="upa-agenda-detail__date-box">
                    <span class="upa-agenda-detail__date-month">{{ strtoupper($agenda->date?->translatedFormat('M')) }}</span>
                    <span class="upa-agenda-detail__date-day">{{ $agenda->date?->format('d') }}</span>
                </div>

                <h1 class="upa-agenda-detail__title">{{ $agenda->title }}</h1>

                <div class="upa-agenda-detail__badges">
                    <span class="upa-badge {{ $past ? 'upa-badge-danger' : 'upa-badge-yellow' }}">
                        <i class="bi {{ $past ? 'bi-x-circle-fill' : 'bi-clock-history' }}"></i>{{ $past ? 'Selesai' : 'Akan Datang' }}
                    </span>
                </div>

                <div class="upa-agenda-detail__meta">
                    <span><i class="bi bi-calendar3"></i>{{ $agenda->date?->translatedFormat('l, d F Y') }}</span>
                    <span><i class="bi bi-person-fill"></i>{{ $agenda->post_by }}</span>
                </div>
            </div>

            <h2 class="upa-agenda-detail__section-title">Deskripsi Kegiatan</h2>
            <div class="upa-agenda-detail__desc upa-prose">{!! $agenda->body_html !!}</div>

            <div class="upa-agenda-detail__footer">
                <div class="upa-agenda-detail__actions">
                    <a href="{{ route('publikasi.agenda') }}" class="btn btn-upa-outline d-inline-flex align-items-center gap-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        @if ($lainnya->isNotEmpty())
            <h2 class="h5 mt-5 mb-3">Agenda Lainnya</h2>
            <div class="d-flex flex-column gap-3">
                @foreach ($lainnya as $item)
                    @include('components.agenda-item', ['agenda' => $item])
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
