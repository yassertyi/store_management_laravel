<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $customer = $user->customer;
        
        if (!$customer) {
            // إذا لم يكن هناك سجل customer، قم بإنشائه
            $customer = Customer::create([
                'user_id' => $user->user_id,
                'loyalty_points' => 0,
                'total_orders' => 0
            ]);
        }

        $customerId = $customer->customer_id;

        $totalOrders = Order::where('customer_id', $customerId)->count();
        
        $processingOrders = Order::where('customer_id', $customerId)
                                 ->whereIn('status', ['pending', 'processing'])
                                 ->count();
                                 
        $wishlistItems = Wishlist::where('user_id', $user->user_id)->count();
        $totalReviews = Review::where('user_id', $user->user_id)->count();

        $recentOrders = Order::where('customer_id', $customerId)
                             ->with(['orderItems.product.images'])
                             ->orderBy('created_at', 'desc')
                             ->take(5)
                             ->get()
                             ->map(function($order) {
                                 $order->status_color = $this->getStatusColor($order->status);
                                 $order->status_text = $this->getStatusText($order->status);
                                 return $order;
                             });

        $notifications = Notification::where('user_id', $user->user_id)
                                     ->orderBy('created_at', 'desc')
                                     ->take(5)
                                     ->get();

        // المنتجات التي شاهدها مؤخراً (يمكن استبدالها بجدول tracking لاحقاً)
        $recentlyViewed = Product::with(['images', 'store'])
                                 ->where('status', 'active')
                                 ->orderBy('created_at', 'desc')
                                 ->take(4)
                                 ->get()
                                 ->map(function($product) {
                                     $product->image_url = $product->images->first()->image_path ?? '/static/images/default-product.jpg';
                                     return $product;
                                 });

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

    /**
     * الحصول على لون حالة الطلب
     */
    private function getStatusColor($status)
    {
        $colors = [
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger'
        ];

        return $colors[$status] ?? 'secondary';
    }

    /**
     * الحصول على نص حالة الطلب بالعربية
     */
    private function getStatusText($status)
    {
        $texts = [
            'pending' => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي'
        ];

        return $texts[$status] ?? $status;
    }
}