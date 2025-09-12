<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreAddress;
use App\Models\StorePhone;
use App\Models\User;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    // عرض جميع المتاجر
public function index()
{
    $stores = Store::with(['user','phones','addresses'])
                   ->orderBy('store_id', 'desc')
                   ->paginate(10);

    return view('frontend.admin.dashboard.stores.stores_all', compact('stores'));
}

    // صفحة إضافة متجر
    public function create()
    {
        $sellers = User::where('user_type', 1)->get();
        return view('frontend.admin.dashboard.stores.forms_stores_all', compact('sellers'));
    }

// حفظ متجر جديد
public function store(Request $request)
{
    $data = $request->validate([
        'user_id' => 'required|exists:users,user_id',
        'store_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        'banner' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        'status' => 'required|in:active,inactive,suspended',
    ]);

    // رفع الصور
    if($request->hasFile('logo')){
        $logoName = time().'_logo.'.$request->logo->getClientOriginalExtension();
        $request->logo->move(public_path('static/images/stors'), $logoName);
        $data['logo'] = $logoName;
    }

    if($request->hasFile('banner')){
        $bannerName = time().'_banner.'.$request->banner->getClientOriginalExtension();
        $request->banner->move(public_path('static/images/stors'), $bannerName);
        $data['banner'] = $bannerName;
    }

    $store = Store::create($data);

    // حفظ الهواتف مع is_primary
    if($request->phones){
        foreach($request->phones as $phone){
            if(!empty($phone['number'])){
                StorePhone::create([
                    'store_id' => $store->store_id,
                    'phone' => $phone['number'],
                    'is_primary' => $phone['is_primary'] ?? 0
                ]);
            }
        }
    }

    // حفظ العناوين مع is_primary
    if($request->addresses){
        foreach($request->addresses as $address){
            if(!empty($address['country']) && !empty($address['city'])){
                StoreAddress::create([
                    'store_id' => $store->store_id,
                    'country' => $address['country'],
                    'city' => $address['city'],
                    'street' => $address['street'] ?? '',
                    'zip_code' => $address['zip_code'] ?? '',
                    'is_primary' => $address['is_primary'] ?? 0
                ]);
            }
        }
    }

    return redirect()->route('admin.stores.index')->with('success','تم إنشاء المتجر بنجاح');
}


    // صفحة تعديل متجر
    public function edit(Store $store)
    {
        $sellers = User::where('user_type', 1)->get();
        $store->load(['phones','addresses']);
        return view('frontend.admin.dashboard.stores.forms_stores_all', compact('store','sellers'));
    }

// تحديث متجر
public function update(Request $request, Store $store)
{
    $data = $request->validate([
        'user_id' => 'required|exists:users,user_id',
        'store_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        'banner' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        'status' => 'required|in:active,inactive,suspended',
    ]);

    // رفع الصور
    if($request->hasFile('logo')){
        $logoName = time().'_logo.'.$request->logo->getClientOriginalExtension();
        $request->logo->move(public_path('static/images/stors'), $logoName);
        $data['logo'] = $logoName;
    }

    if($request->hasFile('banner')){
        $bannerName = time().'_banner.'.$request->banner->getClientOriginalExtension();
        $request->banner->move(public_path('static/images/stors'), $bannerName);
        $data['banner'] = $bannerName;
    }

    $store->update($data);

    // تحديث الهواتف
    $submittedPhones = [];
    if($request->phones){
        foreach($request->phones as $phone){
            if(!empty($phone['number'])){
                $storePhone = StorePhone::updateOrCreate(
                    ['store_id'=>$store->store_id, 'phone'=>$phone['number']],
                    ['is_primary'=>$phone['is_primary'] ?? 0]
                );
                $submittedPhones[] = $storePhone->phone_id;
            }
        }
    }
    StorePhone::where('store_id', $store->store_id)
        ->whereNotIn('phone_id', $submittedPhones)
        ->delete();

    // تحديث العناوين
    $submittedAddresses = [];
    if($request->addresses){
        foreach($request->addresses as $address){
            if(!empty($address['country']) && !empty($address['city'])){
                $storeAddress = StoreAddress::updateOrCreate(
                    [
                        'store_id'=>$store->store_id,
                        'country'=>$address['country'],
                        'city'=>$address['city'],
                        'street'=>$address['street'] ?? '',
                        'zip_code'=>$address['zip_code'] ?? ''
                    ],
                    ['is_primary'=>$address['is_primary'] ?? 0]
                );
                $submittedAddresses[] = $storeAddress->address_id;
            }
        }
    }
    StoreAddress::where('store_id', $store->store_id)
        ->whereNotIn('address_id', $submittedAddresses)
        ->delete();

    $redirectUrl = $request->input('redirect_to', route('admin.stores.index'));
    return redirect($redirectUrl)->with('success','تم تحديث المتجر بنجاح');
}

