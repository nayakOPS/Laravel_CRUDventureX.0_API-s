<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'sometimes|integer',
            'name' => 'sometimes|string',
            'status' => 'sometimes|string|in:pending,completed',
            'project_id' => 'sometimes|integer',
            'assigned_to' => 'sometimes|integer',
        ];
    }
}
