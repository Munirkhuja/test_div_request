<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

class Status
{
    public function handle(Builder $query, $next)
    {
        if (request()->has('status')) {
            $query->where('status', request('status'));
        }

        return $next($query);
    }
}
