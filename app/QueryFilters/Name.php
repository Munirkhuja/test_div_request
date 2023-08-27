<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

class Name
{
    public function handle(Builder $query, $next)
    {
        if (request()->has('name')) {
            $query->where('name', 'LIKE', '%'.request('name').'%');
        }

        return $next($query);
    }
}
