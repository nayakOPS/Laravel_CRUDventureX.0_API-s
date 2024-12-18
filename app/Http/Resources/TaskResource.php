<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'projects' => new ProjectResource($this->whenLoaded('projects')),
            'assigned_to' => $this->when($this->assignedUser, fn() => $this->assignedUser->name),
        ];
    }
}
