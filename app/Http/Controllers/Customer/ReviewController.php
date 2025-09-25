<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        // جلب التقييمات الخاصة بالمستخدم مع Pagination
        $reviews = Review::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10); // يمكن تغيير العدد حسب الحاجة

        return view('frontend.customers.reviews.index', compact('reviews'));
    }

    public function destroy($id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);
        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف التقييم بنجاح'
        ]);
    }
}
