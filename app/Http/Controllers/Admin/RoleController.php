<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('name')->get();
        
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:roles,name|alpha_dash',
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama role wajib diisi',
            'name.unique' => 'Nama role sudah ada',
            'name.alpha_dash' => 'Nama role hanya boleh menggunakan huruf, angka, dash, dan underscore',
            'display_name.required' => 'Nama tampilan role wajib diisi',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Role::create([
                'name' => strtolower($request->name),
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role berhasil ditambahkan: ' . $request->display_name);
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Gagal menambahkan role: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        $role->load([
            'users' => function ($query) {
                $query->orderBy('name');
            },
            'permissions' => function ($query) {
                $query->orderBy('category', 'asc')->orderBy('name', 'asc');
            }
        ]);

        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama role wajib diisi',
            'name.unique' => 'Nama role sudah ada',
            'name.alpha_dash' => 'Nama role hanya boleh menggunakan huruf, angka, dash, dan underscore',
            'display_name.required' => 'Nama tampilan role wajib diisi',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $role->update([
                'name' => strtolower($request->name),
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role berhasil diperbarui: ' . $request->display_name);
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Gagal memperbarui role: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        // Prevent deletion of built-in roles
        $protectedRoles = ['admin', 'manager', 'employee', 'supervisor'];
        
        if (in_array($role->name, $protectedRoles)) {
            return back()->withErrors(['error' => 'Role sistem tidak dapat dihapus: ' . $role->display_name]);
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus role yang masih digunakan oleh user']);
        }

        try {
            $roleName = $role->display_name;
            $role->delete();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role berhasil dihapus: ' . $roleName);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus role: ' . $e->getMessage()]);
        }
    }

    /**
     * Assign users to role
     */
    public function assignUsers(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ], [
            'user_ids.required' => 'Pilih minimal satu user',
            'user_ids.*.exists' => 'User tidak valid',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $users = User::whereIn('id', $request->user_ids)->get();
            
            foreach ($users as $user) {
                if (!$user->hasRole($role->name)) {
                    $user->roles()->attach($role->id);
                }
            }

            return back()->with('success', 'User berhasil ditambahkan ke role: ' . $role->display_name);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambahkan user ke role: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove user from role
     */
    public function removeUser(Role $role, User $user)
    {
        try {
            if ($user->hasRole($role->name)) {
                $user->roles()->detach($role->id);
                return back()->with('success', 'User berhasil dihapus dari role: ' . $role->display_name);
            }

            return back()->withErrors(['error' => 'User tidak memiliki role ini']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus user dari role: ' . $e->getMessage()]);
        }
    }

    /**
     * Update permissions for a role
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'permissions.array' => 'Format permissions tidak valid',
            'permissions.*.exists' => 'Permission tidak valid',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $permissionIds = $request->input('permissions', []);
            
            // Sync permissions (akan menambah yang baru dan menghapus yang tidak terpilih)
            $role->permissions()->sync($permissionIds);

            $permissionCount = count($permissionIds);
            
            return back()->with('success', "Permissions berhasil diperbarui untuk role {$role->display_name}! Total: {$permissionCount} permissions");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengupdate permissions: ' . $e->getMessage()]);
        }
    }

    /**
     * Assign specific permission to role
     */
    public function assignPermission(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $permission = \App\Models\Permission::find($request->permission_id);
            
            if (!$role->hasPermission($permission)) {
                $role->permissions()->attach($permission->id);
                return back()->with('success', "Permission '{$permission->display_name}' berhasil ditambahkan ke role {$role->display_name}");
            }

            return back()->withErrors(['error' => 'Role sudah memiliki permission ini']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambahkan permission: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove specific permission from role
     */
    public function removePermission(Role $role, \App\Models\Permission $permission)
    {
        try {
            if ($role->hasPermission($permission)) {
                $role->permissions()->detach($permission->id);
                return back()->with('success', "Permission '{$permission->display_name}' berhasil dihapus dari role {$role->display_name}");
            }

            return back()->withErrors(['error' => 'Role tidak memiliki permission ini']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus permission: ' . $e->getMessage()]);
        }
    }
}