<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Customer;
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
        try {
            $storeId = Auth::user()->seller->store_id;
            $startDate = Carbon::now()->subDays(30)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            // الطريقة الصحيحة للحصول على عملاء المتجر
            $customerIds = Order::whereHas('orderItems.product', function($q) use ($storeId) {
                    $q->where('store_id', $storeId);
                })
                ->pluck('customer_id')
                ->unique()
                ->toArray();

            // الحصول على user_ids من العملاء
            $userIds = Customer::whereIn('customer_id', $customerIds)
                ->pluck('user_id')
                ->toArray();

            // استخدام query واحدة للحصول على جميع الإحصائيات
            $userStats = User::whereIn('user_id', $userIds)
                ->where('user_type', 0)
                ->select([
                    DB::raw('COUNT(*) as total_users'),
                    DB::raw('SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as new_users'),
                    DB::raw('SUM(CASE WHEN last_login_at >= ? THEN 1 ELSE 0 END) as active_users')
                ])
                ->addBinding([$startDate, $endDate, Carbon::now()->subDays(7)], 'select')
                ->first();

            // التسجيلات اليومية
            $dailyRegistrations = User::whereIn('user_id', $userIds)
                ->where('user_type', 0)
                ->select([
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as count')
                ])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // ملء الأيام المفقودة في البيانات
            $dailyRegistrations = $this->fillMissingDates($dailyRegistrations, $startDate, $endDate);

            // أحدث 10 عملاء مع معلومات إضافية
            $recentUsers = User::whereIn('user_id', $userIds)
                ->where('user_type', 0)
                ->with(['customer' => function($query) use ($storeId) {
                    $query->withCount(['orders' => function($q) use ($storeId) {
                        $q->whereHas('orderItems.product', function($productQuery) use ($storeId) {
                            $productQuery->where('store_id', $storeId);
                        });
                    }]);
                }])
                ->with(['customer' => function($query) use ($storeId) {
                    $query->withSum(['orders as total_spent' => function($q) use ($storeId) {
                        $q->whereHas('orderItems.product', function($productQuery) use ($storeId) {
                            $productQuery->where('store_id', $storeId);
                        });
                    }], 'total_amount');
                }])
                ->latest()
                ->take(10)
                ->get();

            // إضافة orders_count و total_spent لكل مستخدم
            $recentUsers->each(function($user) {
                $user->orders_count = $user->customer->orders_count ?? 0;
                $user->total_spent = $user->customer->total_spent ?? 0;
            });

            return view('frontend.Seller.dashboard.statistics.users', [
                'totalUsers' => $userStats->total_users ?? 0,
                'newUsers' => $userStats->new_users ?? 0,
                'activeUsers' => $userStats->active_users ?? 0,
                'dailyRegistrations' => $dailyRegistrations,
                'recentUsers' => $recentUsers
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in users statistics: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ في تحميل الإحصائيات: ' . $e->getMessage());
        }
    }

    /**
     * ملء الأيام المفقودة في بيانات التسجيلات
     */
    private function fillMissingDates($data, $startDate, $endDate)
    {
        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);
        $filledData = [];
        
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $existing = $data->firstWhere('date', $dateString);
            
            $filledData[] = [
                'date' => $dateString,
                'count' => $existing ? $existing->count : 0
            ];
        }
        
        return collect($filledData);
    }

    /**
     * طريقة بديلة أبسط (إذا كانت الطريقة الأولى لا تعمل)
     */
    public function usersSimple()
    {
        try {
            $storeId = Auth::user()->seller->store_id;
            
            // طريقة أبسط - جميع العملاء الذين اشتروا من المتجر
            $userIds = DB::table('orders')
                ->join('order_items', 'orders.order_id', '=', 'order_items.order_id')
                ->join('products', 'order_items.product_id', '=', 'products.product_id')
                ->join('customers', 'orders.customer_id', '=', 'customers.customer_id')
                ->where('products.store_id', $storeId)
                ->distinct()
                ->pluck('customers.user_id')
                ->toArray();

            // الإحصائيات الأساسية
            $totalUsers = count($userIds);
            
            $newUsers = User::whereIn('user_id', $userIds)
                ->where('user_type', 0)
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->count();

            $activeUsers = User::whereIn('user_id', $userIds)
                ->where('user_type', 0)
                ->where('last_login_at', '>=', Carbon::now()->subDays(7))
                ->count();

            // التسجيلات اليومية
            $dailyRegistrations = User::whereIn('user_id', $userIds)
                ->where('user_type', 0)
                ->select([
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as count')
                ])
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $dailyRegistrations = $this->fillMissingDates($dailyRegistrations, 
                Carbon::now()->subDays(30), Carbon::now());

            // أحدث 10 عملاء
            $recentUsers = User::whereIn('user_id', $userIds)
                ->where('user_type', 0)
                ->latest()
                ->take(10)
                ->get();

            // إضافة معلومات الطلبات لكل مستخدم
            foreach ($recentUsers as $user) {
                $user->orders_count = DB::table('orders')
                    ->join('order_items', 'orders.order_id', '=', 'order_items.order_id')
                    ->join('products', 'order_items.product_id', '=', 'products.product_id')
                    ->where('products.store_id', $storeId)
                    ->where('orders.customer_id', $user->customer->customer_id ?? null)
                    ->count();

                $user->total_spent = DB::table('orders')
                    ->join('order_items', 'orders.order_id', '=', 'order_items.order_id')
                    ->join('products', 'order_items.product_id', '=', 'products.product_id')
                    ->where('products.store_id', $storeId)
                    ->where('orders.customer_id', $user->customer->customer_id ?? null)
                    ->sum('orders.total_amount') ?? 0;
            }

            return view('frontend.Seller.dashboard.statistics.users', [
                'totalUsers' => $totalUsers,
                'newUsers' => $newUsers,
                'activeUsers' => $activeUsers,
                'dailyRegistrations' => $dailyRegistrations,
                'recentUsers' => $recentUsers
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in usersSimple statistics: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ في تحميل الإحصائيات');
        }
    }

    /**
     * API للحصول على بيانات المستخدمين
     */
    public function getUsersData(Request $request)
    {
        try {
            $period = $request->get('period', 30);
            $storeId = Auth::user()->seller->store_id;

            // نفس منطق usersSimple مع فترة قابلة للتخصيص
            $userIds = DB::table('orders')
                ->join('order_items', 'orders.order_id', '=', 'order_items.order_id')
                ->join('products', 'order_items.product_id', '=', 'products.product_id')
                ->join('customers', 'orders.customer_id', '=', 'customers.customer_id')
                ->where('products.store_id', $storeId)
                ->distinct()
                ->pluck('customers.user_id')
                ->toArray();

            $startDate = Carbon::now()->subDays($period);
            
            $data = [
                'totalUsers' => count($userIds),
                'newUsers' => User::whereIn('user_id', $userIds)
                    ->where('user_type', 0)
                    ->where('created_at', '>=', $startDate)
                    ->count(),
                'activeUsers' => User::whereIn('user_id', $userIds)
                    ->where('user_type', 0)
                    ->where('last_login_at', '>=', Carbon::now()->subDays(7))
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحميل البيانات'
            ], 500);
        }
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
