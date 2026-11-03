@extends('panel.layouts.app')
@section('title', 'Data Alumni Lama')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3"><input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nama atau NIM"></div>
            <div class="col-md-3">
                <select name="fakultas" class="form-select">
                    <option value="">Semua Fakultas</option>
                    @foreach ($fakultas as $id => $nama)
                        <option value="{{ $id }}" @selected((int) request('fakultas') === $id)>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="verifikasi" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="1" @selected(request('verifikasi') === '1')>Terverifikasi</option>
                    <option value="0" @selected(request('verifikasi') === '0')>Belum</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-outline-secondary"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('panel.admin.alumni-lama.export', request()->query()) }}" class="btn btn-success"><i class="bi bi-download me-1"></i>Ekspor CSV</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Nama</th><th>NIM</th><th>Fakultas / Prodi</th><th>Lulus</th><th>Status</th><th>Instansi</th><th>Verifikasi</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($records as $a)
                        <tr>
                            <td>{{ $a->nama }}<div class="small text-muted">{{ $a->no_hp }}</div></td>
                            <td>{{ $a->nim }}</td>
                            <td>{{ $a->fakultas?->fakultas }}<div class="small text-muted">{{ $a->prodi?->prodi }} ({{ $a->jenjang }})</div></td>
                            <td>{{ $a->tahun_lulus }}</td>
                            <td>{{ $a->status }}</td>
                            <td>{{ $a->nama_instansi ?: $a->nama_usaha ?: $a->pt_lanjut }}</td>
                            <td>
                                <form method="POST" action="{{ route('panel.admin.alumni-lama.verify', $a) }}">
                                    @csrf
                                    <input type="hidden" name="verified" value="{{ $a->is_verified ? 0 : 1 }}">
                                    <button class="btn btn-sm {{ $a->is_verified ? 'btn-success' : 'btn-outline-secondary' }}">
                                        <i class="bi {{ $a->is_verified ? 'bi-patch-check-fill' : 'bi-patch-question' }}"></i>
                                        {{ $a->is_verified ? 'Terverifikasi' : 'Verifikasi' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('panel.admin.alumni-lama.destroy', $a) }}" onsubmit="return confirm('Hapus data {{ addslashes($a->nama) }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $records->links() }}
    </div>
</div>
@endsection