// عرض تفاصيل المتجر
public function show(Store $store)
{
    $store->load(['user', 'phones', 'addresses']);
    return view('frontend.admin.dashboard.stores.stores_details', compact('store'));
}

    // حذف متجر
    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('admin.stores.index')->with('success','تم حذف المتجر بنجاح');
    }

    // عرض جميع الهواتف
public function phones()
{
    $phones = StorePhone::with('store')
                ->orderBy('store_id', 'desc')
                ->paginate(15);

    $stores = Store::all();
    return view('frontend.admin.dashboard.stores.store_phones_all', compact('phones','stores'));
}



    // حفظ أو تحديث أرقام الهواتف مباشرة من صفحة اتصالات المتاجر
public function savePhones(Request $request)
{
    $data = $request->validate([
        'phones' => 'required|array',
        'phones.*.phone_id' => 'nullable|exists:store_phones,phone_id',
        'phones.*.store_id' => 'required|exists:stores,store_id',
        'phones.*.number' => 'required|string|max:20',
        'phones.*.is_primary' => 'nullable|boolean',
    ]);

    $submittedPhones = [];

    foreach ($data['phones'] as $phone) {
        if (!empty($phone['phone_id'])) {
            // ✅ تعديل رقم موجود
            $storePhone = StorePhone::find($phone['phone_id']);
            if ($storePhone) {
                $storePhone->update([
                    'store_id'   => $phone['store_id'],
                    'phone'      => $phone['number'],
                    'is_primary' => $phone['is_primary'] ?? 0,
                ]);
                $submittedPhones[] = $storePhone->phone_id;
            }
        } else {
            // ✅ إضافة رقم جديد
            $storePhone = StorePhone::create([
                'store_id'   => $phone['store_id'],
                'phone'      => $phone['number'],
                'is_primary' => $phone['is_primary'] ?? 0,
            ]);
            $submittedPhones[] = $storePhone->phone_id;
        }
    }

    // ✅ حذف أي أرقام لم يتم إرسالها (اختياري)
    // StorePhone::whereNotIn('phone_id', $submittedPhones)->delete();

    return redirect()->back()->with('success', 'تم حفظ أرقام الهواتف بنجاح');
}


public function deletePhone(StorePhone $phone)
{
    try {
        $phone->delete();
        return response()->json(['success' => 'تم حذف الرقم بنجاح']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'حدث خطأ أثناء الحذف'], 500);
    }
}



    // عرض جميع العناوين
public function addresses()
{
    $addresses = StoreAddress::with('store')
                    ->orderBy('created_at', 'desc') 
                    ->paginate(15);

    $stores = Store::all();
    return view('frontend.admin.dashboard.stores.store_addresses_all', compact('addresses','stores'));
}


public function saveAddresses(Request $request)
{
    $data = $request->validate([
        'addresses' => 'required|array',
        'addresses.*.address_id' => 'nullable|exists:store_addresses,address_id',
        'addresses.*.store_id' => 'required|exists:stores,store_id',
        'addresses.*.country' => 'required|string|max:255',
        'addresses.*.city' => 'required|string|max:255',
        'addresses.*.street' => 'nullable|string|max:255',
        'addresses.*.zip_code' => 'nullable|string|max:20',
        'addresses.*.is_primary' => 'nullable|boolean',
    ]);

    foreach ($data['addresses'] as $address) {
        if (isset($address['address_id'])) {
            // تعديل العنوان
            $storeAddress = StoreAddress::find($address['address_id']);
            if ($storeAddress) {
                $storeAddress->update([
                    'store_id' => $address['store_id'],
                    'country' => $address['country'],
                    'city' => $address['city'],
                    'street' => $address['street'] ?? '',
                    'zip_code' => $address['zip_code'] ?? '',
                    'is_primary' => $address['is_primary'] ?? 0
                ]);
            }
        } else {
            // إضافة عنوان جديد
            StoreAddress::create([
                'store_id' => $address['store_id'],
                'country' => $address['country'],
                'city' => $address['city'],
                'street' => $address['street'] ?? '',
                'zip_code' => $address['zip_code'] ?? '',
                'is_primary' => $address['is_primary'] ?? 0
            ]);
        }
    }

    return redirect()->back()->with('success', 'تم حفظ التغييرات بنجاح');
}

public function deleteAddress(StoreAddress $address)
{
    try {
        $address->delete();
        return response()->json(['success' => 'تم حذف العنوان بنجاح']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'حدث خطأ أثناء الحذف'], 500);
    }
}

}
