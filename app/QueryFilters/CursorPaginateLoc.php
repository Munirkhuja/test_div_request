<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

class CursorPaginateLoc
{
    public function handle(Builder $query, $next)
    {
        if (request()->has('limit') && request('limit') < config('div-api.max_limit')) {
            $limit = request('limit');
        } else {
            $limit = config('div-api.limit');
        }

        return $next($query->cursorPaginate($limit)->withQueryString());
    }
}
