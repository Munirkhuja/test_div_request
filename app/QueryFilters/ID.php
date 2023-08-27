<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

class ID
{
    use FilterNumber;

    public function handle(Builder $query, $next)
    {
        $query = $this->filterNumber($query, 'id');

        return $next($query);
    }
}
