@extends('panel.layouts.app')
@section('title', $def['title'])

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <form method="GET" class="d-flex gap-2 flex-grow-1" style="max-width:420px">
                @if ($def['search'])
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari {{ strtolower($def['title']) }}">
                    <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                @endif
            </form>
            <div class="ms-auto">
                @unless ($def['readonly'])
                    <a href="{{ route($def['route'].'.create') }}" class="btn btn-upa"><i class="bi bi-plus-lg me-1"></i>Tambah {{ $def['title'] }}</a>
                @endunless
            </div>
        </div>
        @if ($def['description'])
            <p class="text-muted small">{{ $def['description'] }}</p>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:48px">#</th>
                        @foreach (array_keys($def['columns']) as $label)<th>{{ $label }}</th>@endforeach
                        @unless ($def['readonly'])<th class="text-end" style="width:110px">Aksi</th>@endunless
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td class="text-muted">{{ $records->firstItem() + $loop->index }}</td>
                            @foreach ($def['columns'] as $column)<td>{{ $column($record) }}</td>@endforeach
                            @unless ($def['readonly'])
                                <td class="text-end text-nowrap">
                                    <a href="{{ route($def['route'].'.edit', $record) }}" class="btn btn-sm btn-outline-secondary" title="Ubah"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route($def['route'].'.destroy', $record) }}" class="d-inline"
                                          onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            @endunless
                        </tr>
                    @empty
                        <tr><td colspan="{{ count($def['columns']) + 2 }}" class="text-center text-muted py-4">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $records->links() }}
    </div>
</div>
@endsection
