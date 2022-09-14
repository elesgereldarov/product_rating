<?php

namespace Brendfoni\ProductRating\Models;

use App\Models\Product\Product;
use App\Models\User\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'product_rating',
        'review',
        'admin_comment',
        'status'
    ];


    public const STATUS_WAITING = 'waiting';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_CANCELED = 'canceled';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Customer::class, 'customers');
    }


    public function getMyLikeAttribute()
    {
        return CustomerRating::where('customer_id', user('id'))
            ->where('rating_id', $this->id)->exists();
    }

    public function getLikesCountAttribute()
    {
        return CustomerRating::where('rating_id', $this->id)->count();
    }

    public static function getHiddenName($text)
    {
        $firstLetter = $text[0];
        $textHidden = substr($text, 1);
          return $firstLetter. preg_replace('/\S/', '*', $textHidden);
    }
}
