<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Seller;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $seller = Seller::where('user_id', $user->user_id)->first();
        return view('frontend.Seller.dashboard.profile.edit', compact('user', 'seller'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $seller = Seller::where('user_id', $user->user_id)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->gender = $request->gender;

        if ($request->hasFile('profile_photo')) {
            if($user->profile_photo && file_exists(public_path($user->profile_photo))){
                unlink(public_path($user->profile_photo));
            }
            $file = $request->file('profile_photo');
            $filename = 'seller_' . $user->user_id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('static/images/users'), $filename);
            $user->profile_photo = 'static/images/users/' . $filename;
        }

        $user->save();

        if ($seller) {
            $seller->commission_rate = $request->commission_rate ?? $seller->commission_rate;
            $seller->save();
        }

        return redirect()->route('seller.profile.edit')->with('success', 'تم تحديث البيانات بنجاح ✅');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('seller.profile.edit')->with('success', 'تم تغيير كلمة المرور بنجاح ✅');
    }
}
