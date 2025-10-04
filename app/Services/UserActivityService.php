<?php

namespace App\Services;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Log;

class UserActivityService
{
    /**
     * تسجيل نشاط مستخدم جديد
     */
    public static function log($userId, $activityType, $description, $ipAddress = null, $userAgent = null)
    {
        try {
            return UserActivity::create([
                'user_id' => $userId,
                'activity_type' => $activityType,
                'description' => $description,
                'ip_address' => $ipAddress ?: request()->ip(),
                'user_agent' => $userAgent ?: request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log user activity: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * تسجيل نشاط تسجيل الدخول
     */
    public static function logLogin($userId, $ipAddress = null, $userAgent = null)
    {
        return self::log(
            $userId,
            'login',
            'قام بتسجيل الدخول إلى النظام',
            $ipAddress,
            $userAgent
        );
    }

    /**
     * تسجيل نشاط تسجيل الخروج
     */
    public static function logLogout($userId, $ipAddress = null, $userAgent = null)
    {
        return self::log(
            $userId,
            'logout',
            'قام بتسجيل الخروج من النظام',
            $ipAddress,
            $userAgent
        );
    }

    /**
     * تسجيل محاولة تسجيل دخول فاشلة
     */
    public static function logFailedLogin($userId = null, $email = null, $ipAddress = null, $userAgent = null)
    {
        $description = $userId 
            ? 'محاولة تسجيل دخول فاشلة - كلمة مرور خاطئة'
            : 'محاولة تسجيل دخول فاشلة - بريد إلكتروني غير مسجل: ' . $email;

        return self::log(
            $userId,
            'login_failed',
            $description,
            $ipAddress,
            $userAgent
        );
    }
    /**
 * تسجيل نشاط إدارة الطلبات
 */
public static function logOrderActivity($userId, $action, $orderNumber, $details = null, $ipAddress = null, $userAgent = null)
{
    $description = "قام بـ {$action} للطلب رقم #{$orderNumber}";
    if ($details) {
        $description .= ": {$details}";
    }

    return self::log(
        $userId,
        'order_management',
        $description,
        $ipAddress,
        $userAgent
    );
}

/**
 * تسجيل نشاط تحديث حالة الطلب
 */
public static function logOrderStatusUpdate($userId, $orderNumber, $oldStatus, $newStatus, $ipAddress = null, $userAgent = null)
{
    return self::log(
        $userId,
        'order_status_update',
        "قام بتحديث حالة الطلب رقم #{$orderNumber} من {$oldStatus} إلى {$newStatus}",
        $ipAddress,
        $userAgent
    );
}

/**
 * تسجيل نشاط تحديث حالة الدفع
 */
public static function logPaymentStatusUpdate($userId, $orderNumber, $oldStatus, $newStatus, $ipAddress = null, $userAgent = null)
{
    return self::log(
        $userId,
        'payment_status_update',
        "قام بتحديث حالة الدفع للطلب رقم #{$orderNumber} من {$oldStatus} إلى {$newStatus}",
        $ipAddress,
        $userAgent
    );
}
}