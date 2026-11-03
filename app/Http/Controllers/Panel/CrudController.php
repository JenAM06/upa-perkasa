<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Support\LegacyStorage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * CRUD generik untuk panel. Satu pasang view (panel/crud/index, panel/crud/form)
 * dipakai semua modul konten, sehingga menambah modul cukup menulis definisi()
 * tanpa membuat view baru.
 *
 * Definisi:
 *  model       FQCN model
 *  title       nama tunggal ("Berita")
 *  route       prefix nama rute ("panel.admin.berita")
 *  search      kolom pencarian
 *  order       [kolom, arah]
 *  with        relasi eager load untuk daftar
 *  columns     [label => closure(Model): string|HtmlString]
 *  fields      [nama_kolom => [label, type, rules, options, folder, help, ...]]
 *              type: text | textarea | editor | date | url | number | image | file | toggle | select | remote-select
 *  author      true: isi post_by dan user_id dari pengguna login
 *  readonly    true: hanya daftar (tanpa tambah/ubah)
 *  flush       kunci cache yang dihapus setelah simpan/hapus
 */
abstract class CrudController extends Controller
{
    abstract protected function definition(): array;

    protected function def(): array
    {
        return array_merge([
            'search' => [], 'order' => ['id', 'desc'], 'with' => [], 'author' => false,
            'readonly' => false, 'flush' => [], 'perPage' => 15, 'description' => null, 'createFields' => [],
        ], $this->definition());
    }

    protected function query(Request $request): Builder
    {
        $def = $this->def();
        $q = trim((string) $request->query('q'));

        return $def['model']::query()
            ->with($def['with'])
            ->when($q !== '' && $def['search'], fn ($query) => $query->where(function ($w) use ($def, $q) {
                foreach ($def['search'] as $col) {
                    $w->orWhere($col, 'like', "%{$q}%");
                }
            }))
            ->orderBy(...$def['order']);
    }

    public function index(Request $request): View
    {
        $def = $this->def();
        $records = $this->query($request)->paginate($def['perPage'])->withQueryString();

        return view('panel.crud.index', compact('def', 'records'));
    }

    public function create(): View
    {
        $def = $this->def();
        abort_if($def['readonly'], 404);
        $record = new $def['model'];

        return view('panel.crud.form', compact('def', 'record'));
    }

    public function store(Request $request): RedirectResponse
    {
        $def = $this->def();
        abort_if($def['readonly'], 404);

        $record = new $def['model'];
        $this->persist($request, $record, $def);

        return redirect()->route($def['route'].'.index')->with('status', $def['title'].' berhasil ditambahkan.');
    }

    public function edit(string $record): View
    {
        $def = $this->def();
        abort_if($def['readonly'], 404);
        $record = $def['model']::findOrFail($record);

        return view('panel.crud.form', compact('def', 'record'));
    }

    public function update(Request $request, string $record): RedirectResponse
    {
        $def = $this->def();
        abort_if($def['readonly'], 404);

        $record = $def['model']::findOrFail($record);
        $this->persist($request, $record, $def);

        return redirect()->route($def['route'].'.index')->with('status', $def['title'].' berhasil diperbarui.');
    }

    public function destroy(string $record): RedirectResponse
    {
        $def = $this->def();
        $record = $def['model']::findOrFail($record);

        DB::transaction(function () use ($record, $def) {
            $record->delete();
            foreach ($def['fields'] as $name => $field) {
                if (in_array($field['type'], ['image', 'file'], true)) {
                    LegacyStorage::delete($field['folder'], $record->{$name});
                }
            }
        });
        $this->flush($def);

        return back()->with('status', $def['title'].' berhasil dihapus.');
    }

    protected function persist(Request $request, Model $record, array $def): void
    {
        $data = $request->validate($this->rules($def, $record), [], $this->attributeNames($def));
        $oldFiles = [];

        foreach ($def['fields'] as $name => $field) {
            switch ($field['type']) {
                case 'toggle':
                    $data[$name] = $request->boolean($name) ? ($field['on'] ?? 'true') : ($field['off'] ?? 'false');
                    break;
                case 'image':
                case 'file':
                    unset($data[$name]);
                    if ($request->hasFile($name)) {
                        $oldFiles[] = [$field['folder'], $record->{$name}];
                        $data[$name] = LegacyStorage::store($request->file($name), $field['folder']);
                    }
                    break;
                case 'editor':
                case 'textarea':
                    // Kolom longtext lama NOT NULL: simpan string kosong, bukan null
                    $data[$name] = (string) ($data[$name] ?? '');
                    break;
            }
        }

        if ($def['author'] && ! $record->exists) {
            $data['post_by'] = $request->user()->name;
            if (in_array('user_id', $record->getFillable(), true)) {
                $data['user_id'] = $request->user()->id;
            }
        }

        DB::transaction(fn () => $record->fill($data)->save());

        foreach ($oldFiles as [$folder, $file]) {
            LegacyStorage::delete($folder, $file);
        }

        $this->flush($def);
    }

    protected function rules(array $def, Model $record): array
    {
        $rules = [];
        foreach ($def['fields'] as $name => $field) {
            $base = $field['rules'] ?? ['nullable'];
            if (in_array($field['type'], ['image', 'file'], true) && $record->exists) {
                // Saat edit, file boleh tidak diganti
                $base = array_map(fn ($r) => $r === 'required' ? 'nullable' : $r, $base);
            }
            if ($field['type'] === 'toggle') {
                $base = ['nullable'];
            }
            $rules[$name] = $base;
        }

        return $rules;
    }

    protected function attributeNames(array $def): array
    {
        return collect($def['fields'])->map(fn ($f) => strtolower($f['label']))->all();
    }

    protected function flush(array $def): void
    {
        foreach ($def['flush'] as $key) {
            Cache::forget($key);
        }
    }
}
