<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerRequestController extends Controller
{
    // عرض جميع الطلبات
    public function index()
    {
        $requests = SellerRequest::latest()->paginate(10); 
        return view('frontend.admin.dashboard.seller_requests.index', compact('requests'));
    }

    // عرض تفاصيل طلب محدد
    public function show(SellerRequest $sellerRequest)
    {
        return view('frontend.admin.dashboard.seller_requests.show', compact('sellerRequest'));
    }

    // رفض الطلب
    public function reject(Request $req, SellerRequest $sellerRequest)
    {
        $sellerRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $req->input('reason')
        ]);

        return redirect()->back()->with('error', 'تم رفض الطلب ❌');
    }

    // الموافقة على الطلب
    public function approve(Request $req, SellerRequest $sellerRequest)
    {
        if ($sellerRequest->status === 'approved') {
            return redirect()->back()->with('info', 'تمت الموافقة على هذا الطلب مسبقاً ✅');
        }

        DB::transaction(function () use ($sellerRequest, $req) {

            // ----- صورة البروفايل -----
            $profilePhotoPath = null;
            if ($req->hasFile('profile_photo')) {
                $image = $req->file('profile_photo');
                $imageName = time() . '_profile_' . $image->getClientOriginalName();
                $image->move(public_path('static/images/users'), $imageName);
                $profilePhotoPath = 'static/images/users/' . $imageName;
            } elseif ($sellerRequest->profile_photo) {
                $profilePhotoPath = $sellerRequest->profile_photo;
            }

            // ----- كلمة السر -----
            $password = $sellerRequest->password ? bcrypt($sellerRequest->password) : bcrypt('12345678');

            // ----- إنشاء المستخدم -----
            $user = \App\Models\User::create([
                'name' => $sellerRequest->name,
                'email' => $sellerRequest->email,
                'password' => $password,
                'phone' => $sellerRequest->phone_number ?? $sellerRequest->phone,
                'user_type' => 1, // 1 = بائع
                'active' => true,
                'profile_photo' => $profilePhotoPath,
                'gender' => $sellerRequest->gender,
            ]);

            // ----- صور المتجر -----
            $logoPath = null;
            $bannerPath = null;
            if ($req->hasFile('logo')) {
                $logo = $req->file('logo');
                $logoName = time() . '_logo_' . $logo->getClientOriginalName();
                $logo->move(public_path('static/images/stores'), $logoName);
                $logoPath = 'static/images/stores/' . $logoName;
            } elseif ($sellerRequest->logo) {
                $logoPath = $sellerRequest->logo;
            }

            if ($req->hasFile('banner')) {
                $banner = $req->file('banner');
                $bannerName = time() . '_banner_' . $banner->getClientOriginalName();
                $banner->move(public_path('static/images/stores'), $bannerName);
                $bannerPath = 'static/images/stores/' . $bannerName;
            } elseif ($sellerRequest->banner) {
                $bannerPath = $sellerRequest->banner;
            }

            // ----- إنشاء المتجر -----
            $store = \App\Models\Store::create([
                'user_id' => $user->user_id,
                'store_name' => $sellerRequest->store_name,
                'description' => $sellerRequest->store_description,
                'logo' => $logoPath,
                'banner' => $bannerPath,
                'status' => 'active',
            ]);

            // ----- إنشاء البائع -----
            \App\Models\Seller::create([
                'user_id' => $user->user_id,
                'store_id' => $store->store_id,
                'commission_rate' => 0,
            ]);

            // ----- العنوان -----
            if ($sellerRequest->country || $sellerRequest->city || $sellerRequest->street || $sellerRequest->zip_code) {
                \App\Models\StoreAddress::create([
                    'store_id' => $store->store_id,
                    'country' => $sellerRequest->country,
                    'city' => $sellerRequest->city,
                    'street' => $sellerRequest->street,
                    'zip_code' => $sellerRequest->zip_code,
                    'is_primary' => true,
                ]);
            }

            // ----- الهاتف -----
            if ($sellerRequest->phone_number || $sellerRequest->phone) {
                \App\Models\StorePhone::create([
                    'store_id' => $store->store_id,
                    'phone' => $sellerRequest->phone_number ?? $sellerRequest->phone,
                    'is_primary' => true,
                ]);
            }

            // ----- تحديث حالة الطلب -----
            $sellerRequest->update(['status' => 'approved']);
        });

        return redirect()->back()->with('success', 'تمت الموافقة على الطلب ونقل البيانات ✅');
    }
}
