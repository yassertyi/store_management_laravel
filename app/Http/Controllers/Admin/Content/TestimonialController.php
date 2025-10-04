<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('frontend.admin.content.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('frontend.admin.content.testimonials.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'sort_order' => 'nullable|integer',
        ]);

        // رفع صورة العميل
        if ($request->hasFile('customer_image')) {
            $validated['customer_image'] = $request->file('customer_image')->store('testimonials', 'public');
        }

        // تعيين الحالة إلى مفعلة افتراضياً عند الإضافة من الأدمن
        $validated['is_active'] = true;

        Testimonial::create($validated);

        return redirect()->route('admin.content.testimonials.index')->with('success', 'تم إضافة رأي العميل بنجاح');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('frontend.admin.content.testimonials.form', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'sort_order' => 'nullable|integer',
        ]);

        // رفع صورة العميل
        if ($request->hasFile('customer_image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($testimonial->customer_image && Storage::disk('public')->exists($testimonial->customer_image)) {
                Storage::disk('public')->delete($testimonial->customer_image);
            }
            $validated['customer_image'] = $request->file('customer_image')->store('testimonials', 'public');
        }

        // حذف الصورة إذا طلب المستخدم ذلك
        if ($request->has('remove_customer_image') && $testimonial->customer_image) {
            Storage::disk('public')->delete($testimonial->customer_image);
            $validated['customer_image'] = null;
        }

        $testimonial->update($validated);

        return redirect()->route('admin.content.testimonials.index')->with('success', 'تم تحديث رأي العميل بنجاح');
    }

    public function destroy(Testimonial $testimonial)
    {
        // حذف الصورة إذا كانت موجودة
        if ($testimonial->customer_image && Storage::disk('public')->exists($testimonial->customer_image)) {
            Storage::disk('public')->delete($testimonial->customer_image);
        }
        
        $testimonial->delete();
        return redirect()->route('admin.content.testimonials.index')->with('success', 'تم حذف رأي العميل بنجاح');
    }

    public function toggleStatus(Testimonial $testimonial)
    {
        $testimonial->update(['is_active' => !$testimonial->is_active]);
        
        $status = $testimonial->is_active ? 'مفعل' : 'معطل';
        return redirect()->back()->with('success', "تم $status الرأي بنجاح");
    }
}