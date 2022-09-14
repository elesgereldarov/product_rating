<?php

namespace Brendfoni\ProductRating\Facade;

use Illuminate\Support\Facades\Facade;

class BrendfoniProductRatingFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'brendfoniproductrating';
    }
}
