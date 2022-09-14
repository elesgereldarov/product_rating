<?php


namespace Brendfoni\ProductRating\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerRating extends Model
{
    public $fillable = ['customer_id', 'rating_id'];

    public $table = 'customer_rating';






}
