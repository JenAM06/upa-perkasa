@extends('layouts.app')
@section('title', 'Pengumuman Resmi')



@section('content')
@include('components.breadcrumb', ['crumbs' => [
    ['label'=>'Beranda','url'=>route('home')],
    ['label'=>'Publikasi','url'=>route('publikasi.pengumuman')],
    ['label'=>'Pengumuman'],
]])
@include('components.page-hero', [
    'variant'  => 'panel',
    'title'    => 'Pengumuman Resmi',
    'subtitle' => 'Informasi resmi, kebijakan, dan edaran penting dari UPA Perkasa Universitas Mulawarman untuk seluruh civitas akademika dan stakeholder terkait.',
    'icon'     => 'bi-megaphone',
])

<section class="upa-section pt-4" id="pengumuman-list">
    <div class="container px-3 px-lg-5">

        @include('components.list-filter', ['placeholder' => 'Cari pengumuman', 'class' => 'upa-pengumuman-filter'])

        {{-- List --}}
        <div class="d-flex flex-column gap-4 mb-4">
            @forelse ($pengumuman as $i => $item)
                <div data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                    @include('components.pengumuman-card', ['pengumuman' => $item])
                </div>
            @empty
                @include('components.empty-state', ['text' => 'Pengumuman tidak ditemukan.'])
            @endforelse
        </div>

        @include('components.upa-pagination', ['paginator' => $pengumuman, 'label' => 'Pengumuman'])
    </div>
</section>
@endsection
