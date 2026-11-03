<div class="upa-berita-detail__footer">
    <a href="{{ $backUrl }}" class="btn btn-upa-primary d-inline-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="upa-berita-detail__share">
        Bagikan:
        <button type="button" class="upa-berita-detail__share-btn" title="Bagikan" onclick="if(navigator.share){navigator.share({title:document.title,url:location.href})}">
            <i class="bi bi-share"></i>
        </button>
        <button type="button" class="upa-berita-detail__share-btn" title="Salin tautan" onclick="navigator.clipboard.writeText(location.href)">
            <i class="bi bi-clipboard"></i>
        </button>
    </div>
</div>
