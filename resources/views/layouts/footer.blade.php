{{--
    Footer - UPA Perkasa Unmul
    Reusable via @include('layouts.footer') from app.blade.php
--}}
{{-- $footerLinks, $footerSocial, $contact dari App\View\Composers\NavigationComposer (config/perkasa.php) --}}

<footer class="upa-footer">
    <div class="upa-footer__glow upa-footer__glow--a"></div>
    <div class="upa-footer__glow upa-footer__glow--b"></div>

    <div class="container-fluid px-3 px-lg-5 pt-5 pb-3">
        <div class="row g-4 g-lg-5">
            <div class="col-12 col-lg-3 mb-2">
                <div class="upa-footer__brand">
                    <img src="{{ asset('img/logo/logo-icon.png') }}" alt="Logo UPA Perkasa Unmul" class="upa-footer__logo">
                    <h3 class="upa-footer__title">UPA PERKASA UNMUL</h3>
                </div>
                <p class="upa-footer__desc">
                    Unit Penunjang Akademik Pengembangan Karir dan Kewirausahaan Universitas Mulawarman.
                    Berkomitmen menjembatani lulusan dengan industri melalui data yang akurat dan terpercaya.
                </p>
            </div>

            <div class="col-6 col-lg-2 upa-footer__col--links">
                <h6 class="upa-footer__heading">LINK <span class="upa-footer__heading--underline">TERKAIT</span></h6>
                <ul class="upa-footer__list">
                    @foreach ($footerLinks as $link)
                        <li><a target="_blank" href="{{ $link['url'] }}"><i class="bi bi-chevron-right upa-footer__list-caret"></i>{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="upa-footer__heading">SOSI<span class="upa-footer__heading--underline">AL MEDIA</span></h6>
                <p class="upa-footer__social-lead">Ikuti kabar &amp; kegiatan kami</p>
                <div class="upa-footer__social-icons">
                    @foreach ($footerSocial as $social)
                        <a target="_blank" href="{{ $social['url'] }}" class="upa-footer__social-btn" aria-label="{{ $social['label'] }}" title="{{ $social['label'] }}">
                            <i class="bi {{ $social['icon'] }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="upa-footer__heading">CONTA<span class="upa-footer__heading--underline">CTS</span></h6>
                <ul class="upa-footer__list">
                    <li class="upa-footer__icon-row">
                        <i class="bi bi-people-fill"></i>
                        <div class="upa-footer__icon-row-content">
                            <span class="upa-footer__label">Tracer Study</span>
                            <a href="{{ route('profile.panduan-tracer-study') }}#konsultasi">Hubungi Admin</a>
                        </div>
                    </li>
                    <li class="upa-footer__icon-row">
                        <i class="bi bi-envelope"></i>
                        <div class="upa-footer__icon-row-content">
                            @foreach ($contact['emails'] as $email)
                                <a href="mailto:{{ $email }}">{{ $email }}</a>
                            @endforeach
                        </div>
                    </li>
                    <li class="upa-footer__icon-row">
                        <i class="bi bi-whatsapp"></i>
                        <div class="upa-footer__icon-row-content">
                            <a href="https://wa.me/62{{ ltrim(preg_replace('/\D/', '', $contact['whatsapp']), '0') }}" target="_blank" rel="noopener">{{ $contact['whatsapp_label'] }}</a>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="col-6 col-lg-3">
                <h6 class="upa-footer__heading">ADDR<span class="upa-footer__heading--underline">ESS</span></h6>
                <ul class="upa-footer__list">
                    <li class="upa-footer__icon-row">
                        <i class="bi bi-geo-alt"></i>
                        <a href="{{ $contact['maps_url'] }}" target="_blank" rel="noopener">{{ $contact['address'] }}</a>
                        <span></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="upa-footer__bottom">
        <div class="container-fluid px-3 px-lg-5 pt-2 pb-3 text-center">
            <small>&copy; {{ date('Y') }} UPA Perkasa Universitas Mulawarman. All Rights Reserved.</small>
        </div>
    </div>
</footer>