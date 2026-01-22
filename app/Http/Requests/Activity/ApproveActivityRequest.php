<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class ApproveActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('activities.approve');
    }

    public function rules(): array
    {
        return [
            'rejected_reason' => 'nullable|string|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'rejected_reason.min' => 'Alasan penolakan minimal 10 karakter',
        ];
    }
}
