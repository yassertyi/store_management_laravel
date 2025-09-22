<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        // إذا لم يسجل الدخول
        if (!Auth::check()) {
            return redirect()->route('login.form')
                ->with('error', 'يجب تسجيل الدخول أولاً.');
        }

        // يجيب نوع المستخدم
        $userType = Auth::user()->user_type;

        // إذا لم يكن النوع ضمن المسموح به → رجعه لصفحة تسجيل الدخول مع رسالة
        if (!in_array($userType, $types)) {
            Auth::logout(); // يفضل تسجيل خروجه
            return redirect()->route('login.form')
                ->with('error', 'غير مسموح لك بالدخول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}
