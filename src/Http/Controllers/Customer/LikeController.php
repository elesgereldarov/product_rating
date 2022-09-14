<?php


namespace Brendfoni\ProductRating\Http\Controllers\Customer;


use App\Http\Controllers\Controller;
use Brendfoni\ProductRating\Models\CustomerRating;

class LikeController  extends Controller
{

    public function likeDislike($ratingId)
    {
        $like = CustomerRating::where('customer_id', user('id'))->where('rating_id', $ratingId)->first();
        if($like) {
            $like->delete();
        }
        else {
            CustomerRating::create(['customer_id' => user('id'), 'rating_id' => $ratingId]);
        }
    }

}
