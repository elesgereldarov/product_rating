<?php

use Illuminate\Support\Facades\Route;
use Brendfoni\ProductRating\Http\Controllers\Admin\ProductRatingController;

Route::group(['namespace' => '\\',], function () {
    Route::resource('product-rating', ProductRatingController::class);
    Route::get('product-ratings/accept/{rating}', [ProductRatingController::class, 'accept'])->name('product-rating-accept');
    Route::get('product-ratings/cancel/{rating}', [ProductRatingController::class, 'cancel'])->name('product-rating-cancel');
    //    Route::put('product-ratings/{id}/approve', [ProductRatingController::class, 'approve'])->name('admin.product-ratings.approve');
    //    Route::delete('product-ratings/{id}', [ProductRatingController::class, 'delete'])->name('admin.product-ratings.delete');
});
