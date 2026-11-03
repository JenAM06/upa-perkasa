{{-- Baris lowongan di halaman perusahaan. Parameter: $loker, $company, $aosDelay, $autoOpen --}}
@php
    [$statusClass, $statusLabel] = [
        'dibuka' => ['upa-badge-open', 'Sedang Dibuka'],
        'segera_berakhir' => ['upa-badge-warning', 'Segera Berakhir'],
        'ditutup' => ['upa-badge-danger', 'Telah Tutup'],
    ][$loker->status];
    $modalId = 'lowonganModal-'.$loker->id;
@endphp

<div class="upa-vacancy-item" data-aos="fade-up" data-aos-delay="{{ $aosDelay ?? 0 }}">
    <div class="upa-vacancy-item__body">
        <div class="upa-vacancy-item__status">
            <span class="upa-badge {{ $statusClass }}">{{ $statusLabel }}</span>
            <span class="upa-vacancy-item__posted">Diposting: {{ $loker->created_at?->diffForHumans() }}</span>
        </div>
        <div class="upa-vacancy-item__actions">
            @if ($loker->link && $loker->status !== 'ditutup')
                <a href="{{ $loker->link }}" target="_blank" rel="noopener" class="btn btn-upa-primary">
                    <i class="bi bi-lightning-charge-fill me-1"></i>Lamar Sekarang
                </a>
            @endif
            <button type="button" class="btn btn-upa-outline" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                Lihat Detail
            </button>
        </div>

        <h3 class="upa-vacancy-item__title">{{ $loker->name_loker }}</h3>

        @if ($loker->tingkatan_karir)
            <div class="upa-vacancy-item__tags">
                <span class="upa-lowongan-tag">{{ \Illuminate\Support\Str::limit(strip_tags($loker->tingkatan_karir), 40) }}</span>
            </div>
        @endif
    </div>

    <div class="upa-vacancy-item__divider"></div>

    <div class="upa-vacancy-item__footer">
        <div class="upa-vacancy-item__meta">
            <span class="upa-vacancy-item__deadline"><i class="bi bi-calendar-event"></i>Batas Waktu: {{ $loker->tanggal_loker?->translatedFormat('d F Y') ?? '-' }}</span>
            <span><i class="bi bi-geo-alt me-1"></i>{{ \Illuminate\Support\Str::limit($loker->lokasi, 40) }}</span>
        </div>
        <span class="upa-vacancy-item__salary"><i class="bi bi-cash-stack"></i>{{ $loker->gaji }}</span>
    </div>
</div>

@include('components.lowongan-detail-modal', ['loker' => $loker, 'company' => $company, 'modalId' => $modalId, 'autoOpen' => $autoOpen ?? false])
