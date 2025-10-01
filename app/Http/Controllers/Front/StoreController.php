<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Product;
use App\Models\StoreAddress;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function show($storeId)
    {
        $store = Store::with([
            'user',
            'addresses',
            'phones',
            'products.images',
            'products.category',
            'products.reviews',
            'products.store'
        ])->findOrFail($storeId);

        // حساب متوسط تقييم المتجر
        $storeRating = $this->calculateStoreRating($store);
        $totalProducts = $store->products->count();
        
        // منتجات المتجر المميزة
        $featuredProducts = $store->products()
            ->where('is_featured', true)
            ->where('status', 'active')
            ->with(['images', 'store', 'reviews', 'category'])
            ->take(8)
            ->get();

        // أحدث منتجات المتجر
        $latestProducts = $store->products()
            ->where('status', 'active')
            ->with(['images', 'store', 'reviews', 'category'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('frontend.home.store.store-detail', compact(
            'store',
            'storeRating',
            'totalProducts',
            'featuredProducts',
            'latestProducts'
        ));
    }

    public function products($storeId, Request $request)
    {
        $store = Store::with('user')->findOrFail($storeId);
        
        $query = Product::where('store_id', $storeId)
            ->where('status', 'active')
            ->with(['images', 'category', 'reviews', 'store']);

        // فلترة حسب الفئة
        if ($request->has('category_id') && $request->category_id != 0) {
            $query->where('category_id', $request->category_id);
        }

        // فلترة حسب السعر
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // ترتيب النتائج
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

        $products = $query->paginate(12);

        return view('frontend.home.store.store-products', compact('store', 'products'));
    }

    public function index(Request $request)
    {
        // بناء الاستعلام الأساسي
        $query = Store::with(['products.images', 'products.reviews', 'addresses'])
            ->where('status', 'active')
            ->whereHas('products', function($query) {
                $query->where('status', 'active');
            })
            ->withCount(['products' => function($query) {
                $query->where('status', 'active');
            }]);

        // البحث باسم المتجر
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('store_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // الفلترة حسب المدينة
        if ($request->has('city') && $request->city != 'all') {
            $query->whereHas('addresses', function($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        // الترتيب
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'name':
                $query->orderBy('store_name', 'asc');
                break;
            case 'products':
                $query->orderBy('products_count', 'desc');
                break;
            case 'rating':
                // ترتيب حسب متوسط التقييمات
                $query->withAvg('products.reviews', 'rating')->orderBy('products_reviews_avg_rating', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $stores = $query->paginate(12);

        // المدن المتاحة للفلترة
        $cities = StoreAddress::select('city')
            ->distinct()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->pluck('city');

        // إحصائيات إضافية
        $totalStores = Store::where('status', 'active')->count();
        $totalProducts = Product::where('status', 'active')->count();

        return view('frontend.home.store.all-stores', compact(
            'stores', 
            'cities', 
            'totalStores', 
            'totalProducts'
        ));
    }

    private function calculateStoreRating($store)
    {
        $totalRating = 0;
        $totalReviews = 0;

        foreach ($store->products as $product) {
            $productReviews = $product->reviews->where('is_approved', true);
            foreach ($productReviews as $review) {
                $totalRating += $review->rating;
                $totalReviews++;
            }
        }

        return $totalReviews > 0 ? round($totalRating / $totalReviews, 1) : 0;
    }

    // دالة جديدة للحصول على المتاجر المميزة للصفحة الرئيسية
    public function getFeaturedStores()
    {
        return Store::with(['products.images', 'products.reviews'])
            ->where('status', 'active')
            ->whereHas('products', function($query) {
                $query->where('status', 'active');
            })
            ->withCount(['products' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
    }
}