<?php

/*
|--------------------------------------------------------------------------
| Pengaturan khusus UPA Perkasa
|--------------------------------------------------------------------------
| Semua yang tidak punya tempat di database disimpan di sini (database tidak
| boleh ditambah kolom/tabel). Ubah lewat .env bila perlu.
*/

return [

    /*
    | Halaman yang kontennya diambil dari tabel `pages` (bisa diedit Admin Perkasa
    | lewat menu Pages). Kunci = nama rute, nilai = slug di tabel pages.
    | Jika admin membuat halaman pengganti (mis. program-kerja-2027), cukup ubah slug.
    */
    'pages' => [
        'profile.sejarah' => ['slug' => 'sejarah-terbentuknya-perkasa', 'group' => 'Profil'],
        'profile.visi-misi' => ['slug' => 'visi-misi-upa-perkasa', 'group' => 'Profil'],
        'profile.tujuan-sasaran' => ['slug' => 'tujuan-sasaran', 'group' => 'Profil'],
        'profile.struktur' => ['slug' => 'struktur-fungsional-upa-perkasa-tahun-2026', 'group' => 'Profil'],
        'profile.program-kerja' => ['slug' => 'program-kerja-2026', 'group' => 'Profil'],
        'profile.logo' => ['slug' => 'arti-logo-upa-perkasa', 'group' => 'Profil'],
        'profile.panduan-tracer-study' => ['slug' => ['divisi-tracer-study', 'modul-panduan-tracer-study', 'konsultasi-tacer-study-admin'], 'group' => 'Profil'],
        'report.laporan-tahunan' => ['slug' => 'laporan-tahunan-upa-perkasa', 'group' => 'Report'],
        'report.tracer-study' => ['slug' => 'laporan-tracer-study-dan-laporan-triwulan', 'group' => 'Report'],
        'report.capaian-iku1' => ['slug' => 'capaian-iku1-tahun-2025-dalam-tracerstudy', 'group' => 'Report'],
        'ppid.wajib-berkala' => ['slug' => '1-informasi-wajib-berkala', 'group' => 'PPID'],
        'ppid.tersedia-setiap-saat' => ['slug' => '2-informasi-tersedia-setiap-saat', 'group' => 'PPID'],
        'ppid.dikecualikan' => ['slug' => 'daftar-informasi-dikecualikan', 'group' => 'PPID'],
    ],

    /*
    | Navbar dibangun dari tabel menus/submenus (bisa diedit admin).
    | id menu di tabel `menus` untuk tiap dropdown navbar.
    | Halaman Profil Pimpinan menampilkan semua halaman pada submenu milik menu "pimpinan".
    */
    'menus' => [
        'profil' => (int) env('PERKASA_MENU_PROFIL', 6),
        'ppid' => (int) env('PERKASA_MENU_PPID', 17),
        'pimpinan' => (int) env('PERKASA_MENU_PIMPINAN', 23),
        'report' => (int) env('PERKASA_MENU_REPORT', 24),
    ],

    // Folder upload lama di disk "legacy" (storage/app/legacy/...)
    'upload_folders' => [
        'berita' => 'berita',
        'event' => 'event',
        'loker' => 'loker',
        'company' => 'company',
        'user' => 'user',
        'mahasiswa' => 'mahasiswa',
    ],

    // Sementara modul tracer belum dipindah, menu Tracer Study diarahkan ke sini
    'legacy_tracer_url' => env('LEGACY_TRACER_URL', 'https://perkasa.unmul.ac.id/perkasa2/tracer-study'),

    // Domain lama: tautan absolut di konten HTML lama yang mengarah ke sini diubah ke rute baru
    'legacy_hosts' => ['perkasa.unmul.ac.id', 'www.perkasa.unmul.ac.id'],

    // Batas lamaran: kolom loker.tanggal_loker diperlakukan sebagai tanggal penutupan
    'loker_segera_berakhir_hari' => 7,

    // Kontak dan tautan footer
    'contact' => [
        'address' => env('PERKASA_ADDRESS', 'Gedung Prof. Masjaya Lt. 1, Universitas Mulawarman'),
        'maps_url' => env('PERKASA_MAPS_URL', 'https://maps.app.goo.gl/pqkrj5Ew4vJCkkD76'),
        'emails' => array_filter(explode(',', env('PERKASA_EMAILS', 'upt.perkasa@unmul.ac.id,uptperkasaunmul@gmail.com'))),
        'whatsapp' => env('PERKASA_WHATSAPP', '08115828004'),
        'whatsapp_label' => env('PERKASA_WHATSAPP_LABEL', '08115828004 - Admin'),
    ],

    'footer_links' => [
        ['label' => 'Universitas Mulawarman', 'url' => 'https://unmul.ac.id/'],
        ['label' => 'PPID Universitas Mulawarman', 'url' => 'https://ppid.unmul.ac.id/'],
    ],

    'social' => [
        ['label' => 'Instagram', 'icon' => 'bi-instagram', 'url' => 'https://www.instagram.com/upa_perkasa/'],
        ['label' => 'LinkedIn', 'icon' => 'bi-linkedin', 'url' => 'https://www.linkedin.com/in/upa-perkasa-unmul-5b6677139/'],
        ['label' => 'YouTube', 'icon' => 'bi-youtube', 'url' => 'https://www.youtube.com/@tvperkasa9216'],
    ],

    // Login mahasiswa Unmul lewat API OSM (sama dengan aplikasi lama)
    'osm' => [
        'base_url' => env('OSM_BASE_URL', 'https://osm.unmul.ac.id'),
        'api_key' => env('OSM_API_KEY'),
        'username' => env('OSM_USERNAME'),
        'password' => env('OSM_PASSWORD'),
        'timeout' => 10,
    ],
];
