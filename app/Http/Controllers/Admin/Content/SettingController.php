<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group_name')->orderBy('key')->get();
        $groups = [
            'header' => 'إعدادات الهيدر',
            'footer' => 'إعدادات الفوتر', 
            'sections' => 'إعدادات الأقسام',
            'services' => 'إعدادات الخدمات',
            'general' => 'إعدادات عامة',
            'contact' => 'معلومات الاتصال',
            'social' => 'وسائل التواصل'
        ];
        
        return view('frontend.admin.content.settings.index', compact('settings', 'groups'));
    }

    public function create()
    {
        $groups = [
            'header' => 'إعدادات الهيدر',
            'footer' => 'إعدادات الفوتر',
            'sections' => 'إعدادات الأقسام',
            'services' => 'إعدادات الخدمات',
            'general' => 'إعدادات عامة',
            'contact' => 'معلومات الاتصال',
            'social' => 'وسائل التواصل'
        ];
        
        $types = [
            'text' => 'نص',
            'textarea' => 'نص طويل',
            'number' => 'رقم',
            'email' => 'بريد إلكتروني',
            'url' => 'رابط',
            'image' => 'صورة'
        ];
        
        return view('frontend.admin.content.settings.form', compact('groups', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'nullable|string',
            'group_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:50'
        ]);

        Setting::create($validated);

        return redirect()->route('admin.content.settings.index')->with('success', 'تم إضافة الإعداد بنجاح');
    }

    public function edit(Setting $setting)
    {
        $groups = [
            'header' => 'إعدادات الهيدر',
            'footer' => 'إعدادات الفوتر',
            'sections' => 'إعدادات الأقسام',
            'services' => 'إعدادات الخدمات',
            'general' => 'إعدادات عامة',
            'contact' => 'معلومات الاتصال',
            'social' => 'وسائل التواصل'
        ];
        
        $types = [
            'text' => 'نص',
            'textarea' => 'نص طويل',
            'number' => 'رقم',
            'email' => 'بريد إلكتروني',
            'url' => 'رابط',
            'image' => 'صورة'
        ];
        
        return view('frontend.admin.content.settings.form', compact('setting', 'groups', 'types'));
    }

    public function update(Request $request, Setting $setting)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:settings,key,' . $setting->id,
            'value' => 'nullable|string',
            'group_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:50'
        ]);

        $setting->update($validated);

        return redirect()->route('admin.content.settings.index')->with('success', 'تم تحديث الإعداد بنجاح');
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();
        return redirect()->route('admin.content.settings.index')->with('success', 'تم حذف الإعداد بنجاح');
    }

    public function bulkUpdate(Request $request)
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}