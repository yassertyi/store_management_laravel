<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\Store;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            $query->where(function ($q) use ($search) {
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
                    $query->whereHas('reviews', function ($q) {
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
        $stores = Store::withCount(['products' => function ($query) {
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

    public function show($id)
    {
        $product = Product::with([
            'category',
            'store',
            'primaryImage',
            'images',
            'variants',
            'reviews.user',
            'reviews.helpfuls'
        ])->findOrFail($id);

        $mainImage = $product->primaryImage ?? $product->images->first();
        $otherImages = $product->images->where('image_id', '!=', $mainImage->image_id);

        // التحقق من وجود المنتج في المفضلة
        $inWishlist = false;
        if (Auth::check()) {
            $inWishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $id)
                ->exists();
        }

        // حساب متوسط التقييم
        $averageRating = $product->reviews->avg('rating') ?? 0;
        $totalReviews = $product->reviews->count();

        // توزيع التقييمات
        $ratingDistribution = [
            5 => $product->reviews->where('rating', 5)->count(),
            4 => $product->reviews->where('rating', 4)->count(),
            3 => $product->reviews->where('rating', 3)->count(),
            2 => $product->reviews->where('rating', 2)->count(),
            1 => $product->reviews->where('rating', 1)->count(),
        ];

        // منتجات مشابهة
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('product_id', '!=', $id)
            ->where('status', 'active')
            ->with('images')
            ->take(4)
            ->get();

        return view('frontend.home.products.show', compact(
            'product',
            'mainImage',
            'otherImages',
            'inWishlist',
            'averageRating',
            'totalReviews',
            'ratingDistribution',
            'relatedProducts'
        ));
    }

    public function storeReview(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|between:1,5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string|min:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'البيانات غير صالحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // التحقق من أن المستخدم قد طلب هذا المنتج
            $hasPurchased = Auth::user()->orders()
                ->whereHas('orderItems', function ($query) use ($productId) {
                    $query->where('product_id', $productId);
                })
                ->exists();

            if (!$hasPurchased) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب شراء المنتج أولاً قبل إضافة التقييم'
                ], 403);
            }

            // التحقق من عدم وجود تقييم سابق
            $existingReview = Review::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'لقد قمت بتقييم هذا المنتج مسبقاً'
                ], 409);
            }

            $review = Review::create([
                'product_id' => $productId,
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'title' => $request->title,
                'comment' => $request->comment,
                'is_approved' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة التقييم بنجاح',
                'review' => $review->load('user')
            ]);

        } catch (\Exception $e) {
            \Log::error('Review creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة التقييم'
            ], 500);
        }
    }

    public function toggleHelpful(Request $request, $reviewId)
{
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'يجب تسجيل الدخول أولاً'
        ], 401);
    }

    try {
        $review = Review::findOrFail($reviewId);

        $existingHelpful = $review->helpfuls()
            ->where('user_id', Auth::id())
            ->first();

        if ($existingHelpful) {
            $existingHelpful->delete();
            $isHelpful = false;
            $message = 'تم إزالة التفضيل';
        } else {
            $review->helpfuls()->create([
                'user_id' => Auth::id(),
                'is_helpful' => true
            ]);
            $isHelpful = true;
            $message = 'شكراً لك! تم تسجيل تفضيلك';
        }

        $helpfulCount = $review->helpfuls()->count();

        return response()->json([
            'success' => true,
            'is_helpful' => $isHelpful,
            'helpful_count' => $helpfulCount,
            'message' => $message
        ]);

    } catch (\Exception $e) {
        \Log::error('Toggle helpful error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء التحديث'
        ], 500);
    }
}

}
