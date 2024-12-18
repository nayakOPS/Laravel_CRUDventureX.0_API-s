<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,completed',
            'projects_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
        ];
    }
}
