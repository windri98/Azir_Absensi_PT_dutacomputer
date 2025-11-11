<?php

namespace App\Http\Controllers;

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
        // Get all permissions grouped by category
        $permissions = Permission::select('id', 'name', 'display_name', 'description', 'category', 'is_system')
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        // Get all roles with their permissions
        $roles = Role::with('permissions')->get();

        return view('management.permissions', compact('permissions', 'roles'));
    }

    /**
     * Show permission details
     */
    public function show(Permission $permission)
    {
        $permission->load('roles');
        return view('management.permissions.show', compact('permission'));
    }
}