<?php

namespace App\Http\Controllers\backend;

use App\Exports\PermissionExport;
use App\Http\Controllers\Controller;
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
    public function all_permissions() {
        $permissions = Permission::latest()->get();

        return view('admin.backend.pages.permissions.all_permissions', compact('permissions'));
    }

    public function add_permission() {
        return view('admin.backend.pages.permissions.add_permission');
    }

    /**
     * Store a new permission in the database.
     *
     * @param Request $request The HTTP request object.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_permission(Request $request) {

        try{
            // Validate the request data
            $request->validate([
                'name' => 'required|unique:permissions',
                'group_name' => 'required'
            ]);
    
            // Create a new permission record
            Permission::create([
                'name' => $request->name,
                'group_name' => $request->group_name
            ]);
    
            // Create a success notification
            $notification = [
                'message' => 'Permission created successfully.',
                'alert-type' => 'success'
            ];
    
            // Redirect to the all permissions page with the notification
            return redirect()->route('admin.all_permission')->with($notification);
        } catch(Exception $e) {

            // Create an error notification
            $notification = [
                'message' => 'Oops, something went wrong. Please try again',
                'alert-type' => 'error'
            ];

            // Redirect back to the previous page with the notification
            return redirect()->back()->with($notification);
        }
    }

    public function permission_edit(string $id) {
        $permission = Permission::find($id);

        return view('admin.backend.pages.permissions.edit_permission', compact('permission'));
    }

    /**
     * Update a permission in the database.
     *
     * @param Request $request The HTTP request object.
     * @param string $id The ID of the permission to update.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_permission(Request $request, string $id) {

        try  {
            // Find the permission by ID
            $permission = Permission::find($id);

            // Validate the request data
            $data = $request->validate([
                'name' => 'required|unique:permissions,name,'.$permission->id, // Ensure the name is unique except for itself
                'group_name' => 'required'
            ]);

            // Update the permission with the new data
            $permission->update($data);
            
            // Create a success notification
            $notification = array(
                'message' => 'Permission updated successfully.',
                'alert-type' => 'success',
            );

            // Redirect to the all permissions page with the notification
            return redirect()->route('admin.all_permission')->with($notification);
        } catch(Exception $e) {

            // Create an error notification
            $notification = array(
                'message' => "Oops! something went wrong.",
                'alert-type' => 'error',
            );

            // Redirect back to the previous page with the notification
            return redirect()->back()->with($notification);
        }
    }

    public function permission_delete(string $id) {

        try {
            Permission::find($id)->delete();
            $notification = array(
                'message' => 'Permission deleted successfully.',
                'alert-type' => 'success',
            );
            return redirect()->back()->with($notification);
        } catch(Exception $e) {
            $notification = array(
                'message' => "Oops! something went wrong.",  
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }
    }

    public function export_permission() {

        return Excel::download(new PermissionExport, 'permissions.xlsx');
    }

    public function import_permission() {
        return view('admin.backend.pages.permissions.import_permission');
    }

    public function import_permission_file(Request $request) {

        $request->validate([
            'excel_file' => 'required|mimes:xlsx'
        ]);

        $import = Excel::import(new PermissionImport, $request->file('excel_file'));
        if($import) {
            $notification = array(
                'message' => 'Permission imported successfully.',
                'alert-type' => 'success',
            );
            return redirect()->back()->with($notification);
        } else {
            $notification = array(
                'message' => "Oops! something went wrong.",  
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }
    }

    public function all_roles() {

        $roles = Role::latest()->get();
        return view('admin.backend.pages.roles.all_roles', compact('roles'));
    }

    public function add_role() {

        return view('admin.backend.pages.roles.add_role');
    }

    /**
     * Store a new role in the database.
     *
     * @param Request $request The HTTP request object.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_role(Request $request) {

        try {

            // Validate the request data
            $request->validate([
                'name' => 'required|unique:roles' // Ensure the name is unique
            ]);

            // Create a new role
            Role::create([
                'name' => $request->name
            ]);

            // Create a success notification
            $notification = array(
                'message' => 'Role created successfully.',
                'alert-type' => 'success',
            );

            // Redirect to the all roles page with the notification
            return redirect()->route('admin.all_role')->with($notification);
        } catch(Exception $e) {

            // Create an error notification
            $notification = array(
                'message' => 'Oops, something went wrong. Please try again',
                'alert-type' => 'error'
            );

            // Redirect back to the previous page with the notification
            return redirect()->back()->with($notification);
        }
    }

    public function edit_role(string $id) {

        $role = Role::find($id);
        return view('admin.backend.pages.roles.edit_role', compact('role'));
    }

    /**
     * Update a role in the database.
     *
     * @param Request $request The HTTP request object.
     * @param string $id The ID of the role to update.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_role(Request $request, string $id) {

        try  {
            // Find the role by ID
            $role = Role::find($id);

            // Validate the request data
            $data = $request->validate([
                'name' => 'required|unique:roles,name,'.$role->id, // Ensure the name is unique except for itself
            ]);

            // Update the role with the new data
            $role->update($data);

            // Create a success notification
            $notification = array(
                'message' => 'Role updated successfully.',
                'alert-type' => 'success',
            );

            // Redirect to the all roles page with the notification
            return redirect()->route('admin.all_role')->with($notification);

        } catch(Exception $e) {

            // Create an error notification
            $notification = array(
                'message' => 'Oops, something went wrong. Please try again',
                'alert-type' => 'error'
            );

            // Redirect back to the previous page with the notification
            return redirect()->back()->with($notification);
        }
    }

    public function delete_role(string $id) {

        try {
            Role::find($id)->delete();

            $notification = array(
                'message' => 'Role deleted successfully.',
                'alert-type' => 'success',
            );

            return redirect()->back()->with($notification);
        } catch(Exception $e) {

            $notification = array(
                'message' => 'Oops, something went wrong. Please try again',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }
    }

    public function all_role_permissions() {

        $roles = Role::all();

        return view('admin.backend.pages.roleSetup.all_role_permission', compact('roles'));
    }


    public function add_role_permissions() {

        $roles = Role::all();

        $permissionGroups = User::get_permission_group_name();

        $permissions = Permission::all();

        return view('admin.backend.pages.roleSetup.add_role_permissions', compact('roles', 'permissionGroups', 'permissions'));
    }


    public function store_role_permissions(Request $request) {

        foreach ($request->permission as $permission) {
            DB::table('role_has_permissions')->insert([
                'role_id' => $request->role_id,
                'permission_id' => $permission
            ]);
        }


        $notification = array(
            'message' => 'Role permissions added successfully.',
            'alert-type' => 'success',
        );

        return redirect()->route('admin.all_role_permissions')->with($notification);
    }

    public function edit_role_permissions(string $id) {

        $role = Role::find($id);

        $permissionGroups = User::get_permission_group_name();

        $permissions = Permission::all();

        return view('admin.backend.pages.roleSetup.edit_role_permissions', compact('role', 'permissionGroups', 'permissions'));
    }

    public function update_role_permissions(Request $request, string $id) {

        $role = Role::find($id);

        if(!empty($request->permission)) {
            $role->syncPermissions($request->permission);

            $notification = array(
                'message' => 'Role permissions updated successfully.',
                'alert-type' => 'success',
            );

            return redirect()->route('admin.all_role_permissions')->with($notification);
        }
    }


    public function delete_role_permissions(string $id) {
        try {

            $role = Role::find($id);
            $role->revokePermissionTo($role->permissions);

            $notification = array(
                'message' => 'Role permissions deleted successfully.',
                'alert-type' => 'success',
            );

            return redirect()->back()->with($notification);
        } catch(Exception $e) {

            $notification = array(
                'message' => 'Oops, something went wrong. Please try again',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }
    }
}
