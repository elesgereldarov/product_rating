<?php

use Illuminate\Support\Facades\Route;
use Brendfoni\ProductRating\Http\Controllers\Customer\ProductRatingController;
use Brendfoni\ProductRating\Http\Controllers\Customer\ProductsController;
use Brendfoni\ProductRating\Http\Controllers\Customer\LikeController;

Route::group(['namespace' => '\\', 'middleware' => ['auth']], function () {
    Route::post('product-ratings/{product_id}', [ProductRatingController::class, 'create'])->name('customer.add-product-rating');
    Route::post('product-ratings/{product_id}/{rating_id}', [ProductRatingController::class, 'update'])->name('customer.update-product-rating');
    Route::get('account/delivered-products', [ProductsController::class, 'getProducts'])->name('customer.get-product-ratings');
    Route::get('account/product-ratings/', [ProductRatingController::class, 'index'])->name('customer.product-ratings.index');
    Route::get('product-rating/like/{rating_id}', [LikeController::class, 'likeDislike'])->name('product.product-rating.like');
});


Route::group(['namespace' => '\\',], function () {
    Route::get('product-rating/{product_slug}', [ProductsController::class, 'getRating'])->name('product.get-product-rating');
    Route::get('product/rating/{brand_slug}/{product_slug}', [ProductsController::class, 'ratings'])
        ->where('brand_slug', '^(?!.*nova|.*jarvis).*$')
        ->name('product.product-ratings');
});

