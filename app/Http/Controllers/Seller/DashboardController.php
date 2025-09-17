<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
        /**
     * عرض لوحة تحكم البائع
     */
    public function index()
    {
        // هنا تقدر تمرر بيانات من قاعدة البيانات مثلاً عدد المنتجات، الطلبات، إلخ.
        // مؤقتاً نخليها ترجع فيو فقط
        return view('frontend.Seller.dashboard.home');
    }
}
