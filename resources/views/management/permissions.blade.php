@extends('layouts.app')

@section('title', 'Manajemen Permissions')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Permissions</h1>
            <div class="text-sm text-gray-600">
                Total: {{ $permissions->flatten()->count() }} permissions
            </div>
        </div>

        <!-- Permissions by Category -->
        <div class="space-y-8">
            @foreach($permissions as $category => $categoryPermissions)
                <div class="border rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-800 uppercase">
                            {{ $category }} 
                            <span class="text-sm font-normal text-gray-600">({{ $categoryPermissions->count() }} permissions)</span>
                        </h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permission</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Display Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles Assigned</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($categoryPermissions as $permission)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <code class="px-2 py-1 bg-gray-100 rounded text-sm font-mono">{{ $permission->name }}</code>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $permission->display_name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $permission->description }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @php
                                                $assignedRoles = $roles->filter(function($role) use ($permission) {
                                                    return $role->permissions->contains('id', $permission->id);
                                                });
                                            @endphp
                                            
                                            @if($assignedRoles->count() > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($assignedRoles as $role)
                                                        <span class="px-2 py-1 text-xs rounded-full 
                                                            @if($role->name === 'admin') bg-red-100 text-red-800
                                                            @elseif($role->name === 'manager') bg-blue-100 text-blue-800
                                                            @elseif($role->name === 'supervisor') bg-green-100 text-green-800
                                                            @else bg-gray-100 text-gray-800
                                                            @endif">
                                                            {{ ucfirst($role->name) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-xs">Tidak ada role</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($permission->is_system)
                                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-lock mr-1"></i>System
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-unlock mr-1"></i>Custom
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Roles Summary -->
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Role Permission Summary</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($roles as $role)
                    <div class="border rounded-lg p-4 
                        @if($role->name === 'admin') border-red-200 bg-red-50
                        @elseif($role->name === 'manager') border-blue-200 bg-blue-50
                        @elseif($role->name === 'supervisor') border-green-200 bg-green-50
                        @else border-gray-200 bg-gray-50
                        @endif">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold 
                                @if($role->name === 'admin') text-red-800
                                @elseif($role->name === 'manager') text-blue-800
                                @elseif($role->name === 'supervisor') text-green-800
                                @else text-gray-800
                                @endif">
                                {{ ucfirst($role->name) }}
                            </h3>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($role->name === 'admin') bg-red-200 text-red-800
                                @elseif($role->name === 'manager') bg-blue-200 text-blue-800
                                @elseif($role->name === 'supervisor') bg-green-200 text-green-800
                                @else bg-gray-200 text-gray-800
                                @endif">
                                {{ $role->permissions->count() }} perms
                            </span>
                        </div>
                        
                        @if($role->description)
                            <p class="text-sm text-gray-600 mb-3">{{ $role->description }}</p>
                        @endif
                        
                        <!-- Permission categories for this role -->
                        @php
                            $rolePermissionsByCategory = $role->permissions->groupBy('category');
                        @endphp
                        
                        <div class="space-y-2">
                            @foreach($rolePermissionsByCategory as $cat => $catPerms)
                                <div class="text-xs">
                                    <span class="font-medium text-gray-700">{{ ucfirst($cat) }}:</span>
                                    <span class="text-gray-600">{{ $catPerms->count() }} permissions</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Permission Statistics -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Statistics</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $permissions->flatten()->count() }}</div>
                    <div class="text-sm text-gray-600">Total Permissions</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $permissions->count() }}</div>
                    <div class="text-sm text-gray-600">Categories</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $roles->count() }}</div>
                    <div class="text-sm text-gray-600">Roles</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600">{{ $permissions->flatten()->where('is_system', true)->count() }}</div>
                    <div class="text-sm text-gray-600">System Permissions</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
