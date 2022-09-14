<?php

namespace Brendfoni\ProductRating\QueryFilters;

class StatusFilter
{
    public function handle($request, $next)
    {
        $builder = $next($request);
        $status = request('status');

        if (request()->filled('status')) {
            $builder->where('status',  '=', $status);
        }

        return $builder;
    }
}