<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
   use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{

public function index()
{
    $userId = Auth::id();

    $totalOrders = Order::where('customer_id', $userId)->count();
    $processingOrders = Order::where('customer_id', $userId)
                             ->where('status', 'processing')->count();
    $wishlistItems = Wishlist::where('user_id', $userId)->count();
    $totalReviews = Review::where('user_id', $userId)->count();

    $recentOrders = Order::where('customer_id', $userId)
                         ->orderBy('created_at', 'desc')
                         ->take(5)
                         ->get();

    $notifications = Notification::where('user_id', $userId)
                                 ->orderBy('created_at', 'desc')
                                 ->take(5)
                                 ->get();

    // المنتجات التي شاهدها مؤخراً، يمكن أن يكون لديك جدول tracking أو تستخدم آخر المنتجات التي تم عرضها
    $recentlyViewed = Product::orderBy('created_at', 'desc')->take(5)->get();

    $data = [
        'total_orders' => $totalOrders,
        'processing_orders' => $processingOrders,
        'wishlist_items' => $wishlistItems,
        'total_reviews' => $totalReviews,
        'recent_orders' => $recentOrders,
        'notifications' => $notifications,
        'recently_viewed' => $recentlyViewed
    ];

    return view('frontend.customers.dashboard.home', compact('data'));
}

}
