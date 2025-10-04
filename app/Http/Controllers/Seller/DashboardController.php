<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\Notification;
use App\Models\Category;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * عرض لوحة تحكم البائع
     */
    public function index()
    {
        $user = Auth::user();
        
        // التحقق من وجود علاقة البائع والمتجر
        if (!$user->seller || !$user->seller->store) {
            return view('frontend.Seller.dashboard.home', [
                'data' => $this->getEmptyData()
            ]);
        }

        $store = $user->seller->store;
        $storeId = $store->store_id;

        try {
            $data = [
                'notifications' => $this->getNotifications($user->user_id),
                'total_orders' => $this->getTotalOrders($storeId),
                'total_sales' => $this->getTotalSales($storeId),
                'total_customers' => $this->getTotalCustomers($storeId),
                'total_products' => $this->getTotalProducts($storeId),
                'sales_by_category' => $this->getSalesByCategory($storeId)
            ];
        } catch (\Exception $e) {
            $data = $this->getEmptyData();
        }

        return view('frontend.Seller.dashboard.home', compact('data'));
    }

    /**
     * الحصول على بيانات الرسم البياني
     */
    public function getChartData(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->seller || !$user->seller->store) {
            return response()->json([
                'labels' => [],
                'sales' => []
            ]);
        }

        $store = $user->seller->store;
        $storeId = $store->store_id;
        
        $days = $request->get('days', 7);
        $endDate = now();
        $startDate = now()->subDays($days);

        try {
            $salesData = DB::table('orders')
                ->join('order_items', 'orders.order_id', '=', 'order_items.order_id')
                ->join('products', 'order_items.product_id', '=', 'products.product_id')
                ->where('products.store_id', $storeId)
                ->where('orders.payment_status', 'paid')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('DATE(orders.created_at) as date'),
                    DB::raw('SUM(order_items.total_price) as total_sales')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $labels = [];
            $sales = [];

            // ملء البيانات للأيام المفقودة
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                $dateStr = $currentDate->format('Y-m-d');
                $labels[] = $currentDate->format('d M');
                
                $sale = $salesData->firstWhere('date', $dateStr);
                $sales[] = $sale ? floatval($sale->total_sales) : 0;
                
                $currentDate->addDay();
            }

            return response()->json([
                'labels' => $labels,
                'sales' => $sales
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'labels' => [],
                'sales' => []
            ]);
        }
    }

    /**
     * دالة مساعدة للحصول على أيقونة الفئة
     */
    public static function getCategoryIcon($categoryName)
    {
        $icons = [
            'إلكترونيات' => 'la-laptop',
            'ملابس' => 'la-tshirt',
            'أثاث' => 'la-couch',
            'مجوهرات' => 'la-gem',
            'هواتف' => 'la-mobile',
            'كتب' => 'la-book',
            'رياضة' => 'la-futbol',
            'جمال' => 'la-palette',
            'منزل' => 'la-home',
            'أطفال' => 'la-child'
        ];

        foreach ($icons as $key => $icon) {
            if (str_contains($categoryName, $key)) {
                return $icon;
            }
        }

        return 'la-shopping-bag';
    }

    /**
     * الدوال المساعدة
     */
    private function getNotifications($userId)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($notification) {
                $notification->time = Carbon::parse($notification->created_at)->diffForHumans();
                return $notification;
            });
    }

    private function getTotalOrders($storeId)
    {
        return Order::whereHas('items.product', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->count();
    }

    private function getTotalSales($storeId)
    {
        return Order::whereHas('items.product', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->where('payment_status', 'paid')->sum('total_amount') ?? 0;
    }

    private function getTotalCustomers($storeId)
    {
        return Order::whereHas('items.product', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->distinct('customer_id')->count('customer_id');
    }

    private function getTotalProducts($storeId)
    {
        return Product::where('store_id', $storeId)->count();
    }

    private function getSalesByCategory($storeId)
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->where('products.store_id', $storeId)
            ->where('orders.payment_status', 'paid')
            ->select('categories.name', DB::raw('SUM(order_items.total_price) as amount'))
            ->groupBy('categories.category_id', 'categories.name')
            ->get();
    }

    private function getEmptyData()
    {
        return [
            'notifications' => collect([]),
            'total_orders' => 0,
            'total_sales' => 0,
            'total_customers' => 0,
            'total_products' => 0,
            'sales_by_category' => collect([])
        ];
    }
}