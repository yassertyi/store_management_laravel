<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    // قائمة البائعين
    public function index()
    {
        $sellers = Seller::with(['user', 'store'])->orderBy('seller_id','desc')->paginate(10);
        return view('frontend.admin.dashboard.users.seller_all', compact('sellers'));
    }

    // صفحة إنشاء بائع جديد
    public function create()
    {
        $users = User::where('user_type', 1) 
             ->doesntHave('seller')
             ->get();

        $stores = Store::doesntHave('seller')->get();
        return view('frontend.admin.dashboard.users.forms_seller', compact('users', 'stores'));
    }

    // تخزين بائع جديد
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id|unique:sellers,user_id',
            'store_id' => 'required|exists:stores,store_id|unique:sellers,store_id',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        Seller::create($request->all());

        return redirect()->route('admin.sellers.index')->with('success', 'تمت إضافة البائع بنجاح');
    }

    // صفحة تعديل بائع
    public function edit(Seller $seller)
    {
        $users = User::where('user_type', 1)
             ->where(function($query) use ($seller) {
                 $query->whereDoesntHave('seller') 
                       ->orWhere('user_id', $seller->user_id); 
             })->get();


        $stores = Store::where(function($query) use ($seller) {
                    $query->whereDoesntHave('seller')
                          ->orWhere('store_id', $seller->store_id);
                 })->get();


        return view('frontend.admin.dashboard.users.forms_seller', compact('seller','users','stores'));
    }

    // تحديث بائع
    public function update(Request $request, Seller $seller)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id|unique:sellers,user_id,'.$seller->seller_id.',seller_id',
            'store_id' => 'required|exists:stores,store_id|unique:sellers,store_id,'.$seller->seller_id.',seller_id',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $seller->update($request->all());

        return redirect()->route('admin.sellers.index')->with('success', 'تم تحديث بيانات البائع بنجاح');
    }

    // حذف بائع
    public function destroy(Seller $seller)
    {
        $seller->delete();
        return redirect()->route('admin.sellers.index')->with('success', 'تم حذف البائع بنجاح');
    }
}
