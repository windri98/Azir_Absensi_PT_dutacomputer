<?php

namespace App\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:100',
            'priority' => 'nullable|in:low,medium,normal,high,urgent',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'admin_notes' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul keluhan wajib diisi',
            'description.required' => 'Deskripsi keluhan wajib diisi',
            'attachment.mimes' => 'File harus berupa pdf, doc, docx, jpg, jpeg, atau png',
            'attachment.max' => 'Ukuran file maksimal 5MB',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
        ];
    }
}
