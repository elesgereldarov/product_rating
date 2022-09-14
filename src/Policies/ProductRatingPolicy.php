<?php

namespace Brendfoni\ProductRating\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Brendfoni\ProductRating\Models\ProductRating;
use App\User;

class ProductRatingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the User can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('browse_product-rating');
    }

    /**
     * Determine whether the User can view the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $user, ProductRating $productRatingShippingWeight)
    {
        return $user->hasPermission('read_product-rating');
    }

    /**
     * Determine whether the User can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('add_product-rating');
    }

    /**
     * Determine whether the User can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductRating  $productRatingShippingWeight
     * @return mixed
     */
    public function update(User $user, ProductRating $productRatingShippingWeight)
    {
        return $user->hasPermission('edit_product-rating');
    }

    /**
     * Determine whether the User can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductRating  $productRatingShippingWeight
     * @return mixed
     */
    public function delete(User $user, ProductRating $productRatingShippingWeight)
    {
        return $user->hasPermission('delete_product-rating');
    }
}
