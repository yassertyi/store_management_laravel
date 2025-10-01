<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login-modal');
    }

public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user && Hash::check($request->password, $user->password)) {
        // تسجيل الدخول
        Auth::login($user);

        // التوجيه حسب نوع المستخدم
        if ($user->user_type == 2) {
            return redirect()->route('admin.dashboard'); // لوحة تحكم الأدمن
        } elseif ($user->user_type == 1) {
            return redirect()->route('seller.dashboard'); // لوحة تحكم البائع
        } else {
            return redirect()->route('customer.dashboard'); // لوحة تحكم العميل
        }
    }

    return back()->withErrors([
        'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
    ]);
}


    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('front.home');
    }
}
