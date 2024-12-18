<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filter
{
    protected Request $request;
    protected Builder $query;

    public function __construct(Request $request, Builder $query)
    {
        $this->request = $request;
        $this->query = $query;
    }

    abstract public function apply(): Builder;
}
