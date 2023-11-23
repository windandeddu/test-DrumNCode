<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'numeric', 'min:1', 'max:5'],
            'parent_id' => ['sometimes', 'nullable', 'exists:tasks,id'],
        ];
    }

}
