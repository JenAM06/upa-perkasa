<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Panel\CrudController;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Perusahaan mitra (26 ribu baris). Setiap perusahaan punya akun users ber-role company.
 * Tambah perusahaan = buat akun + baris company sekaligus.
 */
class CompanyController extends CrudController
{
    protected function definition(): array
    {
        return [
            'model' => Company::class,
            'title' => 'Perusahaan',
            'route' => 'panel.admin.company',
            'search' => ['company_name', 'bidang'],
            'with' => ['user:id,email,suspend'],
            'perPage' => 20,
            // Hanya tampil saat tambah: akun login perusahaan
            'createFields' => [
                'account_email' => ['label' => 'Email Akun (untuk login)', 'type' => 'email'],
                'account_password' => ['label' => 'Password Akun', 'type' => 'password', 'help' => 'Minimal 8 karakter.'],
            ],
            'columns' => [
                'Nama' => fn (Company $c) => $c->company_name,
                'Bidang' => fn (Company $c) => Str::limit($c->bidang, 50),
                'Email Akun' => fn (Company $c) => $c->user?->email,
                'Berkas' => fn (Company $c) => $c->file_url ? view('panel.crud.title', ['title' => 'Unduh', 'url' => $c->file_url]) : '',
                'Status' => fn (Company $c) => view('panel.admin-perkasa.company-suspend', ['company' => $c]),
            ],
            'fields' => [
                'company_name' => ['label' => 'Nama Perusahaan', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'bidang' => ['label' => 'Bidang', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                'nama_pimpinan' => ['label' => 'Nama Pimpinan', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                'no_telp' => ['label' => 'No. Telepon', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                'website' => ['label' => 'Website', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                'alamat_pusat' => ['label' => 'Alamat Pusat', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
                'alamat_cabang' => ['label' => 'Alamat Cabang', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
                'deskripsi' => ['label' => 'Deskripsi', 'type' => 'editor', 'rules' => ['nullable', 'string']],
                'file' => ['label' => 'Berkas Perusahaan', 'type' => 'file', 'folder' => 'company', 'rules' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120']],
            ],
        ];
    }

    /** Tambah: buat akun users (role company) lalu baris company. */
    public function store(Request $request): RedirectResponse
    {
        $account = $request->validate([
            'account_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'account_password' => ['required', 'string', 'min:8'],
        ], [], ['account_email' => 'email akun', 'account_password' => 'password akun']);

        $def = $this->def();
        DB::transaction(function () use ($request, $account, $def) {
            $user = User::create([
                'name' => $request->input('company_name'),
                'email' => $account['account_email'],
                'password' => $account['account_password'],
            ]);
            $user->assignRole(Role::COMPANY);

            $company = new Company(['user_id' => $user->id]);
            $this->persist($request, $company, $def);
        });

        return redirect()->route($def['route'].'.index')->with('status', 'Perusahaan berhasil ditambahkan.');
    }

    public function destroy(string $record): RedirectResponse
    {
        // Menghapus perusahaan juga menghapus lowongan & lamaran (FK cascade). Lebih aman dinonaktifkan.
        return back()->withErrors(['company' => 'Perusahaan tidak dihapus. Gunakan tombol Nonaktifkan.']);
    }

    public function toggleSuspend(Company $company): RedirectResponse
    {
        $user = $company->user;
        abort_unless($user, 404);
        $user->forceFill(['suspend' => $user->isSuspended() ? 'false' : 'true'])->save();

        return back()->with('status', $company->company_name.($user->isSuspended() ? ' dinonaktifkan.' : ' diaktifkan kembali.'));
    }

    /** Untuk pilihan perusahaan di form lowongan (data terlalu banyak untuk <select> biasa). */
    public function search(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q'));

        return response()->json(
            Company::query()
                ->when($q !== '', fn ($w) => $w->where('company_name', 'like', "%{$q}%"))
                ->orderBy('company_name')->limit(20)->get(['id', 'company_name'])
                ->map(fn ($c) => ['id' => $c->id, 'text' => $c->company_name])
        );
    }
}
