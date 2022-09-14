<?php

namespace Brendfoni\ProductRating\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\User\Order;
use App\Models\User\OrderProduct;
use App\Repositories\Models\ProductRepository;
use App\Models\Product\ProductVariant;
use Brendfoni\ProductRating\Models\ProductRating;
use Brendfoni\ProductRating\Http\Requests\Customer\CreateProductRatingRequest;
use Brendfoni\ProductRating\QueryFilters\ExistedRatingFilter;
use Brendfoni\ProductRating\Services\RatingService;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class ProductsController extends Controller
{

    public $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }


    public function getProducts(Request $request)
    {
        $products = Product::whereHas('orderProduct', function ($query) {
            $query->where('orders.customer_id', user('id'));
        })
            ->whereHas('orderProduct.status', function ($query) {
                $query->where('name', 'delivered');
            });

        $products = app(Pipeline::class)->send($products)
            ->through([
                ExistedRatingFilter::class,
            ])
            ->thenReturn()
            ->simplePaginate(50)->toArray();

        return \response()->json($products);
    }

    public function getRating($productSlug)
    {
        $product = Product::where('slug', $productSlug)->first();
        $productRating = ProductRating::where('product_id', $product->id);

        $productRating = app(Pipeline::class)->send($productRating)
            ->thenReturn()
            ->simplePaginate(50);

        return \response()->json($productRating);
    }

    public function ratings(Request $request, $brand_slug = null, $product_slug = null)
    {
        $product = Product::whereSlug($product_slug)
            ->forCustomer()
            ->with([
                'mainCategories.recursiveParentCategory',
                'families',
                'sizeVariant',
                'addtionalImages',
                'childProducts.sizeVariant',
                'ratingAccepted'
            ]);
        if($request->filled('search')) {
            $product->whereHas('ratingAccepted', function ($query){
                $query->where('review', 'like', '%'.\request()->search.'%');
            });
        }

        $product = $product->firstOrFail();

        $relatedProducts = ProductRepository::getForRelatedProducts($product);

        $features = ProductVariant::where('product_id', $product->id)
            ->with(['variant', 'feature'])->get()->sortBy('feature.position');

        $features = $features->filter(function ($featureValue) {
            return $featureValue->feature->name != 'Brand';
        });

        $showFilters = false;
        $categories = $this->getCategorySlugs($product->mainCategories->first(), collect());
        $breadCrumb = $this->indexBreadCrumb($categories);
        $countRating = $this->ratingService->getCountRatings($product->id);

        $toRate = $unratedProducts = Product::where('id', '=', $product->id)->whereHas('orderProduct', function ($query) {
            $query->where('orders.customer_id', user('id'));
        })
            ->whereHas('orderProduct.status', function ($query) {
                $query->where('name', 'delivered');
            })->whereDoesntHave('rating', function ($query) {
                $query->where('customer_id', user('id'));
            })->with(['orderProduct' => function($query){
                $query->where('order_products.customer_id', '=', user('id'))
                ->orderBy('order_products.created_at', 'desc');
            }])->exists();

        return view(
            'shared.containers.pages.product.rating',
            compact('countRating', 'product', 'features', 'showFilters', 'breadCrumb', 'relatedProducts', 'toRate')
        );
    }

    protected function indexBreadCrumb($categories)
    {
        $breadCrumb = collect();
        $categorySlugs = collect();

        foreach ($categories as $category) {
            $categorySlugs->push($category->slug);
            $breadCrumb->push([
                'url' => route('product.index', $categorySlugs->implode('+')),
                'name' => $category->name,
            ]);
        }

        return $breadCrumb;
    }

    /*
     * For show method
     */
    protected function getCategorySlugs($category, $categorySlugs)
    {
        $categorySlugs->prepend($category);

        if ($category->recursiveParentCategory) {
            $this->getCategorySlugs($category->recursiveParentCategory, $categorySlugs);
        }

        return $categorySlugs;

    }
}
