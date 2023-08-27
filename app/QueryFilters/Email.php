<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

class Email
{
    public function handle(Builder $query, $next)
    {
        if (request()->has('email')) {
            $query->where('email', 'LIKE', '%'.request('email').'%');
        }

        return $next($query);
    }
}
