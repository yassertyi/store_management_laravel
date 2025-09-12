<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        // مشاركة الإشعارات والرسائل مع جميع الـ views
        View::composer('*', function ($view) {
            if(Auth::check()) {
                // إشعارات
                $notifications = Notification::where('user_id', Auth::id())
                                             ->where('is_read', false)
                                             ->orderBy('created_at', 'desc')
                                             ->get();
                $unreadCount = $notifications->count();

                // رسائل غير مقروءة
                $unreadMessages = Message::with('sender')
                                         ->where('receiver_id', Auth::id())
                                         ->where('is_read', false)
                                         ->orderBy('created_at', 'desc')
                                         ->take(5)
                                         ->get();
                $unreadMessagesCount = $unreadMessages->count();

                $view->with([
                    'unreadNotifications' => $notifications,
                    'unreadCount' => $unreadCount,
                    'unreadMessages' => $unreadMessages,
                    'unreadMessagesCount' => $unreadMessagesCount,
                ]);
            }
        });
    }
}
