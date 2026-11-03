{{-- Kartu lowongan (beranda). Parameter: $loker (App\Models\Loker, relasi company.user dimuat) --}}
@php
    $company = $loker->company;
    $statusLabel = ['dibuka' => 'Dibuka', 'segera_berakhir' => 'Segera Berakhir', 'ditutup' => 'Ditutup'][$loker->status];
@endphp
<div class="upa-vacancy-card upa-card h-100 position-relative">
    <div class="upa-vacancy-card__top">
        <div class="upa-vacancy-card__logo">
            @if ($company?->logo_url)
                <img src="{{ $company->logo_url }}" alt="Logo {{ $company->company_name }}" loading="lazy">
            @elseif ($company)
                <span class="fw-bold">{{ $company->initials }}</span>
            @else
                <i class="bi bi-building"></i>
            @endif
        </div>

        <div class="upa-vacancy-card__info">
            <h5 class="upa-vacancy-card__title">{{ \Illuminate\Support\Str::limit($loker->name_loker, 60) }}</h5>
            <p class="upa-vacancy-card__company">{{ $company?->company_name }}</p>
            @if (filled(trim((string) $loker->lokasi, ' -')))
                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ \Illuminate\Support\Str::limit($loker->lokasi, 40) }}</small>
            @endif
        </div>

        <span class="upa-badge upa-badge-open">{{ $statusLabel }}</span>
    </div>

    <hr class="upa-vacancy-card__divider">
    <div class="d-flex justify-content-between align-items-center">
        <small class="upa-vacancy-card__deadline">Batas: {{ $loker->tanggal_loker?->translatedFormat('d M Y') ?? '-' }}</small>
        <a href="{{ route('publikasi.lowongan-detail', $loker->route_slug) }}" class="upa-vacancy-card__link stretched-link">
            Detail <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</div>
