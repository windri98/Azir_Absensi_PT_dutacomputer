<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Check if relationships are already loaded (from eager loading in controller)
        $roles = $this->relationLoaded('roles')
            ? $this->roles->map(fn($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
            ])
            : $this->roles()->select('id', 'name', 'display_name')->get();

        // Get permissions from already-loaded roles if available
        $permissions = [];
        if ($this->relationLoaded('roles')) {
            $permissions = $this->roles
                ->flatMap(fn($role) => $role->permissions ?? [])
                ->unique('id')
                ->map(fn($permission) => [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'display_name' => $permission->display_name,
                ])
                ->values();
        } else {
            $permissions = $this->getAllPermissions()->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'display_name' => $permission->display_name,
                ];
            });
        }

        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'photo' => $this->photo,
            'annual_leave_quota' => $this->annual_leave_quota,
            'sick_leave_quota' => $this->sick_leave_quota,
            'special_leave_quota' => $this->special_leave_quota,
            'remaining_annual_leave' => $this->getRemainingAnnualLeave(),
            'remaining_sick_leave' => $this->getRemainingSickLeave(),
            'remaining_special_leave' => $this->getRemainingSpecialLeave(),
            'roles' => $roles,
            'permissions' => $permissions,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
