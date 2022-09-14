<?php

namespace Brendfoni\ProductRating;

class BrendfoniProductRating
{
    public function adminRoutes()
    {
        require __DIR__ . '/../routes/brendfoni_product_rating_admin.php';
    }

    public function customerRoutes()
    {
        require __DIR__ . '/../routes/brendfoni_product_rating_customer.php';
    }
}
