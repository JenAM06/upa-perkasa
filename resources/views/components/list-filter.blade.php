{{--
    Filter daftar publik (judul, bulan, tahun). Dikirim otomatis saat pilihan berubah.
    @include('components.list-filter', ['placeholder' => 'Cari berita'])
--}}
@php $cls = $class ?? 'upa-berita-filter'; @endphp
<form method="GET" class="{{ $cls }} mb-4" data-aos="fade-up" data-autosubmit>
    <div class="{{ $cls }}__search">
        <i class="bi bi-search"></i>
        <input type="text" name="q" placeholder="{{ $placeholder ?? 'Cari' }}" value="{{ request('q') }}" aria-label="{{ $placeholder ?? 'Cari' }}">
    </div>
    <select name="bulan" aria-label="Bulan">
        <option value="">Pilih Bulan</option>
        @foreach (\App\Support\ListFilter::MONTHS as $num => $bulan)
            <option value="{{ $num }}" @selected((int) request('bulan') === $num)>{{ $bulan }}</option>
        @endforeach
    </select>
    <select name="tahun" aria-label="Tahun">
        <option value="">Pilih Tahun</option>
        @foreach (range(now()->year + ($futureYear ?? 0), 2019) as $tahun)
            <option value="{{ $tahun }}" @selected((int) request('tahun') === $tahun)>{{ $tahun }}</option>
        @endforeach
    </select>
</form>
@include('components.list-filter-script')
