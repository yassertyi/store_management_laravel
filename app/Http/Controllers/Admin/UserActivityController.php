<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    /**
     * عرض قائمة نشاطات المستخدمين
     */
    public function index(Request $request)
    {
        $query = UserActivity::with('user');
        
        // البحث
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('activity_type', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        }
        
        // التصفية حسب نوع النشاط
        if ($request->has('activity_type') && $request->activity_type != '') {
            $query->where('activity_type', $request->activity_type);
        }
        
        // التصفية حسب التاريخ
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $activities = $query->latest()->paginate(20);
        
        // أنواع النشاطات المتاحة للتصفية
        $activityTypes = UserActivity::distinct()->pluck('activity_type');
        
        return view('frontend.admin.dashboard.user-activities.index', compact('activities', 'activityTypes'));
    }

    /**
     * عرض تفاصيل نشاط معين
     */
    public function show($id)
    {
        $activity = UserActivity::with('user')->findOrFail($id);
        
        return view('frontend.admin.dashboard.user-activities.show', compact('activity'));
    }

    /**
     * حذف نشاط
     */
    public function destroy($id)
    {
        $activity = UserActivity::findOrFail($id);
        $activity->delete();
        
        return redirect()->route('admin.user-activities.index')
            ->with('success', 'تم حذف النشاط بنجاح');
    }

    /**
     * حذف جميع النشاطات القديمة
     */
    public function clearOldActivities()
    {
        // حذف النشاطات الأقدم من 30 يوم
        $thirtyDaysAgo = now()->subDays(30);
        UserActivity::where('created_at', '<', $thirtyDaysAgo)->delete();
        
        return redirect()->route('admin.user-activities.index')
            ->with('success', 'تم حذف النشاطات القديمة بنجاح');
    }
}