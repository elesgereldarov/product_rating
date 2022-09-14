<?php

namespace Brendfoni\ProductRating\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Brendfoni\ProductRating\Models\ProductRating;
use Brendfoni\ProductRating\QueryFilters\StatusFilter;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class ProductRatingController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', ProductRating::class);

        $query = ProductRating::query()->orderBy('updated_at');

        $ratings = app(Pipeline::class)->send($query)
            ->through([
                StatusFilter::class,
            ])
            ->thenReturn()
            ->paginate($validated['per_page'] ?? 50)->withQueryString();

        return view('admin.product-ratings.index', compact('ratings'));
    }

    public function show(ProductRating $rating)
    {
        $this->authorize('view', $rating);
        $rating = ProductRating::with(['product'])->findOrFail($rating->id);
        return view('admin.product-ratings.show', compact('rating'));
    }


    public function accept(Request $request, ProductRating $rating)
    {
        $this->authorize('update', $rating);
        $rating->update(['status' => ProductRating::STATUS_ACCEPTED]);
        return redirect()->back();
    }

    public function cancel(Request $request, ProductRating $rating)
    {
        $this->authorize('update', $rating);
        $rating->update(['status' => ProductRating::STATUS_CANCELED, 'admin_comment' => $request->admin_comment]);
        return redirect()->back();
    }
}
