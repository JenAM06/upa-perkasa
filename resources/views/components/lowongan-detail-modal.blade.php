{{-- Modal detail lowongan. Parameter: $loker, $company, $modalId, $autoOpen --}}
<div class="modal fade upa-lowongan-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true" @if ($autoOpen) data-auto-open @endif>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" data-lenis-prevent>
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="{{ $modalId }}Label">{{ $loker->name_loker }}</h2>
                    @if ($loker->gaji)
                        <span class="upa-lowongan-tag"><i class="bi bi-cash-stack me-1"></i>{{ $loker->gaji }}</span>
                    @endif
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <div class="upa-lowongan-modal__company">
                    <div class="upa-lowongan-modal__company-logo">
                        @if ($company?->logo_url)
                            <img src="{{ $company->logo_url }}" alt="Logo {{ $company->company_name }}">
                        @else
                            <i class="bi bi-building text-muted"></i>
                        @endif
                    </div>
                    <div>
                        <p class="upa-lowongan-modal__company-name">{{ $company?->company_name }}</p>
                        <p class="upa-lowongan-modal__company-loc mb-0">{{ $loker->lokasi }}</p>
                    </div>
                </div>

                @if ($loker->file_url)
                    <div class="upa-lowongan-modal__posters">
                        <button type="button" class="upa-lowongan-modal__poster-thumb js-poster-thumb"
                                data-poster-full="{{ $loker->file_url }}" aria-label="Lihat poster ukuran penuh">
                            <img src="{{ $loker->file_url }}" alt="Poster lowongan {{ $loker->name_loker }}" loading="lazy">
                        </button>
                    </div>
                @endif

                @foreach (['fungsi' => 'Fungsi Pekerjaan', 'tingkatan_karir' => 'Tingkatan Karir', 'pengalaman' => 'Pengalaman'] as $field => $label)
                    @if (filled(strip_tags((string) $loker->{$field})))
                        <p class="upa-lowongan-modal__section-title">{{ $label }}</p>
                        <div class="upa-lowongan-modal__desc upa-prose">{!! \App\Support\HtmlContent::clean($loker->{$field}) !!}</div>
                    @endif
                @endforeach

                @if (filled(strip_tags((string) $loker->text)))
                    <p class="upa-lowongan-modal__section-title">Deskripsi dan Persyaratan</p>
                    <div class="upa-lowongan-modal__desc upa-prose">{!! $loker->body_html !!}</div>
                @endif

                <div class="upa-lowongan-modal__notice">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Hanya kandidat yang sesuai kualifikasi yang akan dihubungi untuk tahapan selanjutnya.</span>
                </div>

                <div class="upa-lowongan-modal__facts">
                    <div class="upa-lowongan-modal__fact">
                        <span class="upa-lowongan-modal__fact-label">Lokasi Kerja</span>
                        <span class="upa-lowongan-modal__fact-value">{{ $loker->lokasi }}</span>
                    </div>
                    <div class="upa-lowongan-modal__fact">
                        <span class="upa-lowongan-modal__fact-label">Batas Pendaftaran</span>
                        <span class="upa-lowongan-modal__fact-value upa-lowongan-modal__fact-value--danger">{{ $loker->tanggal_loker?->translatedFormat('d F Y') ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-upa-outline upa-lowongan-modal__btn-close" data-bs-dismiss="modal">Tutup</button>
                @if ($loker->link && $loker->status !== 'ditutup')
                    <a href="{{ $loker->link }}" target="_blank" rel="noopener" class="btn btn-upa-primary">
                        <i class="bi bi-lightning-charge-fill me-1"></i>Lamar Sekarang
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
