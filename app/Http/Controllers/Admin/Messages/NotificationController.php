<?php

namespace App\Http\Controllers\Admin\Messages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * عرض جميع الإشعارات
     */
    public function index()
    {
        $notifications = Notification::with('user')->latest()->paginate(20);
        $users = User::all(); // خيارات الإرسال
        return view('frontend.admin.dashboard.supportTickets.notification_all', compact('notifications', 'users'));
    }

    /**
     * حفظ إشعار جديد أو تعديل موجود
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'users' => 'required|array'
        ]);

        $notificationIds = [];
        $users = $request->users;

        // إرسال الإشعار للجميع
        if (in_array('all', $users)) {
            $users = User::pluck('user_id')->toArray();
        }

        foreach ($users as $userId) {
            if ($request->notification_id) {
                // تعديل إشعار موجود
                $notification = Notification::find($request->notification_id);
                if ($notification) {
                    $notification->update([
                        'title' => $request->title,
                        'content' => $request->content,
                        'user_id' => $userId,
                        'type' => $notification->type ?? 'general', // نوع افتراضي
                        'is_read' => false
                    ]);
                    $notificationIds[] = $notification->notification_id;
                }
            } else {
                // إنشاء إشعار جديد
                $notification = Notification::create([
                    'title' => $request->title,
                    'content' => $request->content,
                    'user_id' => $userId,
                    'type' => 'general', // ← القيمة الافتراضية
                    'is_read' => false
                ]);
                $notificationIds[] = $notification->notification_id;
            }
        }

        return redirect()->back()->with('success', 'تم حفظ الإشعار بنجاح');
    }

    /**
     * عرض إشعار واحد (للتعديل عبر AJAX)
     */
    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        return response()->json($notification);
    }

    /**
     * حذف إشعار
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return response()->json(['status' => 'success']);
    }

    /**
     * تمييز جميع الإشعارات كمقروءة
     */
    public function markAllRead()
    {
        Notification::query()->update(['is_read' => true]);
        return response()->json(['status' => 'success']);
    }
}
