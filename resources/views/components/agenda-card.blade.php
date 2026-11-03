{{-- Kartu agenda (halaman daftar). Parameter: $agenda (App\Models\Agenda) --}}
@php $url = route('publikasi.agenda-detail', $agenda->route_slug); $past = $agenda->isPast(); @endphp
<article class="upa-agenda-card upa-card">
    <div class="upa-agenda-card__badges">
        <span class="upa-badge {{ $past ? 'upa-badge-danger' : 'upa-badge-yellow' }}">
            <i class="bi {{ $past ? 'bi-x-circle-fill' : 'bi-clock-history' }}"></i>{{ $past ? 'Selesai' : 'Akan Datang' }}
        </span>
        <span class="upa-agenda-card__date"><i class="bi bi-calendar3"></i>{{ $agenda->date?->translatedFormat('l, d F Y') }}</span>
    </div>

    <a href="{{ $url }}" class="upa-agenda-card__link">
        Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
    </a>

    <h3 class="upa-agenda-card__title">
        <a href="{{ $url }}" class="stretched-link">{{ $agenda->title }}</a>
    </h3>

    <p class="upa-agenda-card__excerpt">{{ $agenda->excerpt }}</p>

    <div class="upa-agenda-card__meta">
        <span><i class="bi bi-person"></i>{{ $agenda->post_by }}</span>
    </div>
</article>
