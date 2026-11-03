{{-- Kartu berita (beranda). Parameter: $berita (App\Models\Berita) --}}
<article class="upa-news-card upa-card h-100 position-relative">
    <div class="upa-news-card__media">
        <img src="{{ $berita->image_url }}" alt="{{ $berita->judul }}" loading="lazy">
        <span class="upa-news-card__date">{{ $berita->created_at?->translatedFormat('d M Y') }}</span>
    </div>
    <div class="p-3 p-lg-4">
        <h5 class="upa-news-card__title">{{ $berita->judul }}</h5>
        <p class="upa-news-card__excerpt">{{ $berita->excerpt }}</p>
        <a href="{{ route('publikasi.berita-detail', $berita->route_slug) }}" class="upa-news-card__link stretched-link">
            Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</article>
