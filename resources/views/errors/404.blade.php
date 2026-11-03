@extends('layouts.app')
@section('title', 'Halaman tidak ditemukan')

@section('content')
<section class="upa-section text-center">
    <div class="container px-3" style="max-width:640px;">
        <h1 class="heading-bubble mb-3">404</h1>
        <p class="lead mb-4">Halaman yang Anda cari tidak ditemukan atau sudah dipindahkan.</p>
        <a href="{{ route('home') }}" class="btn btn-upa-primary">Kembali ke Beranda</a>
    </div>
</section>
@endsection
