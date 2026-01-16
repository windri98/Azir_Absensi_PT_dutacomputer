<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'type' => 'required|in:work_leave',
            'notes' => 'required|string',
            'work_leave_document' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Tanggal wajib diisi',
            'date.date' => 'Format tanggal tidak valid',
            'type.required' => 'Tipe izin wajib diisi',
            'type.in' => 'Tipe izin tidak valid',
            'notes.required' => 'Keterangan wajib diisi',
            'work_leave_document.mimes' => 'File harus berupa jpeg, jpg, png, atau pdf',
            'work_leave_document.max' => 'Ukuran file maksimal 5MB',
        ];
    }
}
