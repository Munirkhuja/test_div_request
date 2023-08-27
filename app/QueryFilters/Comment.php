<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

class Comment
{
    public function handle(Builder $query, $next)
    {
        if (request()->has('comment')) {
            $query->where('comment', 'LIKE', '%'.request('comment').'%');
        }

        return $next($query);
    }
}
