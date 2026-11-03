<?php

namespace App\Models\Concerns;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

/**
 * Peran dan izin dibaca dari tabel Spatie Permission lama
 * (roles, permissions, model_has_roles, model_has_permissions, role_has_permissions).
 * Kolom model_type berisi 'App\User'; dipetakan ke App\Models\User lewat morph map
 * di AppServiceProvider. Ditulis tanpa paket agar tidak menambah dependensi.
 */
trait HasLegacyRoles
{
    private ?Collection $cachedRoleNames = null;

    public function roles(): MorphToMany
    {
        return $this->morphToMany(Role::class, 'model', 'model_has_roles', 'model_id', 'role_id');
    }

    public function permissions(): MorphToMany
    {
        return $this->morphToMany(Permission::class, 'model', 'model_has_permissions', 'model_id', 'permission_id');
    }

    public function roleNames(): Collection
    {
        return $this->cachedRoleNames ??= $this->roles()->pluck('name');
    }

    /** @param  string|array  $roles  'admin_perkasa' atau 'superadmin|admin_perkasa' atau array */
    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : explode('|', $roles);

        return $this->roleNames()->intersect($roles)->isNotEmpty();
    }

    public function hasPermission(string|array $permissions): bool
    {
        $permissions = is_array($permissions) ? $permissions : explode('|', $permissions);

        $direct = $this->permissions()->whereIn('name', $permissions)->exists();

        return $direct || Permission::query()
            ->whereIn('name', $permissions)
            ->whereHas('roles', fn ($q) => $q->whereIn('roles.id', $this->roles()->pluck('roles.id')))
            ->exists();
    }

    public function assignRole(string $name): void
    {
        $role = Role::where('name', $name)->where('guard_name', 'web')->firstOrFail();
        $this->roles()->syncWithoutDetaching([$role->id]);
        $this->cachedRoleNames = null;
    }

    public function givePermission(string ...$names): void
    {
        $ids = Permission::whereIn('name', $names)->pluck('id');
        $this->permissions()->syncWithoutDetaching($ids);
    }
}
