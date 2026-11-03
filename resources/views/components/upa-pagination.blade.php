{{--
    Pagination bergaya desain UPA untuk LengthAwarePaginator.
    @include('components.upa-pagination', ['paginator' => $berita, 'label' => 'Berita'])
--}}
@if ($paginator->total() > 0)
<div class="upa-pagination-bar" data-aos="fade-up">
    <span class="upa-pagination-bar__info">
        Menampilkan {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} dari {{ number_format($paginator->total(), 0, ',', '.') }} {{ $label }}
    </span>
    @if ($paginator->hasPages())
        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();
            $window = collect(range(max(1, $current - 2), min($last, $current + 2)));
        @endphp
        <ul class="upa-pagination">
            <li>
                <a href="{{ $paginator->previousPageUrl() ?? '#' }}" class="upa-pagination__prev {{ $paginator->onFirstPage() ? 'disabled' : '' }}" aria-label="Halaman sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
            <li>
                <div class="upa-pagination__numbers">
                    @if ($window->first() > 1)
                        <a href="{{ $paginator->url(1) }}" class="upa-pagination__item">1</a>
                        @if ($window->first() > 2)<span class="upa-pagination__item disabled">…</span>@endif
                    @endif
                    @foreach ($window as $p)
                        <a href="{{ $paginator->url($p) }}" class="upa-pagination__item {{ $p === $current ? 'active' : '' }}" @if ($p === $current) aria-current="page" @endif>{{ $p }}</a>
                    @endforeach
                    @if ($window->last() < $last)
                        @if ($window->last() < $last - 1)<span class="upa-pagination__item disabled">…</span>@endif
                        <a href="{{ $paginator->url($last) }}" class="upa-pagination__item">{{ $last }}</a>
                    @endif
                </div>
            </li>
            <li>
                <a href="{{ $paginator->nextPageUrl() ?? '#' }}" class="upa-pagination__next {{ $paginator->hasMorePages() ? '' : 'disabled' }}" aria-label="Halaman berikutnya">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        </ul>
    @endif
</div>
@endif
