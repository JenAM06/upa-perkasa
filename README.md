# UPA Perkasa Unmul (Laravel 13)

Website UPA Pengembangan Karir dan Kewirausahaan Universitas Mulawarman: portal publik, panel admin, dan (tahap berikutnya) tracer study. Pengganti aplikasi lama Laravel 5.8 di `perkasa.unmul.ac.id/perkasa2`.

Acuan kebutuhan: `PRD_Backend_Redesign_UPA_Perkasa_v0.4.md`.

## Aturan penting

1. **Database tidak diubah.** `db_perkasa` dipakai apa adanya (tanpa kolom/tabel baru). Folder `database/migrations` sengaja kosong. **Jangan jalankan `php artisan migrate`** di database ini.
2. Sesi, cache, dan antrean memakai driver `file`/`sync` supaya tidak butuh tabel tambahan.
3. Peran dibaca dari tabel Spatie lama. Kolom `model_has_roles.model_type` berisi `App\User`, dipetakan ke `App\Models\User` di `AppServiceProvider`. Jangan hapus pemetaan itu.
4. File upload lama (nama acak 10 karakter) tetap di folder yang sama, lewat disk `legacy`.

## Instalasi lokal

```bash
composer update laravel/sanctum   # sekali saja: membuang sanctum dari composer.lock
composer install
npm install && npm run build      # atau `npm run dev` saat mengembangkan

cp .env.example .env
php artisan key:generate
# isi DB_* di .env, lalu impor dump db_perkasa.sql ke MySQL/MariaDB

php artisan storage:link          # membuat public/storages -> storage/app/legacy
php artisan serve
```

### File upload

Isi `storage/app/legacy/` dari backup folder `storages/` lama:

```
storage/app/legacy/
├── berita/   event/   loker/   company/   user/   mahasiswa/
└── .htaccess   (memblokir eksekusi skrip, dibawa dari aplikasi lama)
```

Selama backup belum disalin, isi `LEGACY_ASSET_URL=https://perkasa.unmul.ac.id/storages` di `.env` agar gambar tampil dari server lama. Di Windows (Laragon), jalankan `storage:link` dari terminal Administrator.

Cek file yang hilang:

```bash
php artisan tinker
>>> App\Models\Berita::pluck('image')->reject(fn ($f) => App\Support\LegacyStorage::exists('berita', $f))->count();
```

### Login

- Admin dan perusahaan: email + password.
- Mahasiswa Unmul: NIM + password SIA, diperiksa ke API OSM (`OSM_*` di `.env`, minta ke pengelola aplikasi lama).
- Password lama berformat md5 otomatis diubah ke bcrypt saat login berhasil.

## Struktur

```
app/
├── Http/Controllers/
│   ├── Public/            portal: Home, Berita, Agenda, Event, Pengumuman, Lowongan, Page, LegacyRedirect
│   ├── Auth/              LoginController (satu pintu untuk semua peran)
│   ├── Panel/
│   │   ├── CrudController.php      CRUD generik: satu pasang view untuk semua modul konten
│   │   ├── DashboardController.php
│   │   └── AdminPerkasa/           definisi modul (Berita, Agenda, Event, Pengumuman, Loker, Page,
│   │                               Menu, SubMenu, Company, JobApply, MahasiswaNonUnmul,
│   │                               AlumniLama, ProfileText)
│   └── Api/CekMahasiswaController.php
├── Http/Middleware/       EnsureRole (role:a|b), EnsureNotSuspended
├── Models/                satu model per tabel lama; Concerns/ berisi trait bersama
│   └── Concerns/          HasUniqueSlug, HasLegacyUpload, HasHtmlBody, HasLegacyRoles
├── Services/Auth/         LegacyCredentials (bcrypt/md5), OsmLogin
├── Support/               HtmlContent (sanitasi HTML), LegacyUrl (URL lama -> baru),
│                          LegacyStorage (disk upload lama), ListFilter
└── View/Composers/        NavigationComposer (navbar dari tabel menus), PanelComposer (sidebar per peran)

config/perkasa.php         slug halaman, id menu navbar, kontak footer, OSM
resources/views/
├── layouts/ components/ home/ publikasi/ ppid/   portal (desain frontend, data dari database)
├── pages/show.blade.php   semua halaman dari tabel pages (Profil, Report, PPID, /halaman/{slug})
├── auth/ errors/
└── panel/
    ├── layouts/app.blade.php
    ├── crud/              index.blade.php + form.blade.php dipakai semua modul
    └── admin-perkasa/     view khusus (alumni lama, sejarah/visi misi, tombol suspend)
routes/
├── web.php                portal + redirect URL lama + API cek mahasiswa
├── auth.php               login/logout
└── panel.php              /panel/... per peran
```

### Menambah modul konten panel

Buat controller yang mewarisi `CrudController` dan isi `definition()` (model, kolom tabel, field form, aturan validasi), lalu daftarkan satu baris di `routes/panel.php`. Tidak perlu membuat view. Contoh: `app/Http/Controllers/Panel/AdminPerkasa/PengumumanController.php`.

### Mengganti halaman Profil/Report/PPID

Konten diambil dari tabel `pages`. Jika admin membuat halaman pengganti (mis. Program Kerja 2027), ubah slug di `config/perkasa.php` bagian `pages`.

## Tes

Tes membaca salinan database (bukan sqlite). Buat `.env.testing` yang menunjuk ke **salinan** `db_perkasa`, lalu:

```bash
php artisan test
```

`PanelTest` membuat akun uji di dalam transaksi yang di-rollback.

## Status pengerjaan

| Bagian | Status |
|---|---|
| Fondasi Laravel 13 (struktur Laravel 11+, config, model, peran) | Selesai |
| Portal publik tersambung database, redirect URL lama, API cek mahasiswa | Selesai |
| Login satu pintu (email/NIM, OSM, md5 -> bcrypt) | Selesai |
| Panel Admin Perkasa | Selesai |
| Panel Super Admin, Company, Mahasiswa | Berikutnya |
| Tracer study publik dan kuisioner | Berikutnya (sementara `/tracer-study` diarahkan ke aplikasi lama) |
| Panel Admin Tracer, Surveyor, Admin Fakultas, Admin Prodi, laporan bersama | Menunggu kode terbaru dan struktur tabel dari server |
