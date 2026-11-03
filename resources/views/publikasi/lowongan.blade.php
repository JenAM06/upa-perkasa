@extends('layouts.app')
@section('title', 'Lowongan Pekerjaan')



@section('content')
@include('components.breadcrumb', ['crumbs' => [
    ['label'=>'Beranda','url'=>route('home')],
    ['label'=>'Publikasi','url'=>route('publikasi.lowongan')],
    ['label'=>'Lowongan Pekerjaan'],
]])
@include('components.page-hero', [
    'variant'  => 'panel',
    'title'    => 'Lowongan Pekerjaan',
    'subtitle' => 'Temukan peluang karir profesional dan magang yang sesuai dengan kompetensi Anda.',
    'icon'     => 'bi-briefcase',
])

<section class="upa-section pt-4" id="lowongan-list">
    <div class="container px-3 px-lg-5">

        <form method="GET" class="upa-lowongan-filter mb-4" data-aos="fade-up" data-autosubmit>
            <div class="upa-lowongan-filter__search">
                <i class="bi bi-search"></i>
                <input type="text" name="q" placeholder="Cari posisi, lokasi, atau perusahaan" value="{{ request('q') }}" aria-label="Cari lowongan">
            </div>
            <select name="status" aria-label="Status lowongan">
                <option value="">Status Lowongan</option>
                @foreach (['dibuka' => 'Sedang Dibuka', 'segera_berakhir' => 'Segera Berakhir', 'ditutup' => 'Telah Tutup'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
        <p class="text-muted small mb-3" data-aos="fade-up">{{ $jumlahDibuka }} lowongan sedang dibuka</p>

        {{-- Grid --}}
        <div class="row g-4 mb-4" id="lowongan-grid">
            @forelse ($lowongan as $i => $item)
                <div class="col-lg-6 upa-lowongan-grid-item" data-aos="fade-up" data-aos-delay="{{ ($i % 4) * 80 }}">
                    @include('components.lowongan-card', ['loker' => $item])
                </div>
            @empty
                <div class="col-12">@include('components.empty-state', ['text' => 'Lowongan tidak ditemukan.'])</div>
            @endforelse
        </div>

        @include('components.upa-pagination', ['paginator' => $lowongan, 'label' => 'Lowongan Pekerjaan'])
    </div>
</section>
@include('components.list-filter-script')
@endsection
