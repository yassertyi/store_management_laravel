<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index()
    {
        $socialMedia = SocialMedia::ordered()->get();
        $platforms = [
            'facebook' => 'Facebook',
            'twitter' => 'Twitter', 
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'linkedin' => 'LinkedIn',
            'snapchat' => 'Snapchat'
        ];
        return view('frontend.admin.content.social-media.index', compact('socialMedia', 'platforms'));
    }

    public function create()
    {
        $platforms = [
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'youtube' => 'YouTube', 
            'linkedin' => 'LinkedIn',
            'snapchat' => 'Snapchat'
        ];
        return view('frontend.admin.content.social-media.form', compact('platforms'));
    }

    private $platformIcons = [
        'facebook' => 'lab la-facebook-f',
        'twitter' => 'lab la-twitter',
        'instagram' => 'lab la-instagram',
        'youtube' => 'lab la-youtube',
        'linkedin' => 'lab la-linkedin-in',
        'snapchat' => 'lab la-snapchat',
        'tiktok' => 'lab la-tiktok',
        'whatsapp' => 'lab la-whatsapp',
        'telegram' => 'lab la-telegram',
    ];

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        // تعيين الأيقونة تلقائياً بناءً على المنصة
        $validated['icon_class'] = $this->platformIcons[$validated['platform']] ?? 'lab la-link';

        SocialMedia::create($validated);

        return redirect()->route('admin.content.social-media.index')->with('success', 'تم إضافة وسيلة التواصل بنجاح');
    }

    public function edit(SocialMedia $socialMedia)
    {
        $platforms = [
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'linkedin' => 'LinkedIn',
            'snapchat' => 'Snapchat'
        ];
        return view('frontend.admin.content.social-media.form', compact('socialMedia', 'platforms'));
    }

    public function update(Request $request, SocialMedia $socialMedia)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        // تعيين الأيقونة تلقائياً بناءً على المنصة
        $validated['icon_class'] = $this->platformIcons[$validated['platform']] ?? 'lab la-link';

        $socialMedia->update($validated);

        return redirect()->route('admin.content.social-media.index')->with('success', 'تم تحديث وسيلة التواصل بنجاح');
    }


    public function destroy(SocialMedia $socialMedia)
    {
        $socialMedia->delete();
        return redirect()->route('admin.content.social-media.index')->with('success', 'تم حذف وسيلة التواصل بنجاح');
    }

    public function toggleStatus(SocialMedia $socialMedia)
    {
        $socialMedia->update(['is_active' => !$socialMedia->is_active]);
        return redirect()->back()->with('success', 'تم تغيير الحالة بنجاح');
    }
}