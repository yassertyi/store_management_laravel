<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use Illuminate\Http\Request;

class FooterLinkController extends Controller
{
    public function index()
    {
        $footerLinks = FooterLink::ordered()->get();
        $sections = ['store', 'customer_service', 'information'];
        return view('frontend.admin.content.footer-links.index', compact('footerLinks', 'sections'));
    }

    public function create()
    {
        $sections = ['store' => 'المتجر', 'customer_service' => 'خدمة العملاء', 'information' => 'معلومات'];
        return view('frontend.admin.content.footer-links.form', compact('sections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        FooterLink::create($validated);

        return redirect()->route('admin.content.footer-links.index')->with('success', 'تم إضافة رابط الفوتر بنجاح');
    }

    public function edit(FooterLink $footerLink)
    {
        $sections = ['store' => 'المتجر', 'customer_service' => 'خدمة العملاء', 'information' => 'معلومات'];
        return view('frontend.admin.content.footer-links.form', compact('footerLink', 'sections'));
    }

    public function update(Request $request, FooterLink $footerLink)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $footerLink->update($validated);

        return redirect()->route('admin.content.footer-links.index')->with('success', 'تم تحديث رابط الفوتر بنجاح');
    }

    public function destroy(FooterLink $footerLink)
    {
        $footerLink->delete();
        return redirect()->route('admin.content.footer-links.index')->with('success', 'تم حذف رابط الفوتر بنجاح');
    }

    public function toggleStatus(FooterLink $footerLink)
    {
        $footerLink->update(['is_active' => !$footerLink->is_active]);
        return redirect()->back()->with('success', 'تم تغيير الحالة بنجاح');
    }
}