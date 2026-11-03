@extends('layouts.app')
@section('title', $loker->name_loker.' - '.($company?->company_name ?? 'Lowongan'))



@section('content')
@include('components.breadcrumb', ['crumbs' => [
    ['label'=>'Beranda','url'=>route('home')],
    ['label'=>'Publikasi','url'=>route('publikasi.lowongan')],
    ['label'=>'Lowongan Pekerjaan','url'=>route('publikasi.lowongan')],
    ['label'=> $company?->company_name ?? 'Perusahaan'],
]])

<section class="upa-section pt-4">
    <div class="container px-3 px-lg-5">
        <div class="row g-4 g-lg-5">

            {{-- Sidebar --}}
            <div class="col-lg-4" data-aos="fade-up">
                <div class="upa-company-sidebar">
                    <div class="upa-company-sidebar__logo">
                        @if ($company?->logo_url)
                            <img src="{{ $company->logo_url }}" alt="Logo {{ $company->company_name }}">
                        @elseif ($company)
                            <span class="fs-3 fw-bold">{{ $company->initials }}</span>
                        @else
                            <i class="bi bi-building"></i>
                        @endif
                    </div>
                    <h1 class="upa-company-sidebar__name">{{ $company?->company_name }}</h1>
                    @if ($company?->website)
                        <a href="{{ \Illuminate\Support\Str::startsWith($company->website, 'http') ? $company->website : 'https://'.$company->website }}" target="_blank" rel="noopener" class="upa-company-sidebar__website">
                            <i class="bi bi-globe2 me-1"></i>{{ $company->website }}
                        </a>
                    @endif

                    @if (filled($company?->deskripsi))
                        <p class="upa-company-sidebar__label">DESKRIPSI</p>
                        <div class="upa-company-sidebar__desc upa-prose">{!! \App\Support\HtmlContent::clean($company->deskripsi) !!}</div>
                    @endif

                    <hr>

                    <div class="upa-company-sidebar__row">
                        <i class="bi bi-geo-alt-fill"></i>
                        <div>
                            <p class="upa-company-sidebar__row-label mb-0">Lokasi</p>
                            <p class="upa-company-sidebar__row-value mb-0">{{ $company?->alamat_pusat ?: $loker->lokasi }}</p>
                        </div>
                    </div>
                    <div class="upa-company-sidebar__row">
                        <i class="bi bi-building"></i>
                        <div>
                            <p class="upa-company-sidebar__row-label mb-0">Industri</p>
                            <p class="upa-company-sidebar__row-value mb-0">{{ $company?->bidang ?: '-' }}</p>
                        </div>
                    </div>

                    <a href="{{ route('publikasi.lowongan') }}" class="btn btn-upa-primary w-100 mt-auto">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            {{-- Job list --}}
            <div class="col-lg-8">
                <div class="upa-vacancy-list-head" data-aos="fade-up">
                    <h2 class="upa-vacancy-list-head__title">{{ $jobs->count() }} Lowongan</h2>
                    <form method="GET" class="upa-vacancy-list-head__sort" data-autosubmit>
                        <span>Filter:</span>
                        <select name="status" aria-label="Status lowongan">
                            <option value="">Pilih Status</option>
                            @foreach (['dibuka' => 'Sedang Dibuka', 'segera_berakhir' => 'Segera Berakhir', 'ditutup' => 'Telah Tutup'] as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                @foreach ($jobs as $i => $job)
                    @include('components.lowongan-item', ['loker' => $job, 'company' => $company, 'aosDelay' => min($i, 4) * 80, 'autoOpen' => $job->is($loker)])
                @endforeach
            </div>
        </div>

        <div class="upa-cta-banner mt-4 mt-lg-5 p-4 p-lg-5 d-flex flex-wrap align-items-center justify-content-between gap-3" data-aos="fade-up">
            <div>
                <h3 class="upa-cta-banner__title mb-2">Punya Pertanyaan Mengenai Perusahaan?</h3>
                <p class="upa-cta-banner__text mb-0">Tim HR kami siap menjawab detail mengenai budaya kerja dan proses rekrutmen.</p>
            </div>
            @if ($company?->no_telp)
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $company->no_telp) }}" class="upa-cta-banner__btn upa-cta-banner__btn--yellow">Hubungi Perusahaan</a>
            @endif
        </div>
    </div>
</section>

<div class="upa-poster-lightbox" id="upaPosterLightbox" aria-hidden="true">
    <button type="button" class="upa-poster-lightbox__close" id="upaPosterLightboxClose" aria-label="Tutup pratinjau poster">
        <i class="bi bi-x-lg"></i>
    </button>
    <img src="" alt="Pratinjau poster ukuran penuh" class="upa-poster-lightbox__img" id="upaPosterLightboxImg">
</div>
@include('components.list-filter-script')
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.querySelector('.upa-lowongan-modal[data-auto-open]');
    if (el && window.bootstrap) { bootstrap.Modal.getOrCreateInstance(el).show(); }
});
</script>
@endpush
@endsection