<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of all permissions
     */
    public function index()
    {
        $permissions = Permission::with('roles')->orderBy('category')->orderBy('name')->get();
        $permissionsByCategory = $permissions->groupBy('category');
        
        return view('admin.permissions.index', compact('permissions', 'permissionsByCategory'));
    }

    /**
     * Show permission matrix (roles vs permissions)
     */
    public function matrix()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('category')->orderBy('name')->get();
        $permissionsByCategory = $permissions->groupBy('category');
        
        return view('admin.permissions.matrix', compact('roles', 'permissions', 'permissionsByCategory'));
    }

    /**
     * Show detailed view of what each role can access
     */
    public function capabilities()
    {
        $roles = Role::with(['permissions' => function($query) {
            $query->orderBy('category')->orderBy('name');
        }])->orderBy('name')->get();
        
        return view('admin.permissions.capabilities', compact('roles'));
    }
}