<?php

namespace App\Http\Resources;

use App\Enums\TaskStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskListResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => TaskStatusEnum::from($this->status),
            'completed_at' => $this->completed_at,
            'parent' => new TaskResource($this->parent),
            'author' => new TaskAuthorResource($this->user),
            'children' => TaskListResource::collection($this->children)
        ];
    }
}
