<?php

namespace Brendfoni\ProductRating\Http\Requests\Customer;

use Brendfoni\BlacklistWords\Rules\BlacklistWords;
use Brendfoni\Category\Models\CategoryMatching;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param CategoryMatching $categoryMatching
     * @return bool
     */


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rating' => 'required|integer|between:1,5',
            'review' => ['required', new BlacklistWords],
        ];
    }
}
