<?php

namespace App\Http\Filters;

class ProjectFilter extends Filter
{
    public function apply(): \Illuminate\Database\Eloquent\Builder
    {
        if ($this->request->filled('id')) {
            $this->query->where('id', $this->request->id);
        }

        if ($this->request->filled('name')) {
            $this->query->where('name', 'LIKE', '%' . $this->request->name . '%');
        }

        if ($this->request->filled('category_id')) {
            $this->query->where('category_id', $this->request->category_id);
        }

        return $this->query;
    }
}
