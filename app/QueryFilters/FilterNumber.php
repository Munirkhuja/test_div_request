<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

trait FilterNumber
{
    public function filterNumber(Builder $query, $key)
    {
        $equalHas = request()->has("{$key}");
        if ($equalHas) {
            $query->where("{$key}", request("{$key}"));
        } else {
            $fromHas = request()->has("{$key}_from");
            $toHas = request()->has("{$key}_to");
            if ($fromHas && $toHas) {
                $query->whereBetween("{$key}", [request("{$key}_from"), request("{$key}_to")]);
            }
            if ($fromHas && ! $toHas) {
                $query->where("{$key}", '>=', request("{$key}_from"));
            }
            if (! $fromHas && $toHas) {
                $query->where("{$key}", '<=', request("{$key}_to"));
            }
        }

        return $query;
    }
}
