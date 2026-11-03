@extends('panel.layouts.app')
@section('title', ($record->exists ? 'Ubah ' : 'Tambah ').$def['title'])

@php
    $hasEditor = collect($def['fields'])->contains(fn ($f) => $f['type'] === 'editor');
    $action = $record->exists ? route($def['route'].'.update', $record) : route($def['route'].'.store');
    $fields = $record->exists ? $def['fields'] : array_merge($def['createFields'], $def['fields']);
@endphp

@section('content')
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="card">
    @csrf
    @if ($record->exists) @method('PUT') @endif

    <div class="card-body">
        @foreach ($fields as $name => $field)
            @php
                $value = old($name, $record->{$name} ?? null);
                if ($value instanceof \DateTimeInterface) { $value = $value->format('Y-m-d'); }
                $required = in_array('required', $field['rules'] ?? [], true) && ! (in_array($field['type'], ['image', 'file']) && $record->exists);
            @endphp
            <div class="mb-3">
                @if ($field['type'] !== 'toggle')
                    <label for="f-{{ $name }}" class="form-label fw-medium">{{ $field['label'] }} @if ($required)<span class="text-danger">*</span>@endif</label>
                @endif

                @switch($field['type'])
                    @case('editor')
                        <textarea name="{{ $name }}" id="f-{{ $name }}" class="form-control js-editor" rows="12">{{ $value }}</textarea>
                        @break
                    @case('textarea')
                        <textarea name="{{ $name }}" id="f-{{ $name }}" class="form-control" rows="4">{{ $value }}</textarea>
                        @break
                    @case('image')
                    @case('file')
                        @php $url = \App\Support\LegacyStorage::url($field['folder'], $record->{$name} ?? null); @endphp
                        @if ($url)
                            <div class="mb-2">
                                @if ($field['type'] === 'image')
                                    <img src="{{ $url }}" alt="" style="max-height:140px" class="rounded border">
                                @else
                                    <a href="{{ $url }}" target="_blank" rel="noopener">{{ $record->{$name} }}</a>
                                @endif
                            </div>
                        @endif
                        <input type="file" name="{{ $name }}" id="f-{{ $name }}" class="form-control" @if ($field['type'] === 'image') accept="image/*" @endif>
                        @if ($record->exists)<div class="form-text">Kosongkan jika tidak diganti.</div>@endif
                        @break
                    @case('toggle')
                        <div class="form-check form-switch">
                            <input type="checkbox" name="{{ $name }}" id="f-{{ $name }}" value="1" class="form-check-input"
                                   @checked(old($name, $record->{$name} ?? null) === ($field['on'] ?? 'true') || old($name) === '1')>
                            <label for="f-{{ $name }}" class="form-check-label">{{ $field['label'] }}</label>
                        </div>
                        @break
                    @case('select')
                        <select name="{{ $name }}" id="f-{{ $name }}" class="form-select">
                            <option value="">Pilih</option>
                            @foreach (value($field['options']) as $optValue => $optLabel)
                                <option value="{{ $optValue }}" @selected((string) $value === (string) $optValue)>{{ $optLabel }}</option>
                            @endforeach
                        </select>
                        @break
                    @case('remote-select')
                        <div class="position-relative js-remote-select" data-source="{{ route($field['source']) }}">
                            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                            <input type="text" id="f-{{ $name }}" class="form-control" autocomplete="off" placeholder="Ketik untuk mencari"
                                   value="{{ old($name.'_label', $record->exists ? ($field['display'])($record) : '') }}" name="{{ $name }}_label">
                            <div class="dropdown-menu w-100"></div>
                        </div>
                        @break
                    @default
                        <input type="{{ ['date' => 'date', 'url' => 'url', 'number' => 'number', 'email' => 'email', 'password' => 'password'][$field['type']] ?? 'text' }}"
                               name="{{ $name }}" id="f-{{ $name }}" class="form-control"
                               value="{{ $field['type'] === 'password' ? '' : $value }}">
                @endswitch

                @isset($field['help'])<div class="form-text">{{ $field['help'] }}</div>@endisset
            </div>
        @endforeach
    </div>

    <div class="card-footer bg-white d-flex gap-2 justify-content-end">
        <a href="{{ route($def['route'].'.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-upa"><i class="bi bi-check-lg me-1"></i>Simpan</button>
    </div>
</form>
@endsection

@if ($hasEditor)
    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
        <script>
            // Editor sama dengan aplikasi lama (Summernote) agar konten lama tetap utuh saat disunting
            $('.js-editor').summernote({
                height: 380,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['codeview', 'fullscreen']],
                ],
            });
        </script>
    @endpush
@endif

@push('scripts')
<script>
document.querySelectorAll('.js-remote-select').forEach(function (box) {
    var hidden = box.querySelector('input[type=hidden]');
    var input = box.querySelector('input[type=text]');
    var menu = box.querySelector('.dropdown-menu');
    var timer;
    input.addEventListener('input', function () {
        hidden.value = '';
        clearTimeout(timer);
        timer = setTimeout(function () {
            fetch(box.dataset.source + '?q=' + encodeURIComponent(input.value), { headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (items) {
                    menu.innerHTML = '';
                    items.forEach(function (it) {
                        var a = document.createElement('button');
                        a.type = 'button';
                        a.className = 'dropdown-item';
                        a.textContent = it.text;
                        a.addEventListener('click', function () {
                            hidden.value = it.id; input.value = it.text; menu.classList.remove('show');
                        });
                        menu.appendChild(a);
                    });
                    menu.classList.toggle('show', items.length > 0);
                });
        }, 250);
    });
    document.addEventListener('click', function (e) { if (!box.contains(e.target)) menu.classList.remove('show'); });
});
</script>
@endpush
