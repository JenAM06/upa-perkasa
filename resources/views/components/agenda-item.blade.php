{{-- Butir agenda (beranda). Parameter: $agenda (App\Models\Agenda) --}}
<a href="{{ route('publikasi.agenda-detail', $agenda->route_slug) }}" class="upa-agenda-item">
    <div class="upa-agenda-item__date">
        <span class="upa-agenda-item__month">{{ strtoupper($agenda->date?->translatedFormat('M')) }}</span>
        <span class="upa-agenda-item__day">{{ $agenda->date?->format('d') }}</span>
    </div>
    <div class="upa-agenda-item__body">
        <p class="upa-agenda-item__title mb-1">{{ $agenda->title }}</p>
        <div class="upa-agenda-item__meta">
            <span><i class="bi bi-calendar3 me-1"></i>{{ $agenda->date?->translatedFormat('l, d F Y') }}</span>
            <span><i class="bi bi-{{ $agenda->isPast() ? 'check2-circle' : 'hourglass-split' }} me-1"></i>{{ $agenda->isPast() ? 'Selesai' : 'Akan datang' }}</span>
        </div>
    </div>
</a>
