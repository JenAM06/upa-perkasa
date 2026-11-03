<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Company;
use App\Models\Loker;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Tes login dan panel Admin Perkasa terhadap SALINAN db_perkasa. Data uji dibungkus transaksi dan di-rollback. */
class PanelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }

    public function test_peran_lama_terbaca_lewat_morph_map(): void
    {
        $admin = User::whereHas('roles', fn ($q) => $q->where('name', Role::ADMIN_PERKASA))->first();

        $this->assertNotNull($admin, 'Tidak ada akun admin_perkasa: morph map App\User kemungkinan tidak terpasang.');
        $this->assertTrue($admin->hasRole(Role::ADMIN_PERKASA));
    }

    public function test_login_bcrypt_dan_md5(): void
    {
        $bcrypt = User::create(['name' => 'Uji Bcrypt', 'email' => 'uji-bcrypt@example.test', 'password' => 'rahasia123']);
        $bcrypt->assignRole(Role::ADMIN_PERKASA);
        $this->post('/login', ['login' => 'uji-bcrypt@example.test', 'password' => 'rahasia123'])
            ->assertRedirect(route('panel.home'));
        $this->post('/logout');

        $md5 = User::create(['name' => 'Uji Md5', 'email' => '1999999999']);
        DB::table('users')->where('id', $md5->id)->update(['password' => md5('lama123')]);
        $this->post('/login', ['login' => '1999999999', 'password' => 'lama123'])->assertRedirect(route('panel.home'));
        $this->assertStringStartsWith('$2y$', $md5->fresh()->password, 'Password md5 harus di-rehash ke bcrypt.');
    }

    public function test_password_salah_ditolak(): void
    {
        $this->post('/login', ['login' => 'tidak-ada@example.test', 'password' => 'x'])->assertSessionHasErrors('login');
    }

    public function test_admin_perkasa_bisa_membuka_semua_menu(): void
    {
        $admin = User::whereHas('roles', fn ($q) => $q->where('name', Role::ADMIN_PERKASA))->firstOrFail();

        foreach (['berita', 'agenda', 'event', 'pengumuman', 'loker', 'pages', 'menus', 'submenus', 'company', 'apply', 'non-unmul', 'alumni-lama'] as $menu) {
            $this->actingAs($admin)->get(route("panel.admin.{$menu}.index"))->assertOk();
        }
        $this->actingAs($admin)->get(route('panel.admin.berita.create'))->assertOk();
        $this->actingAs($admin)->get(route('panel.admin.profile-text.edit', 'sejarah'))->assertOk();
    }

    public function test_mahasiswa_tidak_bisa_masuk_panel_admin(): void
    {
        $mhs = User::whereHas('roles', fn ($q) => $q->where('name', Role::MAHASISWA))->firstOrFail();

        $this->actingAs($mhs)->get(route('panel.admin.berita.index'))->assertForbidden();
        $this->actingAs($mhs)->get(route('panel.dashboard'))->assertOk();
    }

    public function test_tambah_berita_dan_lowongan_dengan_upload(): void
    {
        Storage::fake('legacy');
        $admin = User::whereHas('roles', fn ($q) => $q->where('name', Role::ADMIN_PERKASA))->firstOrFail();

        $this->actingAs($admin)->post(route('panel.admin.berita.store'), [
            'judul' => 'Uji Berita Otomatis',
            'isi' => '<p>Isi <script>alert(1)</script>uji</p>',
            'image' => $this->gambar('foto.png'),
            'slider' => '1',
        ])->assertRedirect(route('panel.admin.berita.index'));

        $berita = Berita::where('judul', 'Uji Berita Otomatis')->firstOrFail();
        $this->assertSame('uji-berita-otomatis', $berita->slug);
        $this->assertSame('true', $berita->slider);
        $this->assertSame($admin->name, $berita->post_by);
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{10}\.png$/', $berita->image);
        Storage::disk('legacy')->assertExists('berita/'.$berita->image);

        $this->get(route('publikasi.berita-detail', $berita->slug))->assertOk()->assertDontSee('alert(1)', false);

        $company = Company::firstOrFail();
        $this->actingAs($admin)->post(route('panel.admin.loker.store'), [
            'name_loker' => 'Uji Lowongan', 'company_id' => $company->id, 'tanggal_loker' => now()->addDays(30)->toDateString(),
            'lokasi' => 'Samarinda', 'gaji' => 'Dirahasiakan', 'fungsi' => 'a', 'tingkatan_karir' => 'b', 'pengalaman' => 'c',
            'link' => 'https://contoh.test/lamar', 'image' => $this->gambar('poster.png'), 'text' => '<p>x</p>',
        ])->assertSessionHasNoErrors();

        $loker = Loker::where('name_loker', 'Uji Lowongan')->firstOrFail();
        $this->assertSame(Loker::STATUS_OPEN, $loker->status);
        $this->get(route('publikasi.lowongan-detail', $loker->slug))->assertOk()->assertSee('Uji Lowongan');
    }

    /** PNG 1x1 asli (tanpa ekstensi GD). */
    private function gambar(string $nama): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='));

        return new UploadedFile($path, $nama, 'image/png', null, true);
    }
}
