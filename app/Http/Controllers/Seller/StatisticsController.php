<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function sales()
    {
        $storeId = Auth::user()->seller->store_id;
        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // الإيرادات اليومية
        $dailyOrders = Order::whereHas('orderItems.product', function($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // إجمالي الإيرادات والطلبات ومتوسط الطلب
        $totalRevenue = Order::whereHas('orderItems.product', function($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })->sum('total_amount');

        $totalOrdersCount = Order::whereHas('orderItems.product', function($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })->count();

        $averageOrder = $totalOrdersCount ? $totalRevenue / $totalOrdersCount : 0;

        // حالة الطلبات
        $ordersByStatus = Order::whereHas('orderItems.product', function($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // أحدث 10 طلبات
        $recentOrders = Order::whereHas('orderItems.product', function($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->latest()->take(10)->get();

        return view('frontend.Seller.dashboard.statistics.sales', compact(
            'dailyOrders',
            'totalRevenue',
            'totalOrdersCount',
            'averageOrder',
            'ordersByStatus',
            'recentOrders'
        ));
    }

public function users()
{
    $storeId = Auth::user()->seller->store_id;
    $startDate = Carbon::now()->subDays(30)->startOfDay();
    $endDate = Carbon::now()->endOfDay();

    // العملاء الذين اشتروا منتجات المتجر
    $customerIds = Order::whereHas('orderItems.product', function($q) use ($storeId) {
            $q->where('store_id', $storeId);
        })
        ->pluck('customer_id')
        ->unique()
        ->toArray();

    // إجمالي العملاء
    $totalUsers = User::whereIn('user_id', $customerIds)
                    ->where('user_type', 0)
                    ->count();

    // العملاء الجدد خلال آخر 30 يوم
    $newUsers = User::whereIn('user_id', $customerIds)
                    ->where('user_type', 0)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();

    // العملاء النشطون خلال آخر 7 أيام
    $activeUsers = User::whereIn('user_id', $customerIds)
                    ->where('user_type', 0)
                    ->where('last_login_at', '>=', Carbon::now()->subDays(7))
                    ->count();

    // التسجيلات اليومية للعملاء
    $dailyRegistrations = User::whereIn('user_id', $customerIds)
        ->where('user_type', 0)
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // أحدث 10 عملاء
    $recentUsers = User::whereIn('user_id', $customerIds)
                    ->where('user_type', 0)
                    ->latest()
                    ->take(10)
                    ->get();

    return view('frontend.Seller.dashboard.statistics.users', compact(
        'totalUsers',
        'newUsers',
        'activeUsers',
        'dailyRegistrations',
        'recentUsers'
    ));
}



    public function products()
    {
        $storeId = Auth::user()->seller->store_id;
        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $totalProducts = Product::where('store_id', $storeId)->count();
        $activeProducts = Product::where('store_id', $storeId)->where('status', 'active')->count();
        $featuredProducts = Product::where('store_id', $storeId)->where('is_featured', true)->count();

        $dailyProducts = Product::where('store_id', $storeId)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $recentProducts = Product::with('category')->where('store_id', $storeId)->latest()->take(10)->get();

        return view('frontend.Seller.dashboard.statistics.products', compact(
            'totalProducts',
            'activeProducts',
            'featuredProducts',
            'dailyProducts',
            'recentProducts'
        ));
    }
}
