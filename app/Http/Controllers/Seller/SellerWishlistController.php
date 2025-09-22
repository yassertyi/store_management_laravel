<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class SellerWishlistController extends Controller
{
    /**
     * عرض قائمة المفضلة الخاصة بالبائع
     */
    public function index()
    {
        // الحصول على متجر البائع
        $storeId = Auth::user()->seller->store_id;

        // جلب قائمة المفضلة للمنتجات التابعة لمتجر البائع فقط
        $wishlists = Wishlist::whereHas('product', function ($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->latest()->paginate(10);

        return view('frontend.Seller.dashboard.reviews.wishlists', compact('wishlists'));
    }

    /**
     * حذف منتج من المفضلة
     */
    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);

        // التحقق من أن المنتج ينتمي للمتجر الخاص بالبائع
        if ($wishlist->product->store_id != Auth::user()->seller->store_id) {
            return redirect()->back()->with('error', 'لا يمكنك حذف هذا المنتج.');
        }

        $wishlist->delete();

        return redirect()->back()->with('success', 'تم إزالة المنتج من المفضلة بنجاح.');
    }
}
