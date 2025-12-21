<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly PermissionRepositoryInterface $permissionRepository
    ) {}

    // Permission Methods
    public function getAllPermissions(): Collection
    {
        return $this->permissionRepository->all();
    }

    public function getAllPermissionsLatest(): Collection
    {
        return $this->permissionRepository->getAllLatest();
    }

    public function findPermissionById(int $id): ?Permission
    {
        return $this->permissionRepository->find($id);
    }

    public function createPermission(array $data): Permission
    {
        return $this->permissionRepository->create($data);
    }

    public function updatePermission(Permission $permission, array $data): Permission
    {
        return $this->permissionRepository->update($permission, $data);
    }

    public function deletePermission(Permission $permission): bool
    {
        return $this->permissionRepository->delete($permission);
    }

    // Role Methods
    public function getAllRoles(): Collection
    {
        return $this->roleRepository->all();
    }

    public function getAllRolesLatest(): Collection
    {
        return $this->roleRepository->getAllLatest();
    }

    public function findRoleById(int $id): ?Role
    {
        return $this->roleRepository->find($id);
    }

    public function createRole(array $data): Role
    {
        return $this->roleRepository->create($data);
    }

    public function updateRole(Role $role, array $data): Role
    {
        return $this->roleRepository->update($role, $data);
    }

    public function deleteRole(Role $role): bool
    {
        return $this->roleRepository->delete($role);
    }

    // Role Permission Methods
    public function assignPermissionsToRole(int $roleId, array $permissionIds): void
    {
        $this->roleRepository->assignPermissions($roleId, $permissionIds);
    }

    public function syncRolePermissions(Role $role, array $permissions): Role
    {
        return $this->roleRepository->syncPermissions($role, $permissions);
    }

    public function revokeAllRolePermissions(Role $role): Role
    {
        return $this->roleRepository->revokeAllPermissions($role);
    }

    public function getPermissionGroups(): Collection
    {
        return User::get_permission_group_name();
    }
}
