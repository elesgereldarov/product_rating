<?php

namespace Brendfoni\ProductRating\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\User\Order;
use App\Models\User\OrderProduct;
use Brendfoni\ProductRating\Models\ProductRating;
use Brendfoni\ProductRating\Http\Requests\Customer\CreateProductRatingRequest;
use Brendfoni\ProductRating\QueryFilters\ExistedRatingFilter;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class ProductRatingController extends Controller
{
    public function index()
    {
        $accepted = ProductRating::where('customer_id', user('id'))->where('status', ProductRating::STATUS_ACCEPTED)->count();
        $waiting = ProductRating::where('customer_id', user('id'))->where('status', ProductRating::STATUS_WAITING)->count();
        $canceled = ProductRating::where('customer_id', user('id'))->where('status', ProductRating::STATUS_CANCELED)->count();

        $ratedProducts = Product::whereHas('orderProduct', function ($query) {
            $query->where('orders.customer_id', user('id'));
        })->with('rating')
            ->whereHas('rating', function ($query) {
            $query->where('customer_id', user('id'));
        })
            ->whereHas('orderProduct.status', function ($query) {
                $query->where('name', 'delivered');
            })->whereHas('rating', function ($query) {
                $query->where('customer_id', user('id'));
            })->with(['orderProduct' => function($query){
                $query->where('order_products.customer_id', '=', user('id'))
                ->orderBy('order_products.created_at', 'desc');
            }])->get();

        $unratedProducts = Product::whereHas('orderProduct', function ($query) {
            $query->where('orders.customer_id', user('id'));
        })
            ->whereHas('orderProduct.status', function ($query) {
                $query->where('name', 'delivered');
            })->whereDoesntHave('rating', function ($query) {
                $query->where('customer_id', user('id'));
            })->with(['orderProduct' => function($query){
                $query->where('order_products.customer_id', '=', user('id'))
                ->orderBy('order_products.created_at', 'desc');
            }])->get();

        return view('shared.account.rating', compact('canceled', 'accepted', 'waiting', 'ratedProducts', 'unratedProducts'));
    }

    public function create(CreateProductRatingRequest $request, $product_id)
    {
        $validated = $request->validated();
        $product = Order::where(['customer_id' => user('id')])
            ->whereHas('status', function ($query) {
                $query->where('name', ['delivered']);
            })
            ->whereHas('orderProduct', function ($query) use ($product_id) {
                $query->where('product_id', $product_id);
            })
            ->orderByDesc('id')
            ->exists();

        $existedRating = ProductRating::where('customer_id', user('id'))
            ->where('product_id', $product_id)->exists();
        if ($product && !$existedRating) {
            ProductRating::create([
                'customer_id' => user('id'),
                'product_id' => $product_id,
                'product_rating' => $validated['rating'],
                'review' => $validated['review'],
                'status' => ProductRating::STATUS_WAITING,
            ]);
            return \response()->json([]);
        }
        return \response()->json([], 422);
    }

    public function update(CreateProductRatingRequest $request, $product_id, $id)
    {
        $request = $request->validated();
        $product = Order::where(['customer_id' => user('id')])
            ->whereHas('status', function ($query) {
                $query->where('name', ['delivered']);
            })
            ->whereHas('orderProduct', function ($query) use ($product_id) {
                $query->where('product_id', $product_id);
            })
            ->orderByDesc('id')
            ->exists();

        $existedRating = ProductRating::where('customer_id', user('id'))
            ->where('product_id', $product_id)->exists();
        if ($product && $existedRating) {
            $rating = ProductRating::where('id', $id);
            $rating->update([
                'product_rating' => $request['rating'],
                'review' => $request['review'],
                'status' => ProductRating::STATUS_WAITING,
            ]);
            return \response()->json([]);
        }
        return \response()->json([], 422);
    }
}
