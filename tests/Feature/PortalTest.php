<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Berita;
use App\Models\Event;
use App\Models\Loker;
use App\Models\Pengumuman;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Tes rute publik terhadap SALINAN db_perkasa (atur di .env.testing).
 * Tes ini hanya membaca data; tidak ada RefreshDatabase.
 */
class PortalTest extends TestCase
{
    public function test_beranda_tampil(): void
    {
        $this->get('/')->assertOk()->assertSee('Lowongan Kerja Terbaru');
    }

    #[DataProvider('halamanPages')]
    public function test_halaman_dari_tabel_pages(string $url): void
    {
        $this->get($url)->assertOk();
    }

    public static function halamanPages(): array
    {
        return collect([
            '/profil/sejarah', '/profil/visi-misi', '/profil/tujuan-sasaran', '/profil/struktur',
            '/profil/program-kerja', '/profil/logo', '/profil/panduan-tracer-study', '/profil/pimpinan',
            '/report/laporan-tahunan', '/report/tracer-study', '/report/capaian-iku1',
            '/ppid/tentang', '/ppid/informasi-wajib-berkala', '/ppid/informasi-tersedia-setiap-saat',
            '/ppid/informasi-yang-dikecualikan',
        ])->mapWithKeys(fn ($u) => [$u => [$u]])->all();
    }

    public function test_daftar_dan_detail_publikasi(): void
    {
        $this->get('/publikasi/berita?q=a&tahun=2025')->assertOk();
        $this->get('/publikasi/agenda-kegiatan?waktu=akan-datang')->assertOk();
        $this->get('/publikasi/pengumuman')->assertOk();
        $this->get('/publikasi/lowongan-pekerjaan?status=dibuka')->assertOk();

        $this->get(route('publikasi.berita-detail', Berita::latest('id')->value('slug')))->assertOk();
        $this->get(route('publikasi.agenda-detail', Agenda::latest('id')->value('slug')))->assertOk();
        $this->get(route('publikasi.event-detail', Event::latest('id')->value('slug')))->assertOk();
        $this->get(route('publikasi.pengumuman-detail', Pengumuman::latest('id')->value('slug')))->assertOk();
        $this->get(route('publikasi.lowongan-detail', Loker::latest('id')->value('slug')))->assertOk();
    }

    public function test_slug_tidak_ada_404(): void
    {
        $this->get('/publikasi/berita/tidak-ada-slug-ini-xyz')->assertNotFound();
    }

    public function test_url_lama_diarahkan(): void
    {
        $slug = Berita::latest('id')->value('slug');

        $this->get("/perkasa2/read/{$slug}/berita")->assertRedirect(route('publikasi.berita-detail', $slug))->assertStatus(301);
        $this->get('/perkasa2/pages/rektor/read')->assertRedirect(route('pages.show', 'rektor'));
        $this->get('/perkasa2/list/loker')->assertRedirect(route('publikasi.lowongan'));
        $this->get('/start-session')->assertRedirect(route('login'));
    }

    public function test_api_cek_mahasiswa_format_lama(): void
    {
        $this->getJson('/api-perkasa/0000000000/cek-mahasiswa')
            ->assertOk()->assertJson(['kode' => 0, 'status' => 'tidak terdaftar']);
    }
}
