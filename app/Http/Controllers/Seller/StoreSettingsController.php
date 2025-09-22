<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreAddress;
use App\Models\StorePhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreSettingsController extends Controller
{
    // عرض صفحة إعدادات المتجر
    public function edit()
    {
        $user = Auth::user();
        $store = Store::whereHas('seller', fn($q) => $q->where('user_id', $user->user_id))->first();

        return view('frontend.Seller.dashboard.settings.stores-settings', compact('user', 'store'));
    }

    // تحديث بيانات المتجر الأساسية + العناوين + الهواتف
    public function update(Request $request)
    {
        $user = Auth::user();
        $store = Store::whereHas('seller', fn($q) => $q->where('user_id', $user->user_id))->first();

        $request->validate([
            'store_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'phones.*.number' => 'nullable|string|max:20',
            'addresses.*.country' => 'nullable|string|max:100',
            'addresses.*.city' => 'nullable|string|max:100',
            'addresses.*.street' => 'nullable|string|max:255',
            'addresses.*.zip_code' => 'nullable|string|max:20',
        ]);

        if ($store) {
            // تحديث البيانات الأساسية
            $store->store_name = $request->store_name;
            $store->description = $request->description;

            // رفع الصور
            if ($request->hasFile('logo')) {
                $logoName = time().'_logo.'.$request->file('logo')->getClientOriginalExtension();
                $request->file('logo')->move(public_path('static/images/stors'), $logoName);
                $store->logo = $logoName;
            }
            if ($request->hasFile('banner')) {
                $bannerName = time().'_banner.'.$request->file('banner')->getClientOriginalExtension();
                $request->file('banner')->move(public_path('static/images/stors'), $bannerName);
                $store->banner = $bannerName;
            }

            $store->save();

            // تحديث الهواتف
            if($request->phones){
                StorePhone::where('store_id', $store->store_id)->delete();
                foreach($request->phones as $phone){
                    if(!empty($phone['number'])){
                        StorePhone::create([
                            'store_id' => $store->store_id,
                            'phone' => $phone['number'],
                            'is_primary' => $phone['is_primary'] ?? 0,
                        ]);
                    }
                }
            }

            // تحديث العناوين
            if($request->addresses){
                StoreAddress::where('store_id', $store->store_id)->delete();
                foreach($request->addresses as $address){
                    if(!empty($address['country']) && !empty($address['city'])){
                        StoreAddress::create([
                            'store_id' => $store->store_id,
                            'country' => $address['country'],
                            'city' => $address['city'],
                            'street' => $address['street'] ?? '',
                            'zip_code' => $address['zip_code'] ?? '',
                            'is_primary' => $address['is_primary'] ?? 0,
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'تم تحديث بيانات المتجر بنجاح');
    }
}
