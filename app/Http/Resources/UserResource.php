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
            'roles' => $this->roles()->select('id', 'name', 'display_name')->get(),
            'permissions' => $this->getAllPermissions()->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'display_name' => $permission->display_name,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
