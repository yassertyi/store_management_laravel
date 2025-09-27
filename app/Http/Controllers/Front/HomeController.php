<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;

class HomeController extends Controller
{
    public function index()
    {
        // الفئات مع عدد المنتجات
        $categories = Category::withCount('products')->get();

        // المنتجات المميزة فقط + تحميل العلاقات (store, category, images, reviews)
        $featuredProducts = Product::with(['category', 'store', 'images', 'reviews'])
            ->where('is_featured', true)
            ->where('status', 'active')
            ->take(8)
            ->get();

        $allProducts = Product::with(['category', 'store', 'images', 'reviews'])
            ->where('status', 'active')
            ->take(12)
            ->get();

        // جميع المتاجر للفلترة
        $stores = Store::withCount(['products' => function($query) {
            $query->where('status', 'active');
        }])->get();

        return view('frontend.home.index', compact('categories', 'featuredProducts', 'allProducts','stores'));
    }
}
