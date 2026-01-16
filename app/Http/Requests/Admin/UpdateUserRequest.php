<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('users.edit');
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'employee_id' => 'required|string|max:50|unique:users,employee_id,' . $userId,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => 'nullable|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'annual_leave_quota' => 'required|integer|min:0|max:30',
            'sick_leave_quota' => 'required|integer|min:0|max:30',
            'special_leave_quota' => 'required|integer|min:0|max:30',
            'roles' => 'array',
            'shifts' => 'array',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'ID Karyawan wajib diisi',
            'employee_id.unique' => 'ID Karyawan sudah terdaftar',
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'gender.required' => 'Jenis kelamin wajib diisi',
            'gender.in' => 'Jenis kelamin tidak valid',
        ];
    }
}
