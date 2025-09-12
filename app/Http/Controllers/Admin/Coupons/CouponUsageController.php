<?php

namespace App\Http\Controllers\Admin\Coupons;

use App\Http\Controllers\Controller;
use App\Models\CouponUsage;
use Illuminate\Http\Request;

class CouponUsageController extends Controller
{
    // عرض كل استخدامات الكوبونات
    public function index()
    {
        $usages = CouponUsage::with(['coupon', 'user', 'order'])->latest('used_at')->paginate(15);
        return view('frontend.admin.dashboard.Coupons.coupon_usage', compact('usages'));
    }

    // عرض تفاصيل استخدام معين
    public function show($id)
    {
        $usage = CouponUsage::with(['coupon', 'user', 'order'])->findOrFail($id);
        return view('frontend.admin.dashboard.Coupons.coupon_usage_details', compact('usage'));
    }

    // حذف استخدام كوبون
    public function destroy($id)
    {
        $usage = CouponUsage::findOrFail($id);
        $usage->delete();
        return redirect()->route('admin.coupon-usage.index')->with('success', '🗑️ تم حذف استخدام الكوبون بنجاح');
    }
}
