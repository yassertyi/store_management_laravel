<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Auth::user()->wishlists()
            ->with(['product.images', 'product.category'])
            ->whereHas('product')
            ->latest()
            ->paginate(12);

        return view('frontend.customers.dashboard.wishlist.index', compact('wishlists'));
    }

    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            return response()->json(['error' => 'غير مسموح'], 403);
        }

        try {
            $wishlist->delete();
            return response()->json(['success' => true, 'message' => 'تمت إزالة المنتج من المفضلة']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'حدث خطأ أثناء الإزالة'], 500);
        }
    }
}
