<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use App\Models\User; 
use App\Models\Notification; 
use App\Models\Order; 
use App\Models\Product; 
use DB;

class DashboardController extends Controller
{
    /**
     * إنشاء instance جديد من المتحكم
     */
    public function __construct()
    {
        // تقدر تضيف middleware لو حابب
        // $this->middleware('auth');
        // $this->middleware('admin');
    }
    
    /**
     * عرض لوحة التحكم الرئيسية
     */
    public function index()
    {
        $userId = Auth::id();

        // إشعارات آخر 6 إشعارات خاصة بالمستخدم
        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // إجمالي الطلبات
        $totalOrders = Order::count();

        // إجمالي المبيعات
        $totalSales = Order::sum('total_amount');

        // إجمالي العملاء
        $totalCustomers = User::count();

        // إجمالي المنتجات
        $totalProducts = Product::count();

        // المبيعات حسب الفئة
        $salesByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->select('categories.name', DB::raw('SUM(order_items.total_price) as amount'))
            ->groupBy('categories.name')
            ->get()
            ->toArray(); 

        // تمرير البيانات للواجهة
        return view('frontend.admin.dashboard.home', [
            'notifications' => $notifications,  // متغير منفصل
            'data' => [
                'total_orders'     => $totalOrders,
                'total_sales'      => $totalSales,
                'total_customers'  => $totalCustomers,
                'total_products'   => $totalProducts,
                'sales_by_category'=> $salesByCategory
            ]
        ]);
    }
}
