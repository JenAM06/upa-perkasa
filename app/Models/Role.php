<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    public const SUPERADMIN = 'superadmin';

    public const ADMIN_PERKASA = 'admin_perkasa';

    public const ADMIN_TRACER = 'admin_tracer_study';

    public const MAHASISWA = 'mahasiswa';

    public const COMPANY = 'company';

    public const ADMIN_FAKULTAS = 'admin_fakultas';

    public const ADMIN_PRODI = 'admin_prodi';

    public const ACCESS_API = 'access_api';

    public const SURVEYOR = 'surveyor';

    protected $fillable = ['name', 'guard_name'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }
}
