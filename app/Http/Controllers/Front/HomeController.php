<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Coupon;

class HomeController extends Controller
{
    public function index()
    {
        // الفئات الرئيسية (بدون أب)
        $categories = Category::whereNull('parent_id')
                              ->withCount('products')
                              ->get();

        // المنتجات المميزة
        $featuredProducts = Product::where('is_featured', true)
                                   ->with('category', 'store')
                                   ->take(8)
                                   ->get();

        // المنتجات الأكثر مبيعًا حسب عدد الطلبات
        $bestSellingProducts = Product::withCount('orderItems')
                                      ->orderByDesc('order_items_count')
                                      ->take(8)
                                      ->get();

        // العروض/الخصومات النشطة حاليًا
        $offers = Coupon::where('is_active', true)
                        ->whereDate('start_date', '<=', now())
                        ->whereDate('expiry_date', '>=', now())
                        ->take(2) // عرضين فقط مثلاً
                        ->get();

        // إرسال البيانات إلى الصفحة
        return view('frontend.home.index', compact(
            'categories',
            'featuredProducts',
            'bestSellingProducts',
            'offers'
        ));
    }
}
