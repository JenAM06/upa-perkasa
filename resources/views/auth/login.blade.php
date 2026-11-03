@extends('layouts.app')
@section('title', 'Login')

@section('content')
<section class="upa-section">
    <div class="container px-3" style="max-width:460px;">
        <div class="upa-card p-4 p-lg-5" data-aos="fade-up">
            <div class="text-center mb-4">
                <img src="{{ asset('img/logo/logo-icon.png') }}" alt="" width="56" height="56" class="mb-2">
                <h1 class="h4 mb-1">Masuk</h1>
                <p class="text-muted small mb-0">Mahasiswa/Alumni, Perusahaan, dan Admin UPA Perkasa</p>
            </div>

            <form method="POST" action="{{ route('login.store') }}" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="login" class="form-label">Email atau NIM</label>
                    <input type="text" name="login" id="login" value="{{ old('login') }}" autocomplete="username" autofocus
                           class="form-control @error('login') is-invalid @enderror">
                    @error('login')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Mahasiswa Unmul memakai NIM dan password SIA.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" autocomplete="current-password"
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-check mb-4">
                    <input type="checkbox" name="remember" id="remember" value="1" class="form-check-input">
                    <label for="remember" class="form-check-label">Ingat saya</label>
                </div>
                <button class="btn btn-upa-primary w-100">Masuk</button>
            </form>
        </div>
    </div>
</section>
@endsection
