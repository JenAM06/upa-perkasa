{{-- Kartu lowongan (halaman daftar). Parameter: $loker (App\Models\Loker, relasi company.user dimuat) --}}
@php
    $company = $loker->company;
    [$badgeClass, $badgeLabel] = [
        'dibuka' => ['upa-badge-open', 'DIBUKA'],
        'segera_berakhir' => ['upa-badge-warning', 'SEGERA BERAKHIR'],
        'ditutup' => ['upa-badge-danger', 'DITUTUP'],
    ][$loker->status];
@endphp
<a href="{{ route('publikasi.lowongan-detail', $loker->route_slug) }}" class="upa-lowongan-card upa-hover-lift">
    <div class="upa-lowongan-card__logo">
        @if ($company?->logo_url)
            <img src="{{ $company->logo_url }}" alt="Logo {{ $company->company_name }}" loading="lazy">
        @elseif ($company)
            <span class="fw-bold">{{ $company->initials }}</span>
        @else
            <i class="bi bi-building"></i>
        @endif
    </div>

    <div class="upa-lowongan-card__body">
        <div class="upa-lowongan-card__head">
            <div class="upa-lowongan-card__head-text">
                <h3 class="upa-lowongan-card__title">{{ $loker->name_loker }}</h3>
            </div>
            <span class="upa-badge {{ $badgeClass }} upa-lowongan-card__badge">{{ $badgeLabel }}</span>
        </div>

        @if (filled(trim((string) $loker->lokasi, ' -')))
            <div class="upa-lowongan-card__tags">
                <span class="upa-lowongan-card__meta-label"><i class="bi bi-geo-alt"></i></span>
                <span class="upa-lowongan-tag">{{ \Illuminate\Support\Str::limit($loker->lokasi, 50) }}</span>
            </div>
        @endif

        <p class="upa-lowongan-card__company">{{ $company?->company_name }}</p>

        <div class="upa-lowongan-card__spacer"></div>

        <div class="upa-lowongan-card__footer">
            <span class="upa-lowongan-card__posted"><i class="bi bi-calendar3"></i>Batas: {{ $loker->tanggal_loker?->translatedFormat('d M Y') ?? '-' }}</span>
            <span class="upa-lowongan-card__link">Lihat Selengkapnya <i class="bi bi-arrow-right ms-1"></i></span>
        </div>
    </div>
</a>
