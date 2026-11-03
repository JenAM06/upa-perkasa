@extends('panel.layouts.app')
@section('title', $title)

@section('content')
<div class="alert alert-info small">
    Halaman publik {{ $title }} menampilkan isi dari menu <a href="{{ route('panel.admin.pages.index', ['q' => $type === 'sejarah' ? 'sejarah' : 'visi']) }}">Halaman (Pages)</a>.
    Isian di sini disimpan di tabel lama <code>{{ $type === 'sejarah' ? 'sejarah' : 'visi_misi' }}</code>.
</div>
<form method="POST" action="{{ route('panel.admin.profile-text.update', $type) }}" class="card">
    @csrf @method('PUT')
    <div class="card-body">
        <textarea name="text" class="form-control js-editor" rows="14">{{ old('text', $record->text) }}</textarea>
    </div>
    <div class="card-footer bg-white text-end">
        <button class="btn btn-upa"><i class="bi bi-check-lg me-1"></i>Simpan</button>
    </div>
</form>
@endsection

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
    <script>$('.js-editor').summernote({ height: 420 });</script>
@endpush
