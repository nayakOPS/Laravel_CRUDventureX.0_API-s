<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'imgLink' => $this->imgLink,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
        ];
    }
}
