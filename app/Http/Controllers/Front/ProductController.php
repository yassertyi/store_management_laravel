<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Store;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // بناء الاستعلام الأساسي
        $query = Product::with(['category', 'store', 'images', 'reviews'])
            ->where('status', 'active');

        // البحث بالنص
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // الفلترة بالفئة
        if ($request->has('category_id') && $request->category_id != 0) {
            $query->where('category_id', $request->category_id);
        }

        // الفلترة بالمتجر
        if ($request->has('store_id') && $request->store_id != 0) {
            $query->where('store_id', $request->store_id);
        }

        // الفلترة بالحالة (جديد، عروض، الأكثر مبيعاً)
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'new':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'sale':
                    $query->whereNotNull('compare_price');
                    break;
                case 'bestseller':
                    $query->whereHas('reviews', function($q) {
                        $q->havingRaw('AVG(rating) >= ?', [4.5]);
                    });
                    break;
            }
        }

        // الترتيب
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'name':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // الحصول على المنتجات مع التقسيم
        $products = $query->paginate(12);
        
        // جميع الفئات للفلترة
        $categories = Category::all();
        
        // جميع المتاجر للفلترة
        $stores = Store::withCount(['products' => function($query) {
            $query->where('status', 'active');
        }])->get();

        // الحصول على منتجات المفضلة للمستخدم إذا كان مسجلاً دخوله
        $wishlistProductIds = [];
        if (Auth::check()) {
            $wishlistProductIds = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        return view('frontend.home.products.index', compact('products', 'categories', 'stores', 'wishlistProductIds'));
    }

    // إضافة منتج إلى المفضلة
    public function addToWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً',
                'login_required' => true
            ]);
        }

        $request->validate([
            'product_id' => 'required|exists:products,product_id'
        ]);

        try {
            $existingWishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingWishlist) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج موجود بالفعل في المفضلة'
                ]);
            }

            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة المنتج إلى المفضلة'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الإضافة إلى المفضلة'
            ]);
        }
    }

    // إزالة منتج من المفضلة
    public function removeFromWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً'
            ]);
        }

        $request->validate([
            'product_id' => 'required|exists:products,product_id'
        ]);

        try {
            $wishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($wishlist) {
                $wishlist->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => 'تمت إزالة المنتج من المفضلة'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'المنتج غير موجود في المفضلة'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الإزالة من المفضلة'
            ]);
        }
    }

    // الحصول على حالة المفضلة للمنتج
    public function checkWishlistStatus($productId)
    {
        if (!Auth::check()) {
            return response()->json([
                'in_wishlist' => false
            ]);
        }

        $inWishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'in_wishlist' => $inWishlist
        ]);
    }
}