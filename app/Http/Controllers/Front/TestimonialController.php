<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        // استخدام السكوب مباشرة
        $testimonials = Testimonial::active()->ordered()->paginate(12);
        
        // للتحقق من البيانات، يمكنك إزالة التعليق من السطر التالي مؤقتاً
        // dd($testimonials);
        
        return view('frontend.home.testimonials.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'nullable|string|max:255',
            'content' => 'required|string|min:10|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // رفع صورة العميل إذا وجدت
        if ($request->hasFile('customer_image')) {
            $validated['customer_image'] = $request->file('customer_image')->store('testimonials', 'public');
        }

        // تعيين الحالة إلى غير مفعل حتى يراجعها الأدمن
        $validated['is_active'] = true;

        Testimonial::create($validated);

        return redirect()->route('front.testimonials.all')->with('success', 'شكراً لك! تم إضافة رأيك بنجاح وسيتم مراجعته قبل النشر.');
    }
}