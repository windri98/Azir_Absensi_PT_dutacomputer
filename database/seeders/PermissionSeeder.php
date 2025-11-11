<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all system permissions
        $permissions = [
            // Dashboard permissions
            [
                'name' => 'dashboard.view',
                'display_name' => 'View Dashboard',
                'description' => 'Dapat melihat dashboard utama',
                'category' => 'dashboard'
            ],
            [
                'name' => 'dashboard.admin',
                'display_name' => 'Admin Dashboard',
                'description' => 'Dapat mengakses dashboard admin dengan statistik lengkap',
                'category' => 'dashboard'
            ],

            // User Management permissions
            [
                'name' => 'users.view',
                'display_name' => 'View Users',
                'description' => 'Dapat melihat daftar user',
                'category' => 'users'
            ],
            [
                'name' => 'users.create',
                'display_name' => 'Create Users',
                'description' => 'Dapat membuat user baru',
                'category' => 'users'
            ],
            [
                'name' => 'users.edit',
                'display_name' => 'Edit Users',
                'description' => 'Dapat mengedit data user',
                'category' => 'users'
            ],
            [
                'name' => 'users.delete',
                'display_name' => 'Delete Users',
                'description' => 'Dapat menghapus user',
                'category' => 'users'
            ],
            [
                'name' => 'users.manage_roles',
                'display_name' => 'Manage User Roles',
                'description' => 'Dapat mengelola role user (assign/remove)',
                'category' => 'users'
            ],

            // Role Management permissions
            [
                'name' => 'roles.view',
                'display_name' => 'View Roles',
                'description' => 'Dapat melihat daftar role',
                'category' => 'roles'
            ],
            [
                'name' => 'roles.create',
                'display_name' => 'Create Roles',
                'description' => 'Dapat membuat role baru',
                'category' => 'roles'
            ],
            [
                'name' => 'roles.edit',
                'display_name' => 'Edit Roles',
                'description' => 'Dapat mengedit role',
                'category' => 'roles'
            ],
            [
                'name' => 'roles.delete',
                'display_name' => 'Delete Roles',
                'description' => 'Dapat menghapus role custom',
                'category' => 'roles'
            ],
            [
                'name' => 'roles.assign_permissions',
                'display_name' => 'Assign Role Permissions',
                'description' => 'Dapat mengatur permissions untuk role',
                'category' => 'roles'
            ],

            // Attendance permissions
            [
                'name' => 'attendance.own',
                'display_name' => 'Own Attendance',
                'description' => 'Dapat mengelola absensi sendiri (clock in/out)',
                'category' => 'attendance'
            ],
            [
                'name' => 'attendance.view_all',
                'display_name' => 'View All Attendance',
                'description' => 'Dapat melihat absensi semua user',
                'category' => 'attendance'
            ],
            [
                'name' => 'attendance.edit_all',
                'display_name' => 'Edit All Attendance',
                'description' => 'Dapat mengedit absensi user lain',
                'category' => 'attendance'
            ],
            [
                'name' => 'attendance.approve_leave',
                'display_name' => 'Approve Leave Requests',
                'description' => 'Dapat menyetujui/menolak pengajuan izin',
                'category' => 'attendance'
            ],

            // Shift Management permissions
            [
                'name' => 'shifts.view',
                'display_name' => 'View Shifts',
                'description' => 'Dapat melihat jadwal shift',
                'category' => 'shifts'
            ],
            [
                'name' => 'shifts.create',
                'display_name' => 'Create Shifts',
                'description' => 'Dapat membuat shift baru',
                'category' => 'shifts'
            ],
            [
                'name' => 'shifts.edit',
                'display_name' => 'Edit Shifts',
                'description' => 'Dapat mengedit shift',
                'category' => 'shifts'
            ],
            [
                'name' => 'shifts.delete',
                'display_name' => 'Delete Shifts',
                'description' => 'Dapat menghapus shift',
                'category' => 'shifts'
            ],
            [
                'name' => 'shifts.assign_users',
                'display_name' => 'Assign Users to Shifts',
                'description' => 'Dapat mengatur user ke shift tertentu',
                'category' => 'shifts'
            ],

            // Reports permissions
            [
                'name' => 'reports.view_own',
                'display_name' => 'View Own Reports',
                'description' => 'Dapat melihat laporan absensi sendiri',
                'category' => 'reports'
            ],
            [
                'name' => 'reports.view_all',
                'display_name' => 'View All Reports',
                'description' => 'Dapat melihat laporan semua user',
                'category' => 'reports'
            ],
            [
                'name' => 'reports.export',
                'display_name' => 'Export Reports',
                'description' => 'Dapat mengekspor laporan ke Excel/PDF',
                'category' => 'reports'
            ],
            [
                'name' => 'reports.advanced',
                'display_name' => 'Advanced Reports',
                'description' => 'Dapat membuat laporan kustom dan analitik',
                'category' => 'reports'
            ],

            // Complaints/Leave permissions
            [
                'name' => 'complaints.create',
                'display_name' => 'Create Complaints/Leave',
                'description' => 'Dapat mengajukan izin/cuti/complaint',
                'category' => 'complaints'
            ],
            [
                'name' => 'complaints.view_own',
                'display_name' => 'View Own Complaints',
                'description' => 'Dapat melihat pengajuan sendiri',
                'category' => 'complaints'
            ],
            [
                'name' => 'complaints.view_all',
                'display_name' => 'View All Complaints',
                'description' => 'Dapat melihat semua pengajuan',
                'category' => 'complaints'
            ],
            [
                'name' => 'complaints.manage',
                'display_name' => 'Manage Complaints',
                'description' => 'Dapat mengelola dan merespon pengajuan',
                'category' => 'complaints'
            ],

            // Profile permissions
            [
                'name' => 'profile.edit_own',
                'display_name' => 'Edit Own Profile',
                'description' => 'Dapat mengedit profil sendiri',
                'category' => 'profile'
            ],
            [
                'name' => 'profile.view_others',
                'display_name' => 'View Other Profiles',
                'description' => 'Dapat melihat profil user lain',
                'category' => 'profile'
            ],
            [
                'name' => 'profile.edit_others',
                'display_name' => 'Edit Other Profiles',
                'description' => 'Dapat mengedit profil user lain',
                'category' => 'profile'
            ],

            // System permissions
            [
                'name' => 'system.settings',
                'display_name' => 'System Settings',
                'description' => 'Dapat mengakses pengaturan sistem',
                'category' => 'system'
            ],
            [
                'name' => 'system.audit_logs',
                'display_name' => 'View Audit Logs',
                'description' => 'Dapat melihat log aktivitas sistem',
                'category' => 'system'
            ],
        ];

        // Create permissions
        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                $permissionData + ['is_system' => true]
            );
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        $this->command->info('Permissions created and assigned successfully!');
    }

    private function assignPermissionsToRoles()
    {
        // Super Admin gets all permissions
        $superadminRole = Role::where('name', 'superadmin')->first();
        if ($superadminRole) {
            $allPermissions = Permission::all();
            $superadminRole->permissions()->sync($allPermissions->pluck('id'));
        }

        // Admin gets all permissions
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::all();
            $adminRole->permissions()->sync($allPermissions->pluck('id'));
        }

        // HR permissions (similar to manager but more focus on employee management)
        $hrRole = Role::where('name', 'hr')->first();
        if ($hrRole) {
            $hrPermissions = Permission::whereIn('name', [
                'dashboard.view', 'dashboard.admin',
                'users.view', 'users.create', 'users.edit', 'users.delete', 'users.manage_roles',
                'roles.view',
                'attendance.own', 'attendance.view_all', 'attendance.edit', 'attendance.approve_leave',
                'shifts.view', 'shifts.create', 'shifts.edit', 'shifts.delete', 'shifts.assign_users',
                'reports.view_own', 'reports.view_all', 'reports.export',
                'complaints.create', 'complaints.view_own', 'complaints.view_all', 'complaints.manage',
                'profile.edit_own', 'profile.view_others', 'profile.edit_others'
            ])->get();
            $hrRole->permissions()->sync($hrPermissions->pluck('id'));
        }

        // Manager permissions
        $managerRole = Role::where('name', 'manager')->first();
        if ($managerRole) {
            $managerPermissions = Permission::whereIn('name', [
                'dashboard.view', 'dashboard.admin',
                'users.view', 'users.edit', 'users.manage_roles',
                'roles.view',
                'attendance.own', 'attendance.view_all', 'attendance.approve_leave',
                'shifts.view', 'shifts.edit', 'shifts.assign_users',
                'reports.view_own', 'reports.view_all', 'reports.export',
                'complaints.create', 'complaints.view_own', 'complaints.view_all', 'complaints.manage',
                'profile.edit_own', 'profile.view_others', 'profile.edit_others'
            ])->get();
            $managerRole->permissions()->sync($managerPermissions->pluck('id'));
        }

        // Supervisor permissions
        $supervisorRole = Role::where('name', 'supervisor')->first();
        if ($supervisorRole) {
            $supervisorPermissions = Permission::whereIn('name', [
                'dashboard.view',
                'users.view',
                'roles.view',
                'attendance.own', 'attendance.view_all', 'attendance.approve_leave',
                'shifts.view',
                'reports.view_own', 'reports.view_all',
                'complaints.create', 'complaints.view_own', 'complaints.view_all', 'complaints.manage',
                'profile.edit_own', 'profile.view_others'
            ])->get();
            $supervisorRole->permissions()->sync($supervisorPermissions->pluck('id'));
        }

        // Employee permissions (basic user)
        $employeeRole = Role::where('name', 'employee')->first();
        if ($employeeRole) {
            $employeePermissions = Permission::whereIn('name', [
                'dashboard.view',
                'attendance.own',
                'shifts.view',
                'reports.view_own',
                'complaints.create', 'complaints.view_own',
                'profile.edit_own'
            ])->get();
            $employeeRole->permissions()->sync($employeePermissions->pluck('id'));
        }

        // Intern permissions (very limited)
        $internRole = Role::where('name', 'intern')->first();
        if ($internRole) {
            $internPermissions = Permission::whereIn('name', [
                'dashboard.view',
                'attendance.own',
                'shifts.view',
                'reports.view_own',
                'profile.edit_own'
            ])->get();
            $internRole->permissions()->sync($internPermissions->pluck('id'));
        }

        // Contractor permissions (limited, no complaints)
        $contractorRole = Role::where('name', 'contractor')->first();
        if ($contractorRole) {
            $contractorPermissions = Permission::whereIn('name', [
                'dashboard.view',
                'attendance.own',
                'shifts.view',
                'reports.view_own',
                'profile.edit_own'
            ])->get();
            $contractorRole->permissions()->sync($contractorPermissions->pluck('id'));
        }
    }
}