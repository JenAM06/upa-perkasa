{{-- Kartu berita (halaman daftar). Parameter: $berita (App\Models\Berita) --}}
@php $url = route('publikasi.berita-detail', $berita->route_slug); @endphp
<article class="upa-berita-card upa-card h-100 position-relative">
    <a href="{{ $url }}" class="upa-berita-card__media">
        <img src="{{ $berita->image_url }}" alt="{{ $berita->judul }}" loading="lazy">
    </a>
    <div class="p-3 p-lg-4 d-flex flex-column">
        <span class="upa-berita-card__date"><i class="bi bi-calendar3"></i> {{ $berita->created_at?->translatedFormat('d F Y') }}</span>
        <h5 class="upa-berita-card__title">
            <a href="{{ $url }}">{{ $berita->judul }}</a>
        </h5>
        <p class="upa-berita-card__excerpt">{{ $berita->excerpt }}</p>
        <div class="upa-berita-card__footer mt-auto">
            <span class="upa-berita-card__views"><i class="bi bi-person"></i> {{ $berita->post_by }}</span>
            <a href="{{ $url }}" class="upa-berita-card__link stretched-link">
                Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</article>
