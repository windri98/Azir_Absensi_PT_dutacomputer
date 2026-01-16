<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class CheckOutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location' => 'required',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'location.required' => 'Lokasi wajib diisi',
        ];
    }
}
