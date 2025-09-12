<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use DB;

class StatisticsController extends Controller
{
    // صفحة إحصائيات المبيعات
    public function sales()
    {
        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // الطلبات اليومية + الإيرادات
        $dailyOrders = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ملخص الإحصائيات
        $totalRevenue = Order::sum('total_amount');
        $totalOrdersCount = Order::count();
        $averageOrder = $totalOrdersCount ? $totalRevenue / $totalOrdersCount : 0;

        // الطلبات حسب الحالة
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // آخر 10 طلبات
        $recentOrders = Order::latest()->take(10)->get();

        return view('frontend.admin.dashboard.statistics.sales', compact(
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
    $startDate = \Carbon\Carbon::now()->subDays(30)->startOfDay();
    $endDate   = \Carbon\Carbon::now()->endOfDay();

    // إجمالي المستخدمين
    $totalUsers = \App\Models\User::count();

    // مستخدمين جدد آخر 30 يوم
    $newUsers = \App\Models\User::whereBetween('created_at', [$startDate, $endDate])->count();

    // مستخدمين نشطين (آخر 7 أيام)
    $activeUsers = \App\Models\User::where('last_login_at', '>=', \Carbon\Carbon::now()->subDays(7))->count();

    // تسجيلات يومية
    $dailyRegistrations = \App\Models\User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // أحدث 10 مستخدمين
    $recentUsers = \App\Models\User::latest()->take(10)->get();

    return view('frontend.admin.dashboard.statistics.users', compact(
        'totalUsers',
        'newUsers',
        'activeUsers',
        'dailyRegistrations',
        'recentUsers'
    ));
}

 // صفحة إحصائيات المنتجات
    public function products()
    {
        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // ملخص الإحصائيات
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        $featuredProducts = Product::where('is_featured', true)->count();

        // الإضافات اليومية للمنتجات
        $dailyProducts = Product::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // أحدث 10 منتجات
        $recentProducts = Product::with('store', 'category')->latest()->take(10)->get();

        return view('frontend.admin.dashboard.statistics.products', compact(
            'totalProducts',
            'activeProducts',
            'featuredProducts',
            'dailyProducts',
            'recentProducts'
        ));
    }
}
