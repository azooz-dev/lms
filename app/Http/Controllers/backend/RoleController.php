<?php

declare(strict_types=1);

namespace App\Http\Controllers\backend;

use App\Exports\PermissionExport;
use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\ImportPermissionRequest;
use App\Http\Requests\Role\StorePermissionRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdatePermissionRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Imports\PermissionImport;
use App\Services\RoleService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleService $roleService
    ) {}

    public function all_permissions(): View
    {
        $permissions = $this->roleService->getAllPermissionsLatest();

        return view('admin.backend.pages.permissions.all_permissions', compact('permissions'));
    }

    public function add_permission(): View
    {
        return view('admin.backend.pages.permissions.add_permission');
    }

    public function store_permission(StorePermissionRequest $request): RedirectResponse
    {
        try {
            $this->roleService->createPermission($request->validated());

            return redirect()
                ->route('admin.all_permission')
                ->with(FlashNotification::success('Permission created successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function permission_edit(string $id): View
    {
        $permission = $this->roleService->findPermissionById((int) $id);

        return view('admin.backend.pages.permissions.edit_permission', compact('permission'));
    }

    public function update_permission(UpdatePermissionRequest $request, string $id): RedirectResponse
    {
        try {
            $permission = $this->roleService->findPermissionById((int) $id);
            $this->roleService->updatePermission($permission, $request->validated());

            return redirect()
                ->route('admin.all_permission')
                ->with(FlashNotification::success('Permission updated successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function permission_delete(string $id): RedirectResponse
    {
        try {
            $permission = $this->roleService->findPermissionById((int) $id);
            $this->roleService->deletePermission($permission);

            return redirect()->back()->with(FlashNotification::success('Permission deleted successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function export_permission(): BinaryFileResponse
    {
        return Excel::download(new PermissionExport, 'permissions.xlsx');
    }

    public function import_permission(): View
    {
        return view('admin.backend.pages.permissions.import_permission');
    }

    public function import_permission_file(ImportPermissionRequest $request): RedirectResponse
    {
        try {
            Excel::import(new PermissionImport, $request->file('excel_file'));

            return redirect()->back()->with(FlashNotification::success('Permission imported successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function all_roles(): View
    {
        $roles = $this->roleService->getAllRolesLatest();

        return view('admin.backend.pages.roles.all_roles', compact('roles'));
    }

    public function add_role(): View
    {
        return view('admin.backend.pages.roles.add_role');
    }

    public function store_role(StoreRoleRequest $request): RedirectResponse
    {
        try {
            $this->roleService->createRole($request->validated());

            return redirect()
                ->route('admin.all_role')
                ->with(FlashNotification::success('Role created successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function edit_role(string $id): View
    {
        $role = $this->roleService->findRoleById((int) $id);

        return view('admin.backend.pages.roles.edit_role', compact('role'));
    }

    public function update_role(UpdateRoleRequest $request, string $id): RedirectResponse
    {
        try {
            $role = $this->roleService->findRoleById((int) $id);
            $this->roleService->updateRole($role, $request->validated());

            return redirect()
                ->route('admin.all_role')
                ->with(FlashNotification::success('Role updated successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function delete_role(string $id): RedirectResponse
    {
        try {
            $role = $this->roleService->findRoleById((int) $id);
            $this->roleService->deleteRole($role);

            return redirect()->back()->with(FlashNotification::success('Role deleted successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function all_role_permissions(): View
    {
        $roles = $this->roleService->getAllRoles();

        return view('admin.backend.pages.roleSetup.all_role_permission', compact('roles'));
    }

    public function add_role_permissions(): View
    {
        $roles = $this->roleService->getAllRoles();
        $permissionGroups = $this->roleService->getPermissionGroups();
        $permissions = $this->roleService->getAllPermissions();

        return view('admin.backend.pages.roleSetup.add_role_permissions', compact('roles', 'permissionGroups', 'permissions'));
    }

    public function store_role_permissions(Request $request): RedirectResponse
    {
        $this->roleService->assignPermissionsToRole(
            (int) $request->role_id,
            $request->permission
        );

        return redirect()
            ->route('admin.all_role_permissions')
            ->with(FlashNotification::success('Role permissions added successfully.'));
    }

    public function edit_role_permissions(string $id): View
    {
        $role = $this->roleService->findRoleById((int) $id);
        $permissionGroups = $this->roleService->getPermissionGroups();
        $permissions = $this->roleService->getAllPermissions();

        return view('admin.backend.pages.roleSetup.edit_role_permissions', compact('role', 'permissionGroups', 'permissions'));
    }

    public function update_role_permissions(Request $request, string $id): RedirectResponse
    {
        $role = $this->roleService->findRoleById((int) $id);

        if (! empty($request->permission)) {
            $this->roleService->syncRolePermissions($role, $request->permission);

            return redirect()
                ->route('admin.all_role_permissions')
                ->with(FlashNotification::success('Role permissions updated successfully.'));
        }

        return redirect()->back()->with(FlashNotification::error('Please select at least one permission.'));
    }

    public function delete_role_permissions(string $id): RedirectResponse
    {
        try {
            $role = $this->roleService->findRoleById((int) $id);
            $this->roleService->revokeAllRolePermissions($role);

            return redirect()->back()->with(FlashNotification::success('Role permissions deleted successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }
}
