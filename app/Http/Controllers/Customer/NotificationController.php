<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * عرض صفحة الإشعارات
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // فلترة حسب النوع
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        // فلترة حسب حالة القراءة
        if ($request->has('read_status')) {
            if ($request->read_status == 'read') {
                $query->where('is_read', true);
            } elseif ($request->read_status == 'unread') {
                $query->where('is_read', false);
            }
        }

        $notifications = $query->paginate(15);
        $unreadCount = Notification::where('user_id', Auth::id())->where('is_read', false)->count();

        return view('frontend.customers.dashboard.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * تمييز إشعار كمقروء
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('notification_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'تم تمييز الإشعار كمقروء'
        ]);
    }

    /**
     * تمييز الكل كمقروء
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'تم تمييز جميع الإشعارات كمقروءة'
        ]);
    }

    /**
     * حذف إشعار
     */
    public function destroy($id)
    {
        $notification = Notification::where('notification_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return back()->with('success', 'تم حذف الإشعار بنجاح');
    }

    /**
     * حذف جميع الإشعارات المقروءة
     */
    public function clearRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', true)
            ->delete();

        return back()->with('success', 'تم حذف جميع الإشعارات المقروءة');
    }

    /**
     * الحصول على عدد الإشعارات غير المقروءة (لـ AJAX)
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}