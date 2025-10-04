<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\UserActivityService;

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

            // التحقق من حالة النشاط
            if (!$user->active) {
                // تسجيل محاولة تسجيل دخول فاشلة لحساب غير نشط
                UserActivityService::log(
                    $user->user_id,
                    'login_failed',
                    'محاولة تسجيل دخول فاشلة - الحساب غير نشط',
                    $request->ip(),
                    $request->userAgent()
                );

                return back()->withErrors([
                    'email' => 'حسابك غير نشط، يرجى التواصل مع الإدارة لتفعيله',
                ]);
            }

            // تسجيل الدخول
            Auth::login($user);

            // تسجيل نشاط تسجيل الدخول الناجح
            UserActivityService::logLogin(
                $user->user_id,
                $request->ip(),
                $request->userAgent()
            );

            // تحديث آخر وقت تسجيل دخول
            $user->update([
                'last_login_at' => now()
            ]);

            // التوجيه حسب نوع المستخدم
            if ($user->user_type == 2) {
                return redirect()->route('admin.dashboard'); // لوحة تحكم الأدمن
            } elseif ($user->user_type == 1) {
                return redirect()->route('seller.dashboard'); // لوحة تحكم البائع
            } else {
                return redirect()->route('customer.dashboard'); // لوحة تحكم العميل
            }
        }

        // تسجيل محاولة تسجيل دخول فاشلة
        if ($user) {
            UserActivityService::log(
                $user->user_id,
                'login_failed',
                'محاولة تسجيل دخول فاشلة - كلمة مرور خاطئة',
                $request->ip(),
                $request->userAgent()
            );
        } else {
            // إذا لم يتم العثور على المستخدم، نستخدم IP كمعرف
            UserActivityService::log(
                null,
                'login_failed',
                'محاولة تسجيل دخول فاشلة - بريد إلكتروني غير مسجل: ' . $request->email,
                $request->ip(),
                $request->userAgent()
            );
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            
            // تسجيل نشاط تسجيل الخروج
            UserActivityService::logLogout(
                $userId,
                $request->ip(),
                $request->userAgent()
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('front.home');
    }
}