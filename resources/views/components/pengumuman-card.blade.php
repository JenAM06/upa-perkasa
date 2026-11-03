{{-- Kartu pengumuman (halaman daftar). Parameter: $pengumuman (App\Models\Pengumuman) --}}
@php $url = route('publikasi.pengumuman-detail', $pengumuman->route_slug); @endphp
<article class="upa-pengumuman-card upa-card">
    <div class="upa-pengumuman-card__badges">
        <span class="upa-badge upa-badge-yellow">RESMI</span>
        <span class="upa-pengumuman-card__date"><i class="bi bi-calendar3"></i>{{ $pengumuman->created_at?->translatedFormat('d F Y') }}</span>
    </div>

    <a href="{{ $url }}" class="upa-pengumuman-card__link">
        Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
    </a>

    <h3 class="upa-pengumuman-card__title">
        <a href="{{ $url }}" class="stretched-link">{{ $pengumuman->title }}</a>
    </h3>

    <p class="upa-pengumuman-card__excerpt">{{ $pengumuman->excerpt }}</p>
</article>
