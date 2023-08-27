<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

class CommentUserID
{
    use FilterNumber;

    public function handle(Builder $query, $next)
    {
        $query = $this->filterNumber($query, 'comment_user_id');

        return $next($query);
    }
}
