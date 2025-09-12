<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use App\Models\User; use App\Models\Notification; 
use App\Models\Order; use App\Models\Product; 
use App\Models\Category; use DB;
class DashboardController extends Controller
{
    /**
     * إنشاء instance جديد من المتحكم
     */
    public function __construct()
    {
        // يمكنك إضافة middleware للتحقق من الصلاحيات لاحقاً
        // $this->middleware('auth');
        // $this->middleware('admin');
    }
    
    /**
     * عرض لوحة التحكم الرئيسية
     */
    public function index()
    {
        $userId = Auth::id();

        // إشعارات آخر 10 إشعارات
        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(10)
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


        return view('frontend.admin.dashboard.home', [
            'data' => [
                'notifications' => $notifications,
                'total_orders' => $totalOrders,
                'total_sales' => $totalSales,
                'total_customers' => $totalCustomers,
                'total_products' => $totalProducts,
                'sales_by_category' => $salesByCategory
            ]
        ]);
    }
}