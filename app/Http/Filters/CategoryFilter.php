<?php

namespace App\Http\Filters;

class CategoryFilter extends Filter
{
    public function apply(): \Illuminate\Database\Eloquent\Builder
    {
        if ($this->request->filled('id')) {
            $this->query->where('id', $this->request->id);
        }

        if ($this->request->filled('name')) {
            $this->query->where('name', 'LIKE', '%' . $this->request->name . '%');
        }

        return $this->query;
    }
}
