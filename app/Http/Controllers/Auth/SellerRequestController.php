<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerRequest;

class SellerRequestController extends Controller
{
    // عرض نموذج التسجيل كبائع
    public function showForm()
    {
        return view('frontend.auth.seller_requests.register');
    }

    // حفظ الطلب
  public function store(Request $request)
{
    $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => 'required|email|unique:seller_requests,email',
        'password'      => 'required|string|min:6',
        'phone'         => 'nullable|string|max:20',
        'gender'        => 'nullable|string',
        'store_name'    => 'required|string|max:255',
    ]);

    // رفع الصور والملفات في نفس المسار
    $profilePhoto = null;
    if ($request->hasFile('profile_photo')) {
        $image = $request->file('profile_photo');
        $imageName = time().'_'.$image->getClientOriginalName();
        $image->move(public_path('static/images/users'), $imageName);
        $profilePhoto = 'static/images/users/' . $imageName;
    }

    $logo = null;
    if ($request->hasFile('logo')) {
        $image = $request->file('logo');
        $imageName = time().'_'.$image->getClientOriginalName();
        $image->move(public_path('static/images/stors'), $imageName);
        $logo = 'static/images/stors/' . $imageName;
    }

    $banner = null;
    if ($request->hasFile('banner')) {
        $image = $request->file('banner');
        $imageName = time().'_'.$image->getClientOriginalName();
        $image->move(public_path('static/images/stors'), $imageName);
        $banner = 'static/images/stors/' . $imageName;
    }

    $documentPath = null;
    if ($request->hasFile('document_path')) {
        $file = $request->file('document_path');
        $fileName = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('static/images/stors'), $fileName);
        $documentPath = 'static/images/stors/' . $fileName;
    }

    $additionalImages = [];
    if ($request->hasFile('additional_images')) {
        foreach ($request->file('additional_images') as $img) {
            $imgName = time().'_'.$img->getClientOriginalName();
            $img->move(public_path('static/images/seller/additional_images'), $imgName);
            $additionalImages[] = 'static/images/seller/additional_images/' . $imgName;
        }
    }

    // حفظ بيانات طلب البائع
    $sellerRequest = SellerRequest::create([
        'name'                  => $request->name,
        'email'                 => $request->email,
        'password'              => $request->password,
        'phone'                 => $request->phone,
        'gender'                => $request->gender,
        'profile_photo'         => $profilePhoto,
        'store_name'            => $request->store_name,
        'store_description'     => $request->store_description,
        'logo'                  => $logo,
        'banner'                => $banner,
        'business_license_number'=> $request->business_license_number,
        'document_path'         => $documentPath,
        'additional_images'     => $additionalImages,
        'status'                => 'pending',
    ]);

    // إرسال إشعارات للمشرفين
    $admins = \App\Models\User::where('user_type', 2)->get();
    foreach ($admins as $admin) {
        \App\Models\Notification::create([
            'user_id'       => $admin->user_id,
            'title'         => 'طلب بائع جديد',
            'content'       => "تم إنشاء طلب جديد من المستخدم: {$sellerRequest->name}، البريد: {$sellerRequest->email}, اسم المتجر: {$sellerRequest->store_name}.",
            'type'          => 'seller_request',
            'is_read'       => false,
            'related_id'    => $sellerRequest->id,
            'related_type'  => SellerRequest::class,
        ]);
    }

    return redirect()->back()->with('success', '✅ تم إرسال طلبك بنجاح، وسيتم مراجعته من قبل الإدارة.');
}
}