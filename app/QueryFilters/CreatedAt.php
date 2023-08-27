<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

class CreatedAt
{
    use FilterDateAt;

    public function handle(Builder $query, $next)
    {
        $query = $this->filterDate_at($query, 'created_at');

        return $next($query);
    }
}
