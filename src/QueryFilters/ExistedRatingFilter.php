<?php

namespace Brendfoni\ProductRating\QueryFilters;


class ExistedRatingFilter
{
    public function handle($request, $next)
    {
        $builder = $next($request);

        $filter = request('filter');
        if (request()->filled('filter')) {
            if ($filter === 'my') {
                $builder->whereHas('rating', function ($query) {
                    $query->where('customer_id', user('id'));
                });
            } else {
                $builder->whereDoesntHave('rating', function ($query) {
                    $query->where('customer_id', user('id'));
                });
            }
        }

        return $builder;
    }
}
