<?php

namespace App\Http\Requests;

use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'nullable', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'priority' => ['sometimes', 'nullable', 'numeric', 'min:1', 'max:5'],
            'status' => ['sometimes', 'nullable', 'string', Rule::enum(TaskStatusEnum::class)],
        ];
    }

}
