<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

class Message
{
    public function handle(Builder $query, $next)
    {
        if (request()->has('message')) {
            $query->where('message', 'LIKE', '%'.request('message').'%');
        }

        return $next($query);
    }
}
