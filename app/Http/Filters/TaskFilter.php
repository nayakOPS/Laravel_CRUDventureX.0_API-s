<?php

namespace App\Http\Filters;
use Illuminate\Database\Eloquent\Builder;

class TaskFilter extends Filter
{
    public function apply():Builder
    {
        if ($this->request->filled('id')) {
            $this->query->where('id', $this->request->id);
        }

        if ($this->request->filled('name')) {
            $this->query->where('name', 'LIKE', '%' . $this->request->name . '%');
        }

        if ($this->request->filled('status')) {
            $this->query->where('status', $this->request->status);
        }

        if ($this->request->filled('project_id')) {
            $this->query->where('project_id', $this->request->project_id);
        }

        if ($this->request->filled('assigned_to')) {
            $this->query->where('assigned_to', $this->request->assigned_to);
        }

        return $this->query;
    }
}
