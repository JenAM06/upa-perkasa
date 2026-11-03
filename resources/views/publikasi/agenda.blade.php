@extends('layouts.app')
@section('title', 'Agenda Kegiatan')



@section('content')
@include('components.breadcrumb', ['crumbs' => [
    ['label'=>'Beranda','url'=>route('home')],
    ['label'=>'Publikasi','url'=>route('publikasi.agenda')],
    ['label'=>'Agenda Kegiatan'],
]])
@include('components.page-hero', [
    'variant'  => 'panel',
    'title'    => 'Agenda Kegiatan',
    'subtitle' => 'Jadwal kegiatan, workshop, seminar, dan pelatihan yang akan datang di lingkungan kampus Universitas Mulawarman untuk menunjang karir dan kompetensi mahasiswa serta alumni.',
    'icon'     => 'bi-calendar-check',
])

<section class="upa-section pt-4" id="agenda-list">
    <div class="container px-3 px-lg-5">

        @include('components.list-filter', ['placeholder' => 'Cari agenda kegiatan', 'class' => 'upa-agenda-filter', 'futureYear' => 1])

        <div class="d-flex gap-2 mb-4 flex-wrap" data-aos="fade-up">
            @foreach (['' => 'Semua', 'akan-datang' => 'Akan Datang', 'selesai' => 'Selesai'] as $val => $label)
                <a href="{{ request()->fullUrlWithQuery(['waktu' => $val ?: null, 'page' => null]) }}"
                   class="btn btn-sm {{ request('waktu', '') === $val ? 'btn-upa-primary' : 'btn-upa-outline' }}">{{ $label }}</a>
            @endforeach
        </div>

        {{-- List --}}
        <div class="d-flex flex-column gap-4 mb-4">
            @forelse ($agenda as $i => $item)
                <div data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                    @include('components.agenda-card', ['agenda' => $item])
                </div>
            @empty
                @include('components.empty-state', ['text' => 'Agenda tidak ditemukan.'])
            @endforelse
        </div>

        @include('components.upa-pagination', ['paginator' => $agenda, 'label' => 'Agenda Kegiatan'])
    </div>
</section>
@endsection
