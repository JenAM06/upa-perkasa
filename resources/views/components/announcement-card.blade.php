{{-- Kartu pengumuman ringkas (beranda). Parameter: $pengumuman (App\Models\Pengumuman) --}}
<a href="{{ route('publikasi.pengumuman-detail', $pengumuman->route_slug) }}" class="upa-announcement-card">
    <span class="upa-announcement-card__icon">
        <i class="bi bi-megaphone-fill"></i>
    </span>
    <span class="upa-announcement-card__body">
        <small class="upa-announcement-card__date">{{ $pengumuman->created_at?->translatedFormat('d M Y') }}</small>
        <span class="upa-announcement-card__title">{{ $pengumuman->title }}</span>
    </span>
</a>
