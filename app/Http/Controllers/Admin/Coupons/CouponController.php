<?php

namespace App\Http\Controllers\Admin\Coupons;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    // عرض كل الكوبونات
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('frontend.admin.dashboard.Coupons.couponts', compact('coupons'));
    }

    // صفحة إنشاء كوبون
    public function create()
    {
        $code = 'CPN-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        return view('frontend.admin.dashboard.Coupons.forms_couponts', compact('code'));
    }

    // تخزين كوبون جديد
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code|max:50',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:start_date',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
        ]);

        Coupon::create([
            'code' => $request->code,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_discount_amount' => $request->max_discount_amount ?? 0,
            'start_date' => $request->start_date,
            'expiry_date' => $request->expiry_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', '✅ تم إنشاء الكوبون بنجاح');
    }

    // صفحة التعديل
    public function edit(Coupon $coupon)
    {
        return view('frontend.admin.dashboard.Coupons.forms_couponts', compact('coupon'));
    }

    // تحديث الكوبون
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:start_date',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
        ]);

        $coupon->update([
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_discount_amount' => $request->max_discount_amount ?? 0,
            'start_date' => $request->start_date,
            'expiry_date' => $request->expiry_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', '✅ تم تحديث الكوبون بنجاح');
    }

    // حذف الكوبون
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', '🗑️ تم حذف الكوبون بنجاح');
    }
}
