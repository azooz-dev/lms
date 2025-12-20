<?php

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
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function all_permissions()
    {
        $permissions = Permission::latest()->get();

        return view('admin.backend.pages.permissions.all_permissions', compact('permissions'));
    }

    public function add_permission()
    {
        return view('admin.backend.pages.permissions.add_permission');
    }

    /**
     * Store a new permission in the database.
     *
     * @param  StorePermissionRequest  $request  The validated request object.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_permission(StorePermissionRequest $request)
    {
        try {
            Permission::create([
                'name' => $request->name,
                'group_name' => $request->group_name,
            ]);

            return redirect()
                ->route('admin.all_permission')
                ->with(FlashNotification::success('Permission created successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function permission_edit(string $id)
    {
        $permission = Permission::find($id);

        return view('admin.backend.pages.permissions.edit_permission', compact('permission'));
    }

    /**
     * Update a permission in the database.
     *
     * @param  UpdatePermissionRequest  $request  The validated request object.
     * @param  string  $id  The ID of the permission to update.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_permission(UpdatePermissionRequest $request, string $id)
    {
        try {
            $permission = Permission::find($id);
            $permission->update($request->validated());

            return redirect()
                ->route('admin.all_permission')
                ->with(FlashNotification::success('Permission updated successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function permission_delete(string $id)
    {
        try {
            Permission::find($id)->delete();

            return redirect()->back()->with(FlashNotification::success('Permission deleted successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function export_permission()
    {
        return Excel::download(new PermissionExport, 'permissions.xlsx');
    }

    public function import_permission()
    {
        return view('admin.backend.pages.permissions.import_permission');
    }

    public function import_permission_file(ImportPermissionRequest $request)
    {
        try {
            Excel::import(new PermissionImport, $request->file('excel_file'));

            return redirect()->back()->with(FlashNotification::success('Permission imported successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function all_roles()
    {
        $roles = Role::latest()->get();

        return view('admin.backend.pages.roles.all_roles', compact('roles'));
    }

    public function add_role()
    {
        return view('admin.backend.pages.roles.add_role');
    }

    /**
     * Store a new role in the database.
     *
     * @param  StoreRoleRequest  $request  The validated request object.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_role(StoreRoleRequest $request)
    {
        try {
            Role::create([
                'name' => $request->name,
            ]);

            return redirect()
                ->route('admin.all_role')
                ->with(FlashNotification::success('Role created successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function edit_role(string $id)
    {
        $role = Role::find($id);

        return view('admin.backend.pages.roles.edit_role', compact('role'));
    }

    /**
     * Update a role in the database.
     *
     * @param  UpdateRoleRequest  $request  The validated request object.
     * @param  string  $id  The ID of the role to update.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_role(UpdateRoleRequest $request, string $id)
    {
        try {
            $role = Role::find($id);
            $role->update($request->validated());

            return redirect()
                ->route('admin.all_role')
                ->with(FlashNotification::success('Role updated successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function delete_role(string $id)
    {
        try {
            Role::find($id)->delete();

            return redirect()->back()->with(FlashNotification::success('Role deleted successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function all_role_permissions()
    {
        $roles = Role::all();

        return view('admin.backend.pages.roleSetup.all_role_permission', compact('roles'));
    }

    public function add_role_permissions()
    {
        $roles = Role::all();
        $permissionGroups = User::get_permission_group_name();
        $permissions = Permission::all();

        return view('admin.backend.pages.roleSetup.add_role_permissions', compact('roles', 'permissionGroups', 'permissions'));
    }

    public function store_role_permissions(Request $request)
    {
        foreach ($request->permission as $permission) {
            DB::table('role_has_permissions')->insert([
                'role_id' => $request->role_id,
                'permission_id' => $permission,
            ]);
        }

        return redirect()
            ->route('admin.all_role_permissions')
            ->with(FlashNotification::success('Role permissions added successfully.'));
    }

    public function edit_role_permissions(string $id)
    {
        $role = Role::find($id);
        $permissionGroups = User::get_permission_group_name();
        $permissions = Permission::all();

        return view('admin.backend.pages.roleSetup.edit_role_permissions', compact('role', 'permissionGroups', 'permissions'));
    }

    public function update_role_permissions(Request $request, string $id)
    {
        $role = Role::find($id);

        if (! empty($request->permission)) {
            $role->syncPermissions($request->permission);

            return redirect()
                ->route('admin.all_role_permissions')
                ->with(FlashNotification::success('Role permissions updated successfully.'));
        }

        return redirect()->back()->with(FlashNotification::error('Please select at least one permission.'));
    }

    public function delete_role_permissions(string $id)
    {
        try {
            $role = Role::find($id);
            $role->revokePermissionTo($role->permissions);

            return redirect()->back()->with(FlashNotification::success('Role permissions deleted successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }
}
