<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Customer;

class RegisterController extends Controller
{
    public function showRegisterForm()
{
    return view('frontend.auth.register');
}

public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|confirmed|min:6',
        'phone' => 'nullable|string|max:20',
        'gender' => 'nullable|in:0,1',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'loyalty_points' => 'nullable|integer|min:0',
        'total_orders' => 'nullable|integer|min:0',
    ]);

    $data = $request->only('name','email','phone','gender');
    $data['password'] = Hash::make($request->password);

    // رفع صورة البروفايل إذا موجودة
    if ($request->hasFile('profile_photo')) {
        $file = $request->file('profile_photo');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/profiles'), $filename);
        $data['profile_photo'] = 'uploads/profiles/'.$filename;
    }

    $user = User::create($data);

    // إنشاء بيانات العميل المرتبطة
    Customer::create([
        'user_id' => $user->user_id,
        'loyalty_points' => $request->loyalty_points ?? 0,
        'total_orders' => $request->total_orders ?? 0,
    ]);

    return redirect()->route('login.form')
                     ->with('success', 'تم إنشاء الحساب بنجاح! يمكنك تسجيل الدخول الآن.');
}

}
