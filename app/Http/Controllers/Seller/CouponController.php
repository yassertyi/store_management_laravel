<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('frontend.Seller.dashboard.Coupons.couponts', compact('coupons'));
    }

    public function create()
    {
        return view('frontend.Seller.dashboard.Coupons.forms_couponts');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code',
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
        ]);

        Coupon::create($request->all());

        return redirect()->route('seller.coupons.index')->with('success','تمت إضافة الكوبون بنجاح');
    }

public function show($id)
{
    $coupon = Coupon::with(['usages.user', 'usages.order'])->findOrFail($id);

    return view('frontend.admin.dashboard.Coupons.coupon_usage_details', compact('coupon'));
}


    public function edit(Coupon $coupon)
    {
        return view('frontend.Seller.dashboard.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code,' . $coupon->coupon_id . ',coupon_id',
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
        ]);

        $coupon->update($request->all());

        return redirect()->route('seller.coupons.index')->with('success','تم تعديل الكوبون بنجاح');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('seller.coupons.index')->with('success','تم حذف الكوبون بنجاح');
    }
}
