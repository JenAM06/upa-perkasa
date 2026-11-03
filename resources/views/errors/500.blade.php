{{-- Sengaja tidak memakai layout utama: navbar membaca database, yang mungkin sedang bermasalah. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terjadi kesalahan | UPA Perkasa Unmul</title>
    <style>
        body { font-family: system-ui, sans-serif; display: grid; place-items: center; min-height: 100vh; margin: 0; background: #f4f7f3; color: #1d2a1d; }
        main { text-align: center; padding: 2rem; }
        h1 { color: #004d00; font-size: 3rem; margin: 0 0 .5rem; }
        a { color: #004d00; }
    </style>
</head>
<body>
<main>
    <h1>500</h1>
    <p>Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.</p>
    <p><a href="{{ url('/') }}">Kembali ke Beranda</a></p>
</main>
</body>
</html>
