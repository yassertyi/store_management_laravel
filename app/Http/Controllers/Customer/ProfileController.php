<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * عرض صفحة تعديل الملف الشخصي
     */
    public function edit()
    {
        $user = Auth::user(); // جلب المستخدم الحالي
        return view('frontend.customers.dashboard.profile.edit', compact('user'));
    }

    /**
     * تحديث بيانات الملف الشخصي
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone'  => 'nullable|string|max:20',
            'gender' => 'nullable|in:0,1',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // رفع صورة جديدة إذا وجدت
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                unlink(public_path($user->profile_photo));
            }
            $path = $request->file('profile_photo')->store('uploads/profile_photos', 'public');
            $user->profile_photo = 'storage/' . $path;
        }

        // تحديث باقي البيانات
        $user->update($request->only(['name', 'email', 'phone', 'gender']));

        return redirect()->back()->with('success', 'تم تحديث بياناتك بنجاح');
    }

    /**
     * تحديث كلمة المرور
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}
