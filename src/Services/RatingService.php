<?php

namespace Brendfoni\ProductRating\Services;


use Brendfoni\ProductRating\Models\ProductRating;

class RatingService
{

    public function getCountRatings($productId)
    {

        $count = [];
        for ($i = 1; $i <= 5; $i++) {
            switch ($i) {
                case 1:
                    $count[1] = ProductRating::where('product_id', $productId)->where('product_rating', '1')
                        ->where('status', ProductRating::STATUS_ACCEPTED)->count();
                    break;
                case 2:
                    $count[2] = ProductRating::where('product_id', $productId)->where('product_rating', '2')
                        ->where('status', ProductRating::STATUS_ACCEPTED)->count();
                    break;
                case 3:
                    $count[3] = ProductRating::where('product_id', $productId)->where('product_rating', '3')
                        ->where('status', ProductRating::STATUS_ACCEPTED)->count();
                    break;
                case 4:
                    $count[4] = ProductRating::where('product_id', $productId)->where('product_rating', '4')
                        ->where('status', ProductRating::STATUS_ACCEPTED)->count();
                    break;
                case 5:

                    $count[5] = ProductRating::where('product_id', $productId)->where('product_rating', '5')
                        ->where('status', ProductRating::STATUS_ACCEPTED)->count();
                    break;

            }
        }
        return $count;

    }

}
